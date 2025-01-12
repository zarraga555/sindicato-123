<?php

namespace App\Livewire\AccountLetters;

use Livewire\Component;

class View extends Component
{
    public function render()
    {
        return view('livewire.account-letters.view')->layout('layouts.app');
    }
}
