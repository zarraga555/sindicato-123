<?php

namespace App\Livewire\AccountLetters;

use App\Models\AccountLetters;
use App\Models\CashFlow;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    public $nameLabel;
    public $accountLatter;
    public $account_name;
    public $bank_name;
    public $account_number;
    public $account_type;
    public $currency_type;
    public $initial_account_amount;
    public $created_by;


    public function mount($id)
    {
        $this->accountLatter = AccountLetters::findOrFail($id);
        $this->nameLabel = $this->accountLatter->bank_name;
        // Data
        $this->account_name = $this->accountLatter->account_name ?: 'No asignado';
        $this->bank_name = $this->accountLatter->bank_name;
        $this->account_number = $this->accountLatter->account_number;
        $this->account_type = $this->accountLatter->account_type;
        $this->currency_type = $this->accountLatter->currency_type;
        $this->initial_account_amount = $this->accountLatter->initial_account_amount;
        $this->accountLatter->load('users'); // Carga la relación 'users'
        $this->created_by = $this->accountLatter->users ? $this->accountLatter->users->name : 'No asignado';

    }

    public function render()
    {
        $transactions = CashFlow::where('account_bank_id', $this->accountLatter->id)->paginate(15);
        return view('livewire.account-letters.history', compact('transactions'));
    }
}
