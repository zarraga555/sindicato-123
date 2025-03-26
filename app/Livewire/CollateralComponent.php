<?php

namespace App\Livewire;

use App\Models\Collateral;
use Livewire\Component;
use Livewire\WithPagination;

class CollateralComponent extends Component
{
    use WithPagination;
    public string $search = '';

    public function render()
    {
        $collaterals = Collateral::where('vehicle_id', 'like', '%' . $this->search . '%')->orderBy('registration_date', 'desc')
        ->paginate(25);
        return view('livewire.collateral-component', compact('collaterals'));
    }
}
