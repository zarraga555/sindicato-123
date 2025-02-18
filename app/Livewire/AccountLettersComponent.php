<?php

namespace App\Livewire;

use App\Models\AccountLetters;
use Livewire\Component;
use Livewire\WithPagination;

class AccountLettersComponent extends Component
{
    use WithPagination;

    public string $search = '';

    public function render()
    {
        $accountLetters = AccountLetters::where('bank_name', 'like', '%'.$this->search.'%')
                                            ->orWhere('account_name', 'like', '%'.$this->search.'%')
                                            ->paginate(25);
        return view(
            'livewire.account-letters-component',
            compact('accountLetters')
        )->layout('layouts.app');
    }
}
