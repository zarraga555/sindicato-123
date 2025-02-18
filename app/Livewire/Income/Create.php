<?php

namespace App\Livewire\Income;

use App\Models\AccountLetters;
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
                $totalAmount = $this->processCashFlows();
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

    public function processCashFlows()
    {
        $totalAmount = 0;

        $items = [
            ['amount' => $this->multas, 'item_id' => 10],
            ['amount' => $this->lavado_auto_rojo, 'item_id' => 25],
            ['amount' => $this->lavado_auto_azul, 'item_id' => 28],
            ['amount' => $this->aporte_seguro, 'item_id' => 27],
            ['amount' => $this->amount_hoja_semanal, 'item_id' => 8, 'series' => $this->hoja_semanal_serie],
            ['amount' => $this->amount_hoja_domingo, 'item_id' => 23, 'series' => $this->hoja_domingo_serie],
        ];

        foreach ($items as $item) {
            if (!empty($item['amount']) && $item['amount'] > 0) {
//            Log::info('Creando flujo de efectivo: ', $item);
                $this->createCashFlow($item['amount'], $item['item_id'], $item['series'] ?? null);
                $totalAmount += $item['amount'];
            }
        }

//    Log::info('Total calculado en processCashFlows: ' . $totalAmount);
        return $totalAmount;
    }

    private function createCashFlow($amount, $itemId, $series = null)
    {
        $accountLetter = AccountLetters::find(1);
        $itemCashFlow = ItemsCashFlow::findOrFail($itemId);
        CashFlow::create([
            'user_id' => Auth::id(),
            'transaction_type_income_expense' => 'income',
            'account_bank_id' => 1,
            'amount' => $amount,
            'roadmap_series' => $series,
            'items_id' => $itemId,
            'vehicle_id' => $this->movil,
            'registration_date' => $this->fecha_registro ? Carbon::parse($this->fecha_registro) : Carbon::now(),
            'detail' => "Ingreso de dinero del movil: {$this->movil} cantidad de: {$accountLetter->currency_type}. {$amount} de: {$itemCashFlow->name}",
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

    public function render()
    {
        return view('livewire.income.create')->layout('layouts.app');
    }
}
