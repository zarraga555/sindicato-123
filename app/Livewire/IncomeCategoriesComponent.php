<?php

namespace App\Livewire;

use App\Models\ItemsCashFlow;
use Livewire\Component;
use Livewire\WithPagination;

class IncomeCategoriesComponent extends Component
{
    use WithPagination;

    public $search = "";

    public function render()
    {
        $incomeCategories = ItemsCashFlow::where('type_income_expense', 'income') // Filtrar solo por ingresos
        ->where('name', 'like', '%' . $this->search . '%') // Buscar por el término ingresado
        ->paginate(10);
        return view(
            'livewire.income-categories-component',
            compact('incomeCategories')
        )->layout('layouts.app');
    }
}
