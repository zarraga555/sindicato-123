<?php

namespace App\Livewire\Income;

use Livewire\Component;

class View extends Component
{
    public function render()
    {
        return view('livewire.income.view')->layout('layouts.app');
    }
}
