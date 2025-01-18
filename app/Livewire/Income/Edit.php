<?php

namespace App\Livewire\Income;

use App\Models\AccountLetters;
use App\Models\CashFlow;
use App\Models\ItemsCashFlow;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public $cashFlowId;
    public $amount;
    public $itemCashFlowId;
    public $lastAmount;
    public $nameLabel;
    public $confirmingUserDeletion = false;

    protected array $rules = [
        'amount' => 'required|numeric',
//        'itemCashFlowId' => 'required',
    ];

    /**
     * Carga los datos existentes del modelo al montar el componente.
     */
    public function mount($id)
    {
        $income = CashFlow::findOrFail($id);
        $itemCashFlow = ItemsCashFlow::findOrFail($income->items_id);

        $this->nameLabel = $itemCashFlow->name;
        $this->itemCashFlowId = $income->items_id;
        $this->amount = $income->amount;
        $this->lastAmount = $income->amount;
        $this->cashFlowId = $income->id;
    }

    /**
     * Actualiza el registro en la base de datos.
     */
    public function update()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $cashFlow = CashFlow::findOrFail($this->cashFlowId);
            $cashFlow->update($this->getFormData());
            $this->updateAccountBalance();

            DB::commit();
            session()->flash('message', 'Registro actualizado correctamente.');
            return redirect()->route('income.index');
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
        return [
            'amount' => $this->amount,
        ];
    }

    public function updateAccountBalance(): void
    {
        $account = AccountLetters::find(1);

        if ($account) {
            if ($this->confirmingUserDeletion){
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
            session()->flash('message', 'Registro eliminado correctamente.');
            return redirect()->route('income.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.income.edit')->layout('layouts.app');
    }
}
