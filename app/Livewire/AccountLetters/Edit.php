<?php

namespace App\Livewire\AccountLetters;

use App\Models\AccountLetters;
use App\Models\CashFlow;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public $accountId;
    public $account_name;
    public $bank_name;
    public $account_number;
    public $account_type;
    public $currency_type;
    public $initial_account_amount;
    public $confirmingUserDeletion = false;
    public $verificationTransactions;

    protected array $rules = [
        'account_name' => 'nullable|string|max:255',
        'bank_name' => 'required|string|max:255',
        'account_number' => 'required|numeric',
        'account_type' => 'required|string',
        'currency_type' => 'required|string',
        'initial_account_amount' => 'nullable|numeric',
    ];

    /**
     * Monta los datos existentes para edición.
     */
    public function mount($id)
    {
        $account = AccountLetters::findOrFail($id);

        $this->accountId = $account->id;
        $this->account_name = $account->account_name;
        $this->bank_name = $account->bank_name;
        $this->account_number = $account->account_number;
        $this->account_type = $account->account_type;
        $this->currency_type = $account->currency_type;
        $this->initial_account_amount = $account->initial_account_amount;
        $this->verificationTransactions = $this->verifiedCashFLows();
    }

    /**
     * Verifica si en la Tabla CashFlows si hubo transacciones.
     */
    private function verifiedCashFLows(): bool
    {
        $items = CashFlow::where('account_bank_id', $this->accountId)
            ->where('type_transaction', '!=', 'initial account amount')
            ->get();

        if ($items->isEmpty()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Actualiza el registro en la base de datos.
     */
    public function update()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $account = AccountLetters::findOrFail($this->accountId);
            $account->update($this->getFormData());
            DB::commit();
            session()->flash('message', 'Cuenta bancaria actualizada correctamente.');
            return redirect()->route('accountLetters.index');
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

    /**
     * Elimina el registro de la base de datos.
     */
    public function delete()
    {
        try {
            AccountLetters::findOrFail($this->accountId)->delete();

            $this->closeDelete();
            session()->flash('message', 'Cuenta bancaria eliminada correctamente.');
            return redirect()->route('accountLetters.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene los datos del formulario.
     */
    private function getFormData()
    {
        if ($this->verificationTransactions) {
            return [
                'account_name' => $this->account_name,
                'bank_name' => $this->bank_name,
                'account_number' => $this->account_number,
                'account_type' => $this->account_type,
            ];
        } else {
            return [
                'account_name' => $this->account_name,
                'bank_name' => $this->bank_name,
                'account_number' => $this->account_number,
                'account_type' => $this->account_type,
                'currency_type' => $this->currency_type,
                'initial_account_amount' => $this->initial_account_amount,
            ];
        }
    }

    /**
     * Renderiza la vista del componente.
     */
    public function render()
    {
        return view('livewire.account-letters.edit')->layout('layouts.app');
    }
}
