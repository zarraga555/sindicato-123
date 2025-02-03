<?php

namespace App\Livewire\AccountLetters;

use App\Models\AccountLetters;
use App\Models\CashFlow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public $account_name;
    public $bank_name;
    public $account_number;
    public $account_type;
    public $currency_type;
    public $initial_account_amount;

    private function validateFroms()
    {
        $this->validate([
            'account_name' => 'nullable|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|numeric',
            'account_type' => 'required|string',
            'currency_type' => 'required|string',
            'initial_account_amount' => 'nullable|numeric',
        ]);
    }

    /**
     * Renderiza la vista del componente.
     */
    public function render()
    {
        return view('livewire.account-letters.create')->layout('layouts.app');
    }

    /**
     * Guarda un nuevo registro y redirige al índice.
     */
    public function save()
    {
        $this->validateFroms();

        DB::beginTransaction();
        try {
            $this->createAccountLetter();
            DB::commit();

            session()->flash('message', 'Registro guardado con éxito.');
            return redirect()->route('accountLetters.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al guardar el registro: ' . $e->getMessage());
        }
    }

    /**
     * Guarda un nuevo registro y permite agregar otro.
     */
    public function saveAndCreateAnother()
    {
        $this->validateFroms();

        DB::beginTransaction();
        try {
            $this->createAccountLetter();

            DB::commit();
            // Reinicia los campos del formulario.
            $this->resetForm();

            session()->flash('message', 'Registro guardado. Puedes agregar otro.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al guardar el registro: ' . $e->getMessage());
        }
    }

    /**
     * Lógica compartida para crear un registro en la base de datos.
     */
    private function createAccountLetter()
    {
        $accountLatter = AccountLetters::create([
            'account_name' => $this->account_name,
            'bank_name' => $this->bank_name,
            'account_number' => $this->account_number,
            'account_type' => $this->account_type,
            'currency_type' => $this->currency_type,
            'initial_account_amount' => $this->initial_account_amount,
            'created_by' => auth()->id(),
        ]);
        CashFlow::create([
            'user_id' => Auth::id(),
            'transaction_type_income_expense' => 'income',
            'account_bank_id' => $accountLatter->id,
            'amount' => $this->initial_account_amount,
            'detail' => "Apertura de cuenta:  {$accountLatter->currency_type}. {$this->initial_account_amount} {$accountLatter->bank_name}",
            'type_transaction' => 'initial account amount',
        ]);
    }

    /**
     * Resetea los campos del formulario.
     */
    private function resetForm()
    {
        $this->reset([
            'account_name',
            'bank_name',
            'account_number',
            'account_type',
            'currency_type',
            'initial_account_amount',
        ]);
    }
}
