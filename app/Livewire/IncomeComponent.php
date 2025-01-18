<?php

namespace App\Livewire;

use App\Models\CashFlow;
use Livewire\Component;

class IncomeComponent extends Component
{
    public $search = '';

    public function render()
    {
        $incomes = CashFlow::where('transaction_type_income_expense', 'income')
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
            'livewire.income-component',
            compact('incomes')
        )->layout('layouts.app');
    }

}
