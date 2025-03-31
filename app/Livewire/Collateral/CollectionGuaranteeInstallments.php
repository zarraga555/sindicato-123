<?php

namespace App\Livewire\Collateral;

use App\Models\AccountLetters;
use App\Models\CashFlow;
use App\Models\Collateral;
use App\Models\paymentPlans;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;


class CollectionGuaranteeInstallments extends Component
{
    use WithPagination;

    public string $search = '';
    public $showPaymentModal = false;
    public $date;
    public $amount_paid;
    public $attachment;
    public $description;
    // Label for the modal
    public $debtorName;
    public $dueDate;
    public $pendingAmount;
    public $currency;
    public $instalmentNumber;
    public $bank_id;
    //ID
    public $collateral_id;

    public function render()
    {
        try {
            $collaterals = Collateral::where('vehicle_id', 'like', '%' . $this->search . '%')
                ->where('status', '!=', 'uncollectible')
                ->orderByDesc('created_at')
                ->paginate(25);

            return view('livewire.collateral.collection-guarantee-installments', compact('collaterals'));
        } catch (\Exception $e) {
            Log::error('Error al obtener la lista de préstamos incobrables: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al cargar los datos.');
            return view('livewire.collateral.collection-guarantee-installments', ['collaterals' => collect()]);
        }
    }

    public function openModalPayment($id)
    {
        $this->showPaymentModal = true;
        $this->collateral_id = $id;
        $this->reedCollateral($id);
    }

    public function closeModalPayment()
    {
        $this->showPaymentModal = false;
    }

    public function reedCollateral($id)
    {
        try {
            // Busca la garantia, si no existe lanza error automáticamente
            $collateralDetails = Collateral::findOrFail($id);

            // Obtener la cuota más reciente que aún tenga saldo pendiente ("unpaid" o "partial")
            $fee = paymentPlans::where('collateral_id', $id)
                ->whereIn('paymentStatus', ['unpaid', 'partial'])
                ->orderBy('datePayment', 'asc')
                ->first();

            // Si no hay cuotas pendientes, buscar la última cuota pagada
            if (!$fee) {
                $fee = paymentPlans::where('collateral_id', $id)
                    ->where('paymentStatus', 'paid')
                    ->orderBy('datePayment', 'desc') // Última cuota pagada
                    ->first();
            }

            // Asignar valores básicos del préstamo
            $this->debtorName = $collateralDetails->driver_partner_name;
            $this->currency = $collateralDetails->cashFlows?->banks?->currency_type ?? 'Bs'; // Evita error si no hay relación

            // Si existe una cuota válida
            if ($fee) {
                $this->instalmentNumber = $fee->instalmentNumber ?? 'N/A';
                $this->pendingAmount = ($fee->amount - $fee->amount_paid) > 0 ? ($fee->amount - $fee->amount_paid) : 0;
                $this->dueDate = $fee->datePayment ?? 'N/A';
            } else {
                // Si no hay cuotas programadas ni pagadas
                $this->instalmentNumber = 'N/A';
                $this->pendingAmount = 0;
                $this->dueDate = 'N/A';
            }
        } catch (\Exception $e) {
            // Manejo de errores
            Log::error('Error en reedCollateral: ' . $e->getMessage());
            return response()->json(['error' => 'No se pudo obtener la información del préstamo.'], 500);
        }
    }

    public function paymentDues()
    {
        $this->validate([
            'date' => 'nullable|date',
            'amount_paid' => 'required|numeric|min:0.01',
            'attachment' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'description' => 'nullable|string|max:255',
        ]);
    
        try {
            // Validar el préstamo
            $collateral = Collateral::find($this->collateral_id);
            if (!$collateral) {
                session()->flash('error', 'La garantia no existe.');
                return;
            }
    
            // Crear flujo de caja y obtener su ID
            $cashFlowId = $this->createCashFlow();
            if (!$cashFlowId) {
                session()->flash('error', 'No se pudo registrar el flujo de caja.');
                return;
            }
    
            $remainingPayment = $this->amount_paid; // Monto total pagado
    
            // Obtener cuotas pendientes ordenadas por fecha de pago
            $fees = paymentPlans::where('collateral_id', $this->collateral_id)
                ->whereIn('paymentStatus', ['unpaid', 'partial'])
                ->orderBy('datePayment', 'asc')
                ->get();
    
            if ($fees->isEmpty()) {
                session()->flash('error', 'No hay cuotas pendientes para este préstamo.');
                return;
            }
    
            $atLeastOnePaid = false; // Para saber si se ha pagado al menos una cuota
    
            foreach ($fees as $fee) {
                if ($remainingPayment <= 0) break; // Salir si ya no hay saldo
    
                $pendingAmount = $fee->amount - $fee->amount_paid; // Cuánto falta pagar en esta cuota
    
                if ($remainingPayment >= $pendingAmount) {
                    // Pago cubre toda la cuota → Marcar como pagada
                    $fee->update([
                        'paymentStatus' => 'paid',
                        'amount_paid' => $fee->amount,
                        'cash_flow_id' => $cashFlowId, // Guardar ID del flujo de caja
                    ]);
                    $remainingPayment -= $pendingAmount;
                } else {
                    // Pago parcial → Actualizar y marcar como 'partial'
                    $fee->update([
                        'paymentStatus' => 'partial',
                        'amount_paid' => $fee->amount_paid + $remainingPayment,
                        'cash_flow_id' => $cashFlowId, // Guardar ID del flujo de caja
                    ]);
                    $remainingPayment = 0;
                }
    
                $atLeastOnePaid = true;
            }
    
            // Verificar si todas las cuotas están pagadas y actualizar el préstamo
            $pendingFees = paymentPlans::where('collateral_id', $this->collateral_id)
                ->whereIn('paymentStatus', ['unpaid', 'partial'])
                ->exists();
    
            if (!$pendingFees) {
                $collateral->update(['status' => 'paid']);
            } elseif ($collateral->status === 'unpaid' && $atLeastOnePaid) {
                $collateral->update(['status' => 'partial']);
            }
    
            // Guardar detalles en la última cuota pagada
            if ($this->attachment || $this->description) {
                $lastFee = $fees->first();
                if ($lastFee) {
                    $lastFee->update([
                        'attachment' => $this->attachment,
                        'description' => $this->description,
                    ]);
                }
            }
    
            $this->resetForm();
            session()->flash('success', 'Pago registrado correctamente.');
            $this->closeModalPayment();
        } catch (\Exception $e) {
            Log::error('Error al registrar el pago: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al registrar el pago.');
        }
    }
    
    /**
     * Crea un flujo de caja y devuelve el ID
     */
    public function createCashFlow()
    {
        try {
            if (!$this->collateral_id || !$this->amount_paid || $this->amount_paid <= 0) {
                Log::warning('Datos inválidos para registrar flujo de caja.', [
                    'collateral_id' => $this->collateral_id,
                    'amount_paid' => $this->amount_paid
                ]);
                return null;
            }
    
            $collateral = Collateral::find($this->collateral_id);
            if (!$collateral) {
                session()->flash('error', 'La garamtia no existe.');
                return null;
            }
    
            $this->bank_id = $this->bank_id ?? 1;
            $accountLetter = AccountLetters::find($this->bank_id);
            if (!$accountLetter) {
                session()->flash('error', 'La cuenta bancaria seleccionada no existe.');
                return null;
            }
    
            // Crear flujo de caja y obtener el ID
            $cashFlow = CashFlow::create([
                'user_id' => Auth::id(),
                'transaction_type_income_expense' => 'income',
                'account_bank_id' => $accountLetter->id,
                'amount' => $this->amount_paid,
                'detail' => "Pago de Cuota: {$accountLetter->currency_type} {$this->amount_paid}, Movil: " . optional($collateral)->vehicle_id,
                'type_transaction' => 'payment of guarantee instalments',
                'registration_date' => Carbon::now(),
                'vehicle_id' => $collateral->vehicle_id,
            ]);
    
            // Actualizar saldo de la cuenta bancaria
            $this->updateAccountBalance($this->amount_paid, $accountLetter->id);
    
            return $cashFlow->id;
        } catch (\Exception $e) {
            Log::error('Error al registrar el flujo de caja: ' . $e->getMessage());
            session()->flash('error', 'Ocurrió un error al registrar el flujo de caja.');
            return null;
        }
    }
    
    /**
     * Actualiza el saldo de la cuenta bancaria
     */
    private function updateAccountBalance($amountFinal, $accountLetterId)
    {
        try {
            if (!$accountLetterId || !is_numeric($amountFinal)) {
                Log::warning('Intento de actualizar saldo con datos inválidos.', [
                    'accountLetterId' => $accountLetterId,
                    'amountFinal' => $amountFinal
                ]);
                return;
            }
    
            $accountLetter = AccountLetters::find($accountLetterId);
            if (!$accountLetter) {
                Log::error("Cuenta bancaria con ID {$accountLetterId} no encontrada.");
                return;
            }
    
            $newBalance = max(0, $accountLetter->initial_account_amount + $amountFinal);
            $accountLetter->update(['initial_account_amount' => $newBalance]);
    
            Log::info("Saldo actualizado en cuenta bancaria ID {$accountLetterId}. Nuevo saldo: {$newBalance}");
        } catch (\Exception $e) {
            Log::error("Error al actualizar saldo de la cuenta bancaria ID {$accountLetterId}: " . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->reset('date', 'amount_paid', 'attachment', 'description', 'bank_id');
    }

}
