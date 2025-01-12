<?php

namespace App\Livewire\AccountLetters;

use App\Models\AccountLetters;
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

    protected array $rules = [
        'account_name' => 'required|string|max:255',
        'bank_name' => 'required|string|max:255',
        'account_number' => 'required|numeric',
        'account_type' => 'required|string',
        'currency_type' => 'required|string',
        'initial_account_amount' => 'nullable|numeric',
    ];

    // Cargar datos existentes al montar el componente
    public function mount($id)
    {
        $accountLetters = AccountLetters::findOrFail($id);

        $this->accountId = $accountLetters->id;
        $this->account_name = $accountLetters->account_name;
        $this->bank_name = $accountLetters->bank_name;
        $this->account_number = $accountLetters->account_number;
        $this->account_type = $accountLetters->account_type;
        $this->currency_type = $accountLetters->currency_type;
        $this->initial_account_amount = $accountLetters->initial_account_amount;
    }

    public function update()
    {
        $this->validate();
        $accountLater = AccountLetters::findOrFail($this->accountId);
        $accountLater->update([
            'account_name' => $this->account_name,
            'bank_name' => $this->bank_name,
            'account_number' => $this->account_number,
            'account_type' => $this->account_type,
            'currency_type' => $this->currency_type,
            'initial_account_amount' => $this->initial_account_amount,
        ]);
        session()->flash('message', 'Cuenta Bancaria actualizado correctamente.');
        return redirect()->route('accountLetters.index');
    }

    public function openDelete()
    {
        $this->confirmingUserDeletion = true;
    }

    public function closeDelete()
    {
        $this->confirmingUserDeletion = false;
    }

    public function delete()
    {
        AccountLetters::findOrFail($this->accountId)->delete();
        $this->closeDelete();
        session()->flash('message', 'Cuenta Bancaria eliminado correctamente.');
        return redirect()->route('accountLetters.index');
    }

    public function render()
    {
        return view('livewire.account-letters.edit')->layout('layouts.app');
    }
}
