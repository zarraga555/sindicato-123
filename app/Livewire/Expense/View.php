<?php

namespace App\Livewire\Expense;

use Livewire\Component;

class View extends Component
{
    public function render()
    {
        return view(
            'livewire.expense.view'
        )->layout('layouts.app');
    }
}
