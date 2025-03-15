<?php

namespace App\Livewire;

use Livewire\WithPagination;
use App\Models\CashDrawer;
use App\Models\CashFlow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CashDrawersComponent extends Component
{
    use WithPagination;

    public $modal_cash_closing = false;
    public $_200, $_100, $_50, $_20, $_10, $_5, $_2, $_1, $_050, $_020, $_010;
    public $final_money = 0;
    public $total_calculated = 0;
    public $sum_ticketing = 0;
    public $cash_drawer_id;
    public $cashOnHand;
    public $sum;

    public function render()
    {
        $this->cashOnHand = $this->cashOnHandParcial();
        $this->sum_ticketing = (($this->_200 == null) ? 0 : $this->_200) * 200 + (($this->_100 == null) ? 0 : $this->_100) * 100 + (($this->_50 == null) ? 0 : $this->_50) * 50 + (($this->_20 == null) ? 0 : $this->_20) * 20 + (($this->_10 == null) ? 0 : $this->_10) * 10 + (($this->_5 == null) ? 0 : $this->_5) * 5 + (($this->_2 == null) ? 0 : $this->_2) * 2 + (($this->_1 == null) ? 0 : $this->_1) * 1 + (($this->_050 == null) ? 0 : $this->_050) * 0.5 + (($this->_020 == null) ? 0 : $this->_020) * 0.2 + (($this->_010 == null) ? 0 : $this->_010) * 0.1;
        $cashRegisters = CashDrawer::orderBy('id', 'desc')->paginate(25);
        return view('livewire.cash-drawers-component', compact('cashRegisters'));
    }

    public function openModalCashClosing($id)
    {
        $this->modal_cash_closing = true;
        $this->resetForm();
        $this->cash_drawer_id = $id;
    }

    public function closeModalCashClosing()
    {
        $this->modal_cash_closing = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            '_200',
            '_100',
            '_50',
            '_20',
            '_10',
            '_5',
            '_2',
            '_1',
            '_050',
            '_020',
            '_010',
        ]);
    }
    public function cashClosing()
    {
        try {
            DB::transaction(function () {
                $denominations = [
                    '_200' => $this->_200,
                    '_100' => $this->_100,
                    '_50'  => $this->_50,
                    '_20'  => $this->_20,
                    '_10'  => $this->_10,
                    '_5'   => $this->_5,
                    '_2'   => $this->_2,
                    '_1'   => $this->_1,
                    '_050' => $this->_050,
                    '_020' => $this->_020,
                    '_010' => $this->_010,
                ];

                // Obtener la caja abierta del usuario
                $cash_drawer = CashDrawer::findOrFail($this->cash_drawer_id);

                if (!$cash_drawer) {
                    throw new \Exception('No se encontró una caja abierta para el usuario.');
                }

                // Procesar transacciones abiertas
                $cash_flows = CashFlow::where('cash_drawer_id', $this->cash_drawer_id)->where('transaction_status', 'parcial')
                    ->where('user_id', Auth::id())
                    ->get();

                // Procesar transacciones abiertas filtradas
                $cash_flowsdos = CashFlow::where('cash_drawer_id', $this->cash_drawer_id)->where('transaction_status', 'parcial')
                    ->where('payment_type', 'cash')->where('payment_status', 'paid')
                    ->where('user_id', Auth::id())
                    ->get();

                if ($cash_flows->isEmpty()) {
                    throw new \Exception('No hay transacciones abiertas para cerrar.');
                }
                if ($cash_flowsdos->isEmpty()) {
                    throw new \Exception('No hay transacciones abiertas para cerrar.');
                }

                foreach ($cash_flows as $account) {
                    $amount = $account->amount;

                    if ($account->transaction_type_income_expense === 'income') {
                        $this->final_money += $amount;
                    } else {
                        $this->final_money -= $amount;
                    }
                    // Cerrar la transacción
                    $account->update(['transaction_status' => 'closed']);
                }

                foreach ($cash_flowsdos as $account) {
                    $amount = $account->amount;

                    if ($account->transaction_type_income_expense === 'income') {
                        $this->total_calculated += $amount;
                    } else {
                        $this->total_calculated -= $amount;
                    }
                    // Cerrar la transacción
                    $account->update(['transaction_status' => 'closed']);
                }

                // Cerrar la caja
                $cash_drawer->update([
                    'end_time' => now(),
                    'final_money' => $this->final_money,
                    'total_declared' => $this->sum_ticketing,
                    'total_calculated' => $this->total_calculated,
                    'difference' => $this->sum_ticketing - $this->total_calculated,
                    'status' => 'closed',
                    'denominations' => json_encode($denominations), // Convertimos a JSON para la DB
                ]);

                // Cerrar modal y mostrar mensaje
                $this->closeModalCashClosing();
                session()->flash('success', 'Cierre de caja guardado correctamente.');
            });
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cerrar la caja: ' . $e->getMessage());
        }
    }

    private function cashOnHandParcial()
    {
        $this->sum = 0;
    
        // Ejecutar la consulta correctamente con get()
        $items = CashFlow::where('cash_drawer_id', $this->cash_drawer_id)
            ->where('transaction_status', 'parcial')
            ->where('payment_type', 'cash')
            ->where('payment_status', 'paid')
            ->get(); // <- Aquí está la clave
    
        // Iterar sobre la colección
        foreach ($items as $item) {
            if ($item->transaction_type_income_expense == 'income') {
                $this->sum += $item->amount;
            } else {
                $this->sum -= $item->amount;
            }
        }
        
        return $this->sum;
    }
}
