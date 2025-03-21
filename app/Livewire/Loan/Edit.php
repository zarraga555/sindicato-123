<?php

namespace App\Livewire\Loan;

use App\Models\AccountLetters;
use App\Models\CashFlow;
use App\Models\Loans;
use App\Models\Vehicle;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\paymentPlans;
use Livewire\Component;

class Edit extends Component
{
    public $accountLetters;
    // Personal Information
    public $vehicle_id;
    public $driver_partner;
    public $driver_partner_name;
    // Loan information
    public $bank_id;
    public $loan_start_date;
    public $payment_frequency;
    public $numberInstalments;
    public $amountLoan;
    public $interest_rate;
    public $total_debt = 0.00;
    public $interest_payment_method;
    public $description;

    public $confirmingUserDeletion = false;
    public $lastAmount;
    public bool $showInputs = true;
    public bool $showButtonDelete = false;
    public $loan_id;
    public $loan;

    // private function validateItems()
    // {
    //     $this->validate([
    //         'vehicle_id' => 'required|numeric|min:1',
    //         'driver_partner' => 'required|string|max:255',
    //         'driver_partner_name' => 'required|string|max:255',
    //         'loan_start_date' => 'required',
    //         'bank_id' => 'required|numeric|min:0',
    //         'payment_frequency' => 'required|string|min:0',
    //         'interest_payment_method' => 'required|string|min:0',
    //         'numberInstalments' => 'required|numeric|min:0',
    //         'amountLoan' => 'required|numeric|min:0',
    //         'interest_rate' => 'nullable|numeric|min:0',
    //         'total_debt' => 'required|numeric|min:0',
    //         'description' => 'nullable|string|min:0',
    //     ]);
    // }

    public function mount($id)
    {
        $this->accountLetters = AccountLetters::all();
        $loan = Loans::findOrFail($id);
        $this->loan = $loan;
        $cashFlow = CashFlow::findOrFail($loan->cash_flows_id);
        $this->vehicle_id = $loan->vehicle_id;
        $this->driver_partner = $loan->user_type;
        $this->driver_partner_name = $loan->driver_partner_name;
        $this->bank_id = $cashFlow->account_bank_id;
        $this->loan_start_date = Carbon::parse($loan->loan_start_date)->format('Y-m-d');
        //        $this->loan_start_date = now()->format('Y-m-d');
        $this->payment_frequency = $loan->payment_frequency;
        $this->numberInstalments = $loan->numberInstalments;
        $this->amountLoan = $loan->amountLoan;
        $this->interest_rate = $loan->interest_rate;
        $this->total_debt = $loan->total_debt;
        $this->interest_payment_method = $loan->interest_payment_method;
        $this->description = $loan->description;

        $this->lastAmount = $loan->amountLoan;
        //$this->showInputs = $loan->installments()->where('paymentStatus', 'paid')->count() > 0;
        $this->showButtonDelete = $loan->installments()->where('paymentStatus', 'paid')->count() > 0;
        $this->loan_id = $id;
        $this->loan = $loan;

        // return dd($this->loan->cashFlows->account_bank_id);
    }

    /**
     * Muestra el modal de confirmación de eliminación.
     */
    public function openDelete()
    {
        $this->confirmingUserDeletion = true;
    }

    /**
     * Cierra el modal de confirmación de eliminación.
     */
    public function closeDelete()
    {
        $this->confirmingUserDeletion = false;
    }

    // private function checkAccountBalance()
    // {
    //     if ($this->bank_id) {
    //         $accountLetter = AccountLetters::find($this->bank_id);
    //         if (!$accountLetter) {
    //             throw new Exception('La cuenta bancaria seleccionada no existe.');
    //         }

    //         //verifica si el saldo de la cuenta es sufuciente para cumplir con el prestamo
    //         if ($accountLetter->initial_account_amount < $this->amountLoan) {
    //             return false; // No hay suficiente saldo
    //         }
    //         return true; // Hay suficiente saldo
    //     }
    //     return false; // Si no hay dato en la variable bank_id
    // }


    public function update()
    {
        // Verifica si el vehiculo existe
        if (Vehicle::find($this->vehicle_id)) {
            DB::beginTransaction();
            try {
                $this->processUpdateLoan();
                DB::commit();
                $this->resetForm();
                session()->flash('success', 'Datos guardados correctamente.');
                return redirect()->route('loans.view', ['id' => $this->loan->id]);
            } catch (Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Error al guardar los datos: ' . $e->getMessage());
            }
        } else {
            session()->flash('error', 'El vehículo seleccionado no existe. Por favor, verifica e intenta nuevamente.');
        }
    }

    public function processUpdateLoan()
    {
        $loan = Loans::find($this->loan_id);
        $loan->update([
            'vehicle_id' => $this->vehicle_id,
            'user_type' => $this->driver_partner,
            'driver_partner_name' => $this->driver_partner_name,
            'description' => $this->description,
        ]);
    }

    // Funcion lo de restructurar el pago de sus cuotas cambiando la cantodad de cuotas
    // Funcion pendiente a terminar
    // public function processUpdateItem()
    // {
    //     $this->validate([
    //         'amount' => 'required|numeric|min:1',
    //         'bank_id' => 'required',
    //         'installments' => 'required|integer|min:1',
    //     ]);

    //     $loan = Loans::find($this->loan_id);

    //     if (!$loan) {
    //         session()->flash('error', 'Préstamo no encontrado.');
    //         return;
    //     }

    //     $paidInstallments = $loan->installments()->where('status', 'paid')->count();

    //     if ($paidInstallments > 0 && $this->installments < $paidInstallments) {
    //         session()->flash('error', 'No puedes reducir el número de cuotas por debajo de las ya pagadas.');
    //         return;
    //     }

    //     DB::beginTransaction();
    //     try {
    //         // Actualizar datos del préstamo
    //         $loan->amount = $this->amount;
    //         $loan->bank_id = $this->bank_id;
    //         $loan->installments = $this->installments;
    //         $loan->save();

    //         // Recalcular cuotas
    //         $remainingAmount = $this->amount - $loan->installments()->where('status', 'paid')->sum('amount');
    //         $remainingInstallments = $this->installments - $paidInstallments;

    //         // Borrar cuotas solo si no hay cuotas pagadas
    //         if ($paidInstallments == 0) {
    //             $loan->installments()->delete();
    //         }

    //         $newInstallmentAmount = $remainingAmount / max($remainingInstallments, 1);

    //         for ($i = $paidInstallments + 1; $i <= $this->installments; $i++) {
    //             $loan->installments()->updateOrCreate(
    //                 ['installment_number' => $i],
    //                 ['amount' => round($newInstallmentAmount, 2), 'status' => 'pending']
    //             );
    //         }

    //         DB::commit();
    //         session()->flash('success', 'Préstamo actualizado correctamente.');
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         session()->flash('error', 'Error al actualizar el préstamo: ' . $e->getMessage());
    //     }
    // }

    private function resetForm(): void
    {
        $this->reset([
            'vehicle_id',
            'bank_id',
            'driver_partner',
            'driver_partner_name',
            'loan_start_date',
            'numberInstalments',
            'amountLoan',
            'interest_rate',
            'total_debt',
            'payment_frequency',
            'interest_payment_method',
            'description'
        ]);
    }

    public function updateAccountBalance($accountId)
    {
        $account = AccountLetters::find($accountId);
        if ($account) {
            if ($this->confirmingUserDeletion) {
                $account->increment('initial_account_amount', $this->loan->cashFlows->amount);
            }
        }
    }

    /**
     * Elimina el registro de la base de datos.
     */
    public function delete()
    {
        DB::beginTransaction();
        try {
            if (!$this->loan) {
                throw new \Exception('Loan not found.');
            }
    
            // Capturar el ID antes de eliminar
            $cashFlowsId = $this->loan->cash_flows_id;
            $cashFlowAccountId = $this->loan->cashFlows->account_bank_id;
    
            // Eliminar registros en el orden correcto
            CashFlow::where('id', $cashFlowsId)->delete();
            paymentPlans::where('loan_id', $this->loan_id)->delete();
            Loans::findOrFail($this->loan_id)->delete();
    
            // Llamar a updateAccountBalance después de eliminar
            $this->updateAccountBalance($cashFlowAccountId);
    
            $this->closeDelete();
            DB::commit();
            session()->flash('message', 'Registro eliminado correctamente.');
            return redirect()->route('loans.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        number_format($this->total_debt = (($this->amountLoan * $this->interest_rate) / 100) + $this->amountLoan, 2);
        return view('livewire.loan.edit');
    }
}
