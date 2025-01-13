<?php

namespace App\Livewire;

use App\Models\ItemsCashFlow;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseCategoriesComponent extends Component
{
    use WithPagination;

    public $search = "";

    public function render()
    {
        $expenseCategories = ItemsCashFlow::where('type_income_expense', 'expense') // Filtrar solo por ingresos
        ->where('name', 'like', '%' . $this->search . '%') // Buscar por el término ingresado
        ->paginate(10);
        return view(
            'livewire.expense-categories-component',
            compact('expenseCategories')
        )->layout('layouts.app');
    }
}
