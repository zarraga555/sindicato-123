<?php

namespace App\Livewire\Income;

use App\Models\AccountLetters;
use App\Models\CashDrawer;
use App\Models\CashFlow;
use App\Models\Collateral;
use App\Models\ItemsCashFlow;
use App\Models\Loans;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use function PHPUnit\Framework\returnArgument;

class Create extends Component
{
    public $incomeItems;
    public $movil;
    public $hoja_semanal_serie;
    public $amount_hoja_semanal;
    public $hoja_domingo_serie;
    public $amount_hoja_domingo;
    public $multas;
    public $lavado_auto_rojo;
    public $lavado_auto_azul;
    public $aporte_seguro;
    public $fecha_registro;
    //
    public $hasPendingDebts = false;
    public $modalExpense = false;
    // Expense
    public array $cashFlows = [];
    public $itemsCashFlows;

    public function mount()
    {
        $this->incomeItems = ItemsCashFlow::where('type_income_expense', 'income')->get();
        $this->itemsCashFlows = ItemsCashFlow::where('type_income_expense', 'expense')->get();
        $this->addCashFlow(); // Agrega un flujo inicial vacío
    }

    public function checkPendingDebts()
    {
        if ($this->movil) {
            $hasPendingDebts = CashFlow::where('vehicle_id', $this->movil)
                ->where('payment_status', 'receivable')
                ->exists()
                || Collateral::where('vehicle_id', $this->movil)->exists()
                || Loans::where('vehicle_id', $this->movil)->exists();

            if ($hasPendingDebts) {
                session()->flash('error', 'El vehículo ' . $this->movil . ' tiene deudas pendientes.');
                $this->render(); // Esto hará que el componente se vuelva a renderizar.
            }
        }
    }

    private function validateCashFlows()
    {
        $this->validate([
            'movil' => 'required|string|min:1',
            'hoja_semanal_serie' => 'required|string|max:255',
            'amount_hoja_semanal' => 'required|numeric|min:0',
            'hoja_domingo_serie' => 'nullable|string|max:255',
            'amount_hoja_domingo' => 'nullable|numeric|min:0',
            'multas' => 'nullable|numeric|min:0',
            'lavado_auto_rojo' => 'nullable|numeric|min:0',
            'lavado_auto_azul' => 'nullable|numeric|min:0',
            'aporte_seguro' => 'nullable|numeric|min:0',
            'fecha_registro' => 'nullable'
        ]);
    }

    public function save()
    {
        $this->validateCashFlows();

        // Verificar si el vehículo existe
        if (Vehicle::find($this->movil)) {
            DB::beginTransaction();
            try {
                // Obtiene o crea una caja abierta
                $cash_drawer = $this->getOrCreateCashDrawer();
                $totalAmount = $this->processCashFlows($cash_drawer->id);
                $this->updateAccountBalance($totalAmount, 'income');

                DB::commit();
                $this->resetForm();
                session()->flash('success', 'Datos guardados correctamente.');
                return redirect()->route('income.index');
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Error al guardar los datos: ' . $e->getMessage());
            }
        } else {
            session()->flash('error', 'El vehículo seleccionado no existe. Por favor, verifica e intenta nuevamente.');
        }
    }

    public function saveAndCreateAnother(): void
    {
        $this->validateCashFlows();

        // Verificar si el vehículo existe
        if (Vehicle::find($this->movil)) {
            DB::beginTransaction();
            try {
                // Obtiene o crea una caja abierta
                $cash_drawer = $this->getOrCreateCashDrawer();

                // Procesa los flujos de efectivo
                $totalAmount = $this->processCashFlows($cash_drawer->id);
                $this->updateAccountBalance($totalAmount, 'income');

                DB::commit();
                $this->resetForm();
                session()->flash('success', 'Datos guardados correctamente.');
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Error al guardar los datos: ' . $e->getMessage());
            }
        } else {
            session()->flash('error', 'El vehículo seleccionado no existe. Por favor, verifica e intenta nuevamente.');
        }
    }

    public function payWithQRAndCreateAnother()
    {
        $this->validateCashFlows();

        // Verificar si el vehículo existe
        if (Vehicle::find($this->movil)) {
            DB::beginTransaction();
            try {
                // Obtiene o crea una caja abierta
                $cash_drawer = $this->getOrCreateCashDrawer();

                // Procesa los flujos de efectivo
                $this->processCashFlows($cash_drawer->id, 'qr');

                DB::commit();
                $this->resetForm();
                session()->flash('success', 'Datos guardados correctamente.');
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Error al guardar los datos: ' . $e->getMessage());
            }
        } else {
            session()->flash('error', 'El vehículo seleccionado no existe. Por favor, verifica e intenta nuevamente.');
        }
    }

    public function savePendingPaymentAndCreateAnother()
    {
        // Verificar si el vehículo existe
        if (Vehicle::find($this->movil)) {
            DB::beginTransaction();
            try {
                // Obtiene o crea una caja abierta
                $cash_drawer = $this->getOrCreateCashDrawer();

                // Procesa los flujos de efectivo
                $this->processCashFlows($cash_drawer->id, null, 'receivable');

                DB::commit();
                $this->resetForm();
                session()->flash('success', 'Datos guardados correctamente.');
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Error al guardar los datos: ' . $e->getMessage());
            }
        } else {
            session()->flash('error', 'El vehículo seleccionado no existe. Por favor, verifica e intenta nuevamente.');
        }
    }

    public function processCashFlows($cash_drawer_id, $payment_type = null, $payment_status = null)
    {
        $totalAmount = 0;

        $items = [
            ['amount' => $this->amount_hoja_semanal, 'item_id' => 8, 'series' => $this->hoja_semanal_serie, 'payment_status' => $payment_status],
            ['amount' => $this->amount_hoja_domingo, 'item_id' => 23, 'series' => $this->hoja_domingo_serie, 'payment_status' => $payment_status],
            ['amount' => $this->multas, 'item_id' => 10, 'payment_status' => $payment_status],
            ['amount' => $this->lavado_auto_rojo, 'item_id' => 25, 'payment_status' => 'receivable'],
            ['amount' => $this->lavado_auto_azul, 'item_id' => 28, 'payment_status' => $payment_status],
            ['amount' => $this->aporte_seguro, 'item_id' => 27, 'payment_status' => $payment_status],
        ];

        foreach ($items as $item) {
            if (!empty($item['amount']) && $item['amount'] > 0) {
                $itemCashFlow = ItemsCashFlow::find($item['item_id']);

                if (!$itemCashFlow) {
                    continue;
                }

                // Lógica nueva para payment_type
                $finalPaymentType = null;
                if ($itemCashFlow->pending_payment) {
                    $finalPaymentType = 'pending payment';
                } elseif ($item['payment_status'] === 'receivable') {
                    $finalPaymentType = null;
                } elseif ($payment_type === 'qr') {
                    $finalPaymentType = 'qr';
                } else {
                    $finalPaymentType = 'cash';
                }

                $this->createCashFlow(
                    $cash_drawer_id,
                    $finalPaymentType,
                    $item['payment_status'],
                    $item['amount'],
                    $item['item_id'],
                    $item['series'] ?? null
                );

                if (!$itemCashFlow->pending_payment) {
                    $totalAmount += $item['amount'];
                }
            }
        }

        return $totalAmount;
    }


    private function createCashFlow($cash_drawer_id, $payment_type, $payment_status, $amount, $itemId, $series = null)
    {
        $accountLetter = AccountLetters::find(1);
        $itemCashFlow = ItemsCashFlow::findOrFail($itemId);

        CashFlow::create([
            'user_id' => Auth::id(),
            'transaction_type_income_expense' => 'income',
            'account_bank_id' => $payment_type === 'qr' ? $this->bank_id ?? 1 : 1,
            'amount' => $amount,
            'roadmap_series' => $series,
            'items_id' => $itemId,
            'vehicle_id' => $this->movil,
            'registration_date' => $this->fecha_registro ? Carbon::parse($this->fecha_registro) : Carbon::now(),
            'detail' => "Ingreso de dinero del movil: {$this->movil} cantidad de: {$accountLetter->currency_type}. {$amount} de: {$itemCashFlow->name}",
            'payment_type' => $payment_type,
            'payment_status' => $payment_status ?? 'paid',
            'transaction_status' => 'open',
            'cash_drawer_id' => $cash_drawer_id,
        ]);
    }

    public function updateAccountBalance($amount, $typeTransaction, $accountLetterId = 1)
    {
        // Obtiene la cuenta bancaria
        $account = AccountLetters::find($accountLetterId);
        if ($account) {
            if ($typeTransaction === 'expense') {
                $account->decrement('initial_account_amount', $amount);
            } elseif ($typeTransaction === 'income') {
                $account->increment('initial_account_amount', $amount);
            }
        } else {
            //Log::info('No se encontro la cuenta bancaria');
            session()->flash('error', 'No se encontro la cuenta bancaria.');
        }
    }

    private function resetForm()
    {
        $this->reset([
            'movil',
            'hoja_semanal_serie',
            'amount_hoja_semanal',
            'hoja_domingo_serie',
            'amount_hoja_domingo',
            'multas',
            'lavado_auto_rojo',
            'lavado_auto_azul',
            'aporte_seguro',
            'fecha_registro',
        ]);
    }

    private function getOrCreateCashDrawer()
    {
        // Verifica si ya hay una caja abierta para el usuario actual
        $cash_drawer = CashDrawer::where('status', 'open')
            ->where('user_id', Auth::id())
            ->first();

        // Si no hay una caja abierta, crea una nueva
        if (!$cash_drawer) {
            $cash_drawer = CashDrawer::create([
                'user_id' => Auth::id(),
                'status' => 'open',
                'initial_amount' => 0,
                'start_time' => Carbon::now(),
            ]);
        }

        return $cash_drawer;
    }

    public function openModalExpense()
    {
        $this->modalExpense = true;
    }

    public function closeModalExpense()
    {
        $this->modalExpense = false;
    }

    public function addCashFlow()
    {
        $this->cashFlows[] = [
            'amount' => '',
            'cashFlowId' => '',
            'serie' => '',
        ];
    }

    public function saveExpense()
    {
        $this->validateItemsExpense();

        DB::beginTransaction();
        try {
            // Obtiene o crea una caja abierta
            $cash_drawer = $this->getOrCreateCashDrawer();
            // Procesa los flujos de efectivo solo si el saldo es suficiente
            $amountFinal = $this->processCashFlowsExpense($cash_drawer->id);

            // Actualiza el saldo de la cuenta bancaria
            $this->updateAccountBalance($amountFinal, 'expense');
            DB::commit();
            $this->resetFormExpense();
            session()->flash('success', 'Gasto cuardado correctamente.');
            $this->closeModalExpense();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ocurrió un error al guardar los datos: ' . $e->getMessage());
        }
    }

    public function validateItemsExpense()
    {
        $this->validate([
            'cashFlows.*.amount' => 'required|numeric|min:0.01',
            'cashFlows.*.cashFlowId' => 'required|exists:items_cash_flows,id',
            'cashFlows.*.serie' => 'nullable|string',
        ]);
    }

    public function removeCashFlow($index)
    {
        if (isset($this->cashFlows[$index])) {
            unset($this->cashFlows[$index]);
            $this->cashFlows = array_values($this->cashFlows); // Reindexa el array
        }
    }

    private function resetFormExpense()
    {
        $this->cashFlows = [];
        $this->addCashFlow(); // Reinicia los flujos
    }

    private function processCashFlowsExpense($cash_drawer_id = null, $payment_type = null, $payment_status = null)
    {
        $accountLetter = AccountLetters::find(1);
        $amountFinal = 0;

        // Lógica para definir el payment_type
        if ($payment_status === 'receivable') {
            $payment_type = null; // Si payment_status es 'receivable', payment_type debe ser null
        } elseif ($payment_type === 'qr') {
            $payment_type = 'qr'; // Si el payment_type es 'qr', se mantiene como 'qr'
        } else {
            $payment_type = 'cash'; // Si no es 'qr', se guarda como 'cash'
        }

        foreach ($this->cashFlows as $cashFlow) {
            $itemCashFlow = ItemsCashFlow::findOrFail($cashFlow['cashFlowId']);
            // Solo crea el flujo si hay saldo suficiente
            CashFlow::create([
                'user_id' => Auth::id(),
                'transaction_type_income_expense' => 'expense',
                'account_bank_id' => $this->bank_id ?? 1,
                'amount' => $cashFlow['amount'],
                'items_id' => $cashFlow['cashFlowId'],
                'vehicle_id' => null,
                'type_transaction' => 'expense',
                'roadmap_series' => $cashFlow['serie'],
                'registration_date' => $this->fecha_registro ? Carbon::parse($this->fecha_registro) : Carbon::now(),
                'detail' => "Egreso de dinero: {$accountLetter->currency_type}. {$cashFlow['amount']} de: {$itemCashFlow->name}",
                'payment_type' => $payment_type,
                'payment_status' => $payment_status ?? 'paid',
                'transaction_status' => 'open',
                'cash_drawer_id' => $cash_drawer_id,
            ]);

            $amountFinal += $cashFlow['amount'];
        }

        return $amountFinal;
    }

    public function render()
    {
        return view('livewire.income.create');
    }
}
