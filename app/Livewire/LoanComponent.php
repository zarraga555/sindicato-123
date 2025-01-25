<?php

namespace App\Livewire;

use App\Models\Loans;
use Livewire\Component;
use Livewire\WithPagination;

class LoanComponent extends Component
{
    use WithPagination;

    public string $search = '';

    public function render()
    {
        $loans = Loans::where('vehicle_id', 'like', '%' . $this->search . '%')->orderBy('created_at', 'desc')
            ->paginate(10);
        return view(
            'livewire.loan-component',
            compact('loans')
        )->layout('layouts.app');
    }
}
