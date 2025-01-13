<?php

namespace App\Livewire\IncomeCategories;

use App\Models\ItemsCashFlow;
use Livewire\Component;

class Create extends Component
{
    public $name;

    protected array $rules = [
        'name' => 'required|string|max:255',
    ];

    public function render()
    {
        return view(
            'livewire.income-categories.create'
        )->layout('layouts.app');
    }

    public function save()
    {
        $this->validate();
        ItemsCashFlow::create([
            'name' => $this->name,
            'type_income_expense' => 'income',
            'created_by' => auth()->id(),
        ]);
        session()->flash('message', 'Registro guardado con éxito.');
        return redirect()->route('incomeCategories.index');
    }

    public function saveAndCreateAnother()
    {
//        return dd($this);
        $this->validate();
        ItemsCashFlow::create([
            'name' => $this->name,
            'type_income_expense' => 'income',
            'created_by' => auth()->id(),
        ]);
        // Limpia los campos
        $this->reset(['name']);

        session()->flash('message', 'Registro guardado. Puedes agregar otro.');
    }

}
