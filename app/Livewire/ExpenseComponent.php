<?php

namespace App\Livewire;

use App\Models\CashFlow;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseComponent extends Component
{
    use WithPagination;

    public $search = "";

    public function render()
    {
        $expenses = CashFlow::where('transaction_type_income_expense', 'expense')
            ->where(function ($query) {
                $query //->where('name', 'like', '%' . $this->search . '%') // Búsqueda en nombre del gasto
                ->orWhereHas('vehicles', function ($subQuery) { // Relación con Vehicle
                    $subQuery->where('id', 'like', '%' . $this->search . '%');
                })
                    ->orWhereHas('banks', function ($subQuery) { // Relación con AccountBank
                        $subQuery->where('bank_name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('itemsCashFlow', function ($subQuery) { // Relación con Item
                        $subQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view(
            'livewire.expense-component',
            compact('expenses')
        )->layout('layouts.app');
    }
}
