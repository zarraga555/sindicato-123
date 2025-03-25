<?php

namespace App\Livewire;

use App\Models\CashFlow;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountsReceivable extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingPayment = false;
    public $cashflow_id;

    public function render()
    {
        $incomes = CashFlow::where('transaction_type_income_expense', 'income') // Filtra los ingresos
            ->where('payment_status', 'receivable') // Filtra por el estado de pago
            ->when($this->search, function ($query) {
                // Aplica el filtro solo cuando $this->search tiene un valor
                return $query->where('vehicle_id', $this->search);
            })
            ->paginate(25); // Paginación con 25 resultados por página

        return view('livewire.accounts-receivable', compact('incomes'));
    }

    // Método para abrir el modal
    public function collectModal($id)
    {
        try {
            // Verificar si el registro existe
            $cashflow = CashFlow::findOrFail($id);
            $this->cashflow_id = $id;
            $this->confirmingPayment = true;
        } catch (\Exception $e) {
            // Si no se encuentra el registro, se lanza una excepción y se maneja el error
            $this->dispatchBrowserEvent('error', ['message' => 'Record not found.']);
            Log::error("Error opening payment modal: " . $e->getMessage());
        }
    }

    // Método para cerrar el modal
    public function closeModal()
    {
        $this->confirmingPayment = false;
    }

    // Método para cobrar en efectivo
    public function chargeCash()
    {
        DB::beginTransaction(); // Inicia la transacción

        try {
            $cashflow = CashFlow::findOrFail($this->cashflow_id);

            // Verifica si el estado ya es "paid"
            if ($cashflow->payment_status === 'paid') {
                session()->flash('error', 'This payment has already been processed.');
                return;
            }

            // Actualiza el estado de pago a "paid" y el tipo de pago a "cash"
            $cashflow->payment_status = 'paid';
            $cashflow->payment_type = 'cash';
            $cashflow->save();

            DB::commit(); // Confirma los cambios realizados en la base de datos

            session()->flash('success', 'Payment successfully processed in cash.');
            $this->closeModal();

            // Actualiza la vista de la tabla (sin recargar toda la página)
            $this->render();
        } catch (\Exception $e) {
            DB::rollBack(); // Revierte los cambios si ocurre un error

            // Manejo de errores si el registro no existe o ocurre algún otro problema
            session()->flash('error', 'An error occurred while processing the payment.');
            Log::error("Error charging payment in cash: " . $e->getMessage());
        }
    }

    // Método para cobrar con QR
    public function chargeQR()
    {
        DB::beginTransaction(); // Inicia la transacción

        try {
            $cashflow = CashFlow::findOrFail($this->cashflow_id);

            // Verifica si el estado ya es "paid"
            if ($cashflow->payment_status === 'paid') {
                session()->flash('error', 'This payment has already been processed.');
                return;
            }

            // Actualiza el estado de pago a "paid" y el tipo de pago a "qr"
            $cashflow->payment_status = 'paid';
            $cashflow->payment_type = 'qr';
            $cashflow->save();

            DB::commit(); // Confirma los cambios realizados en la base de datos

            session()->flash('success', 'Payment successfully processed via QR.');
            $this->closeModal();

            // Actualiza la vista de la tabla (sin recargar toda la página)
            $this->render();
        } catch (\Exception $e) {
            DB::rollBack(); // Revierte los cambios si ocurre un error

            // Manejo de errores si el registro no existe o ocurre algún otro problema
            session()->flash('success', 'An error occurred while processing the payment.');
            Log::error("Error charging payment via QR: " . $e->getMessage());
        }
    }
}
