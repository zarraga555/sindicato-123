<?php

namespace App\Livewire;

use App\Models\CashDrawer;
use App\Models\CashFlow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CashRegisterComponent extends Component
{
    use WithPagination;

    public $totalRegistration;
    public $cashOnHand;
    public $_200, $_100, $_50, $_20, $_10, $_5, $_2, $_1, $_050, $_020, $_010;
    public $sum_ticketing = 0;
    public $modal_cash_closing = false;
    public $modal_partial_closing = false;
    public $final_money = 0;
    public $total_calculated = 0;

    public function render()
    {
        $this->totalRegistration = Auth::user()->totalRegistration();
        $this->cashOnHand = Auth::user()->cashOnHand();
        $this->sum_ticketing = (($this->_200 == null) ? 0 : $this->_200) * 200 + (($this->_100 == null) ? 0 : $this->_100) * 100 + (($this->_50 == null) ? 0 : $this->_50) * 50 + (($this->_20 == null) ? 0 : $this->_20) * 20 + (($this->_10 == null) ? 0 : $this->_10) * 10 + (($this->_5 == null) ? 0 : $this->_5) * 5 + (($this->_2 == null) ? 0 : $this->_2) * 2 + (($this->_1 == null) ? 0 : $this->_1) * 1 + (($this->_050 == null) ? 0 : $this->_050) * 0.5 + (($this->_020 == null) ? 0 : $this->_020) * 0.2 + (($this->_010 == null) ? 0 : $this->_010) * 0.1;

        $cashRegisters = CashFlow::where('transaction_status', 'open')->where('user_id', Auth::id())->paginate(25);
        return view('livewire.cash-register-component', compact('cashRegisters'));
    }

    public function openModalCashClosing()
    {
        $this->modal_cash_closing = true;
        $this->resetForm();
    }

    public function openModalPartialClosing()
    {
        $this->modal_partial_closing = true;
    }

    public function closeModalCashClosing()
    {
        $this->modal_cash_closing = false;
        $this->resetForm();
    }

    public function closeModalPartialClosing()
    {
        $this->modal_partial_closing = false;
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
                $cash_drawer = CashDrawer::where('status', 'open')
                    ->where('user_id', Auth::id())
                    ->first();

                if (!$cash_drawer) {
                    throw new \Exception('No se encontró una caja abierta para el usuario.');
                }

                // Procesar transacciones abiertas
                $cash_flows = CashFlow::where('transaction_status', 'open')
                    ->where('user_id', Auth::id())
                    ->get();

                // Procesar transacciones abiertas filtradas
                $cash_flowsdos = CashFlow::where('transaction_status', 'open')
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

    public function partialClosing()
    {
        try {
            $cash_drawer = CashDrawer::where('status', 'open')
                ->where('user_id', Auth::id())
                ->first();

            if (!$cash_drawer) {
                throw new \Exception('No se encontró una caja abierta para el usuario.');
            }

            // Procesar transacciones abiertas
            $cash_flows = CashFlow::where('transaction_status', 'open')
                ->where('user_id', Auth::id())
                ->get();

            // Procesar transacciones abiertas filtradas
            $cash_flowsdos = CashFlow::where('transaction_status', 'open')
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
                $account->update(['transaction_status' => 'parcial']);
            }

            foreach ($cash_flowsdos as $account) {
                $amount = $account->amount;

                if ($account->transaction_type_income_expense === 'income') {
                    $this->total_calculated += $amount;
                } else {
                    $this->total_calculated -= $amount;
                }
                // Cerrar la transacción
                $account->update(['transaction_status' => 'parcial']);
            }

            // Procesar transacciones abiertas
            $cash_flows = CashFlow::where('transaction_status', 'open')
                ->where('user_id', Auth::id())
                ->get();

            foreach ($cash_flows as $account) {
                $account->update(['transaction_status' => 'parcial']);
            }
            $cash_drawer->update(['status' => 'parcial', 'end_time' => Carbon::now(), 'final_money' => $this->final_money, 'total_calculated' => $this->total_calculated,]);
            // Cerrar modal y mostrar mensaje
            $this->closeModalPartialClosing();
            session()->flash('success', 'Cierre de caja parcial guardado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cerrar la caja: ' . $e->getMessage());
        }
    }
}
