<?php

namespace App\Livewire\Loan;

use App\Models\AccountLetters;
use App\Models\CashFlow;
use App\Models\Loans;
use App\Models\paymentPlans;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Termwind\Html\InheritStyles;

class Create extends Component
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

    public function mount()
    {
        $this->accountLetters = AccountLetters::all();
    }

    private function validateItems()
    {
        $this->validate([
            'vehicle_id' => 'required|numeric|min:1',
            'driver_partner' => 'required|string|max:255',
            'driver_partner_name' => 'required|string|max:255',
            'loan_start_date'=> 'required',
            'bank_id' => 'required|numeric|min:0',
            'payment_frequency' => 'required|string|min:0',
            'numberInstalments' => 'required|numeric|min:0',
            'amountLoan' => 'required|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0',
            'total_debt' => 'required|numeric|min:0'
        ]);
    }

    private function checkAccountBalance()
    {
        if ($this->bank_id) {
            $accountLetter = AccountLetters::find($this->bank_id);
            if (!$accountLetter) {
                throw new \Exception('La cuenta bancaria seleccionada no existe.');
            }

            //verifica si el saldo de la cuenta es sufuciente para cumplir con el prestamo
            if ($accountLetter->initial_account_amount < $this->amountLoan) {
                return false; // No hay suficiente saldo
            }
            return true; // Hay suficiente saldo
        }
        return false; // Si no hay dato en la variable bank_id
    }

    public function save()
    {
        $this->validateItems();
        // Verifica si el vehiculo existe
        if (Vehicle::find($this->vehicle_id)) {
            if ($this->checkAccountBalance()) {
                DB::beginTransaction();
                try {
                    $this->processCreateItem();
                    $this->updateAccountBalance($this->amountLoan);

                    DB::commit();
                    $this->resetForm();
                    session()->flash('success', 'Datos guardados correctamente.');
                    return redirect()->route('loans.index');
                } catch (\Exception $e) {
                    DB::rollBack();
                    session()->flash('error', 'Error al guardar los datos: ' . $e->getMessage());
                }
            }else{
                session()->flash('error', 'Saldo insuficiente en la cuenta bancaria seleccionada.');
            }
        }else{
            session()->flash('error', 'El vehículo seleccionado no existe. Por favor, verifica e intenta nuevamente.');
        }
    }

    public function saveAndCreateAnother()
    {
        $this->validateItems();
        // Verifica si el vehiculo existe
        if (Vehicle::find($this->vehicle_id)) {
            if ($this->checkAccountBalance()) {
                DB::beginTransaction();
                try {
                    $this->processCreateItem();
                    $this->updateAccountBalance($this->amountLoan);

                    DB::commit();
                    $this->resetForm();
                    session()->flash('success', 'Datos guardados correctamente.');
                } catch (\Exception $e) {
                    DB::rollBack();
                    session()->flash('error', 'Error al guardar los datos: ' . $e->getMessage());
                }
            }else{
                session()->flash('error', 'Saldo insuficiente en la cuenta bancaria seleccionada.');
            }
        }else{
            session()->flash('error', 'El vehículo seleccionado no existe. Por favor, verifica e intenta nuevamente.');
        }
    }

    private function processCreateItem()
    {
        $cashFlows = CashFlow::create([
            'user_id' => Auth::id(),
            'transaction_type_income_expense' => 'expense',
            'account_bank_id' => $this->bank_id,
            'amount' => $this->amountLoan,
            'vehicle_id' => $this->vehicle_id,
            'type_transaction' => 'loan'
        ]);

        $loan = Loans::create([
            'vehicle_id' => $this->vehicle_id,
//        'driver_id',
//        'partner_id',
            'driver_partner_name' => $this->driver_partner_name,
            'loan_start_date' => $this->loan_start_date,
            'numberInstalments' => $this->numberInstalments,
            'debtStatus' => 'not charged',
            'amountLoan' => $this->amountLoan,
            //News columns
            'interest_rate' => $this->interest_rate ?? 0.00,
            'total_debt' => $this->total_debt,
            'user_type' => $this->driver_partner, //TEMPORALMENTE
            'payment_frequency' => $this->payment_frequency,
            'cash_flows_id' => $cashFlows->id,
            'user_id' => Auth::id(),
        ]);


//        for ($i = 0; $i < $this->numberInstalments; $i++) {
//            paymentPlans::create([
//                'instalmentNumber' => $i,
//                'datePayment',
//                'paymentStatus' => 'unpaid',
//                'amount',
//                'loan_id',
//            ]);
//        }
    }

    private function fees($dues, $loan_amount, $amount_receivable)
    {

    }

    private function updateAccountBalance($amountFinal): void
    {
        $accountLetter = AccountLetters::find($this->bank_id);
        if ($accountLetter) {
            $newBalance = $accountLetter->initial_account_amount - $amountFinal;
            $accountLetter->update(['initial_account_amount' => $newBalance]);
        }
    }

    private function resetForm(): void
    {
        $this->reset([
            'vehicle_id',
            'driver_partner',
            'driver_partner_name',
            'bank_id',
            'payment_frequency',
            'numberInstalments',
            'amountLoan',
            'interest_rate',
            'loan_start_date',
            'total_debt'
        ]);
    }

    public function render()
    {
        number_format($this->total_debt = (($this->amountLoan * $this->interest_rate) / 100) + $this->amountLoan, 2);
        return view('livewire.loan.create')->layout('layouts.app');
    }
}
