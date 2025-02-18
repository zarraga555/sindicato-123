<?php

namespace App\Livewire\OtherIncome;

use App\Models\AccountLetters;
use App\Models\CashFlow;
use App\Models\ItemsCashFlow;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public $cashFlowId;
    public $amount;
    public $account_bank_id;
    public $itemCashFlowId;
    public $lastAmount;
    public $nameLabel;
    public $confirmingUserDeletion = false;
    public $receipt_number;
    public $movil;
    public $bank_id;
    public $mode = "income";
    public $fecha_registro;

    protected array $rules = [
        'amount' => 'required|numeric',
        'itemCashFlowId' => 'required',
        'receipt_number' => 'nullable|numeric',
        'movil' => 'nullable'
    ];

    /**
     * Carga los datos existentes del modelo al montar el componente.
     */
    public function mount($id)
    {
        $otherIncome = CashFlow::findOrFail($id);
        $itemCashFlow = ItemsCashFlow::findOrFail($otherIncome->items_id);

        $this->nameLabel = $itemCashFlow->name;
        $this->lastAmount = $otherIncome->amount;
        $this->cashFlowId = $otherIncome->id;
        $this->account_bank_id = $otherIncome->account_bank_id;

        // Data for the inputs
        $this->itemCashFlowId = $otherIncome->items_id;
        $this->amount = $otherIncome->amount;
//        $this->amount = number_format($otherIncome->amount, 2);
        $this->receipt_number = $otherIncome->roadmap_series;
        $this->movil = $otherIncome->vehicle_id;
        $this->bank_id = $otherIncome->account_bank_id;
        $this->fecha_registro = \Carbon\Carbon::parse($this->fecha_registro)->format('Y-m-d');
    }

    /**
     * Actualiza el registro en la base de datos.
     */
    public function update()
    {
        $this->validate();
        try {
            // Si se ingresó un vehículo, verificar si existe
            if (!empty($this->movil) && !Vehicle::find($this->movil)) {
                session()->flash('error', 'El vehículo seleccionado no existe. Por favor, verifica e intenta nuevamente.');
                return;
            }

            // Si no hay error con el vehículo, continuar con la actualización
            $cashFlow = CashFlow::findOrFail($this->cashFlowId);
            $cashFlow->update($this->getFormData());
            $this->updateAccountBalance();

            DB::commit();
            session()->flash('success', 'Registro actualizado correctamente.');
            return redirect()->route('otherIncome.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el modal de confirmación de eliminación.
     */
    public function openDelete()
    {
        $this->confirmingUserDeletion = true;
    }

    /**
     * Cierra el modal de confirmación de eliminación.
     */
    public function closeDelete()
    {
        $this->confirmingUserDeletion = false;
    }

    private function getFormData(): array
    {
        $accountLetter = AccountLetters::find($this->bank_id);
        $itemCashFlow = ItemsCashFlow::findOrFail($this->itemCashFlowId);
        return [
            'amount' => $this->amount,
            'roadmap_series' => $this->receipt_number,
            'items_id' => $this->itemCashFlowId,
            'vehicle_id' => $this->movil,
            'registration_date' => $this->fecha_registro,
            'detail' => "Ingreso de dinero del movil: {$this->movil} cantidad de: {$accountLetter->currency_type}. {$this->amount} de: {$itemCashFlow->name}",
        ];
    }

    public function updateAccountBalance(): void
    {
        $account = AccountLetters::find($this->account_bank_id);

        if ($account) {
            if ($this->confirmingUserDeletion) {
                $account->decrement('initial_account_amount', $this->lastAmount);
            } else {
                $account->decrement('initial_account_amount', $this->lastAmount);
                $account->increment('initial_account_amount', $this->amount);
            }
        }
    }

    /**
     * Elimina el registro de la base de datos.
     */
    public function delete()
    {
        try {
            CashFlow::findOrFail($this->cashFlowId)->delete();
            $this->updateAccountBalance();

            $this->closeDelete();
            session()->flash('success', 'Registro eliminado correctamente.');
            return redirect()->route('otherIncome.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $items = ItemsCashFlow::where('type_income_expense', 'income')->get();
        return view('livewire.other-income.edit', compact('items'))->layout('layouts.app');
    }
}
