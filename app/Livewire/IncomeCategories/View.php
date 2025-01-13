<?php

namespace App\Livewire\IncomeCategories;

use Livewire\Component;

class View extends Component
{
    public function render()
    {
        return view(
            'livewire.income-categories.view'
        )->layout('layouts.app');
    }
}
