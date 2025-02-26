<?php

namespace App\Livewire\Income;

use App\Models\AccountLetters;
use App\Models\CashDrawer;
use App\Models\CashFlow;
use App\Models\ItemsCashFlow;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use function PHPUnit\Framework\returnArgument;

class Create extends Component
{
    public $itemsCashFlows;
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

    public function mount()
    {
        $this->itemsCashFlows = ItemsCashFlow::where('type_income_expense', 'income')->get();
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
                $totalAmount = $this->processCashFlows();
                $this->updateAccountBalance($totalAmount);

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
                $this->updateAccountBalance($totalAmount);

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
            ['amount' => $this->multas, 'item_id' => 10, 'payment_status' => $payment_status],
            ['amount' => $this->lavado_auto_rojo, 'item_id' => 25, 'payment_status' => 'receivable'],
            ['amount' => $this->lavado_auto_azul, 'item_id' => 28, 'payment_status' => $payment_status],
            ['amount' => $this->aporte_seguro, 'item_id' => 27, 'payment_status' => $payment_status],
            ['amount' => $this->amount_hoja_semanal, 'item_id' => 8, 'series' => $this->hoja_semanal_serie, 'payment_status' => $payment_status],
            ['amount' => $this->amount_hoja_domingo, 'item_id' => 23, 'series' => $this->hoja_domingo_serie, 'payment_status' => $payment_status],
        ];

        foreach ($items as $item) {
            if (!empty($item['amount']) && $item['amount'] > 0) {
                $this->createCashFlow($cash_drawer_id, $payment_type, $item['payment_status'], $item['amount'], $item['item_id'], $item['series']  ?? null);
                $totalAmount += $item['amount'];
            }
        }

        return $totalAmount;
    }

    private function createCashFlow($cash_drawer_id, $payment_type, $payment_status, $amount, $itemId, $series = null,)
    {
        $accountLetter = AccountLetters::find(1);
        $itemCashFlow = ItemsCashFlow::findOrFail($itemId);

        // Lógica para definir el payment_type
        if ($payment_status === 'receivable') {
            $payment_type = null; // Si payment_status es 'receivable', payment_type debe ser null
        } elseif ($payment_type === 'qr') {
            $payment_type = 'qr'; // Si el payment_type es 'qr', se mantiene como 'qr'
        } else {
            $payment_type = 'cash'; // Si no es 'qr', se guarda como 'cash'
        }

        CashFlow::create([
            'user_id' => Auth::id(),
            'transaction_type_income_expense' => 'income',
            'account_bank_id' => $qr ?? 1,
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

    public function updateAccountBalance($amount)
    {
        //    Log::info('Actualizando saldo: ' . $amount);
        $account = AccountLetters::find(1);

        if ($account) {
            $account->increment('initial_account_amount', $amount);
            //        Log::info('Saldo actualizado: ' . $account->initial_account_amount);
        }
        //    else {
        //        Log::warning('Cuenta no encontrada para actualizar saldo.');
        //    }
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

    public function render()
    {
        return view('livewire.income.create');
    }
}
