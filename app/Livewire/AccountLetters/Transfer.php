<?php

namespace App\Livewire\AccountLetters;

use App\Models\AccountLetters;
use App\Models\CashFlow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Transfer extends Component
{
    public $transfer_bank_id;
    public $amount;
    public $bank_id;
    public $description;
    public $accountLetters;
    public $accountLatter;
    public $nameLabel;

    public function mount($id)
    {
        $this->transfer_bank_id = $id;
        $this->accountLatter = AccountLetters::findOrFail($this->transfer_bank_id);
        $this->accountLetters = AccountLetters::where('id', '!=', $this->transfer_bank_id)
            ->where('currency_type', $this->accountLatter->currency_type)
            ->get();
        $this->nameLabel = $this->accountLatter->bank_name ." " . $this->accountLatter->currency_type.". ". $this->accountLatter->initial_account_amount; 
    }

    private function validateFroms()
    {
        $this->validate([
            'amount' => 'required|numeric|min:1',
            'bank_id' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);
    }

    private function resetForm()
    {
        $this->reset([
            'amount',
            'bank_id',
            'description'
        ]);
    }

    private function updateAccountBalance()
    {
        if ($this->bank_id === 'External Transfer') {
            $this->accountLatter->decrement('initial_account_amount', $this->amount);
        } elseif ($this->bank_id != 'External Transfer') {
            $accountIncome = AccountLetters::findOrFail($this->bank_id);

            if ($accountIncome) {
                $accountIncome->increment('initial_account_amount', $this->amount);
                $this->accountLatter->decrement('initial_account_amount', $this->amount);
            }
        }
    }

    private function createRecord()
    {
        if ($this->bank_id != null) {
            if ($this->bank_id === 'External Transfer') {
                $bank_name = __('External Transfer');
                $cashFlow = CashFlow::create([
                    'user_id' => Auth::id(),
                    'transaction_type_income_expense' => 'expense',
                    'account_bank_id' => $this->transfer_bank_id,
                    'amount' => $this->amount,
                    'detail' => "Transferia de:  {$this->accountLatter->currency_type}. {$this->amount} de la cuenta: {$this->accountLatter->account_number} del banco: {$this->accountLatter->bank_name} hacia fuera del sistema en una {$bank_name}",
                    'description' => $this->description,
                    'type_transaction' => 'transfer',
                ]);
            } else {
                $bank = AccountLetters::findOrFail($this->bank_id);
                if ($bank) {
                    $cashFlowExpense = CashFlow::create([
                        'user_id' => Auth::id(),
                        'transaction_type_income_expense' => 'expense',
                        'account_bank_id' => $this->transfer_bank_id,
                        'amount' => $this->amount,
                        'detail' => "Transferia de:  {$this->accountLatter->currency_type}. {$this->amount} de la cuenta: {$this->accountLatter->account_number} del banco: {$this->accountLatter->bank_name} hacia la cuenta {$bank->account_number} {$bank->bank_name}",
                        'description' => $this->description,
                        'type_transaction' => 'transfer',
                    ]);
                    $cashFlowIncome = CashFlow::create([
                        'user_id' => Auth::id(),
                        'transaction_type_income_expense' => 'income',
                        'account_bank_id' => $bank->id,
                        'amount' => $this->amount,
                        'detail' => "Transferia recibida de:  {$this->accountLatter->currency_type}. {$this->amount} de la cuenta: {$this->accountLatter->account_number} del banco: {$this->accountLatter->bank_name}",
                        'description' => $this->description,
                        'type_transaction' => 'transfer',
                    ]);
                } else {
                    session()->flash('error', 'La cuenta bancaria no existe ');
                }
            }
        }
    }


    public function update()
    {
        $this->validateFroms();

        DB::beginTransaction();
        try {
            $this->createRecord();
            $this->updateAccountBalance();

            DB::commit();
            $this->resetForm();

            session()->flash('success', 'Datos guardados correctamente.');
            return redirect()->route('accountLetters.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al guardar los datos: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.account-letters.transfer');
    }
}
