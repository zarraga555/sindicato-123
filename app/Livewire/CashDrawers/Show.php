<?php

namespace App\Livewire\CashDrawers;

use App\Models\CashDrawer;
use App\Models\CashFlow;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public $cashDrawer;
    public $currency;
    public $totalRegistration;
    public $cashOnHand;
    public $cash_drawer_id;


    public function mount($id)
    {
        $this->cashDrawer = CashDrawer::findOrFail($id);
        $this->currency = 'Bs';
        $this->totalRegistration = $this->cashtotalRegistration();
        $this->cashOnHand = $this->cashOnHandParcial();
    }

    public function render()
    {
        $cashFlows = CashFlow::where('cash_drawer_id', $this->cashDrawer->id);
        $cashRegisters = CashFlow::where('cash_drawer_id', $this->cashDrawer->id)->paginate(25);
        return view('livewire.cash-drawers.show', compact('cashFlows', 'cashRegisters'));
    }

    private function cashOnHandParcial()
    {
        $sum = 0;
    
        // Ejecutar la consulta correctamente con get()
        $items = CashFlow::where('cash_drawer_id', $this->cashDrawer->id)
            ->where('payment_type', 'cash')
            ->where('payment_status', 'paid')
            ->get(); // <- Aquí está la clave
    
        // Iterar sobre la colección
        foreach ($items as $item) {
            if ($item->transaction_type_income_expense == 'income') {
                $sum += $item->amount;
            } else {
                $sum -= $item->amount;
            }
        }
        
        return $sum;
    }

    private function cashtotalRegistration()
    {
        $sum = 0;
    
        // Ejecutar la consulta correctamente con get()
        $items = CashFlow::where('cash_drawer_id', $this->cashDrawer->id)
            ->get(); // <- Aquí está la clave
    
        // Iterar sobre la colección
        foreach ($items as $item) {
            if ($item->transaction_type_income_expense == 'income') {
                $sum += $item->amount;
            } else {
                $sum -= $item->amount;
            }
        }
        
        return $sum;
    }
}
