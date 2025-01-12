<?php

namespace App\Livewire\AccountLetters;

use App\Models\AccountLetters;
use Illuminate\Container\Attributes\Auth;
use Livewire\Component;

class Create extends Component
{
    public $account_name;
    public $bank_name;
    public $account_number;
    public $account_type;
    public $currency_type;
    public $initial_account_amount;

    protected array $rules = [
        'account_name' => 'required|string|max:255',
        'bank_name' => 'required|string|max:255',
        'account_number' => 'required|numeric',
        'account_type' => 'required|string',
        'currency_type' => 'required|string',
        'initial_account_amount' => 'nullable|numeric',
    ];

    public function render()
    {
        return view('livewire.account-letters.create')->layout('layouts.app');
    }

    public function save()
    {
        $this->validate();
        AccountLetters::create([
            'account_name' => $this->account_name,
            'bank_name' => $this->bank_name,
            'account_number' => $this->account_number,
            'account_type' => $this->account_type,
            'currency_type' => $this->currency_type,
            'initial_account_amount' => $this->initial_account_amount,
            'created_by' => auth()->id(),
        ]);
        session()->flash('message', 'Registro guardado con éxito.');
        return redirect()->route('accountLetters.index');
    }

    public function saveAndCreateAnother()
    {
//        return dd($this);
        $this->validate();
        AccountLetters::create([
            'account_name' => $this->account_name,
            'bank_name' => $this->bank_name,
            'account_number' => $this->account_number,
            'account_type' => $this->account_type,
            'currency_type' => $this->currency_type,
            'initial_account_amount' => $this->initial_account_amount,
            'created_by' => auth()->id(),
        ]);
        // Limpia los campos
        $this->reset(['account_name', 'bank_name', 'account_number', 'account_type', 'currency_type', 'initial_account_amount']);

        session()->flash('message', 'Registro guardado. Puedes agregar otro.');
    }

//    private function data()
//    {
//
//    }
}
