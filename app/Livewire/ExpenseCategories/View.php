<?php

namespace App\Livewire\ExpenseCategories;

use Livewire\Component;

class View extends Component
{
    public function render()
    {
        return view(
            'livewire.expense-categories.view'
        )->layout('layouts.app');
    }
}
