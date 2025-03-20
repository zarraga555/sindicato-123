<?php

namespace App\Livewire\Loan;

use App\Models\AccountLetters;
use App\Models\CashFlow;
use App\Models\Loans;
use App\Models\paymentPlans;
use App\Models\Vehicle;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

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
    public $interest_payment_method;
    public $description;
    public $loan;

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
            'loan_start_date' => 'required',
            'bank_id' => 'required|numeric|min:0',
            'payment_frequency' => 'required|string|min:0',
            'interest_payment_method' => 'required|string|min:0',
            'numberInstalments' => 'required|numeric|min:0',
            'amountLoan' => 'required|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0',
            'total_debt' => 'required|numeric|min:0',
            'description' => 'nullable|string|min:0',
        ]);
    }

    private function checkAccountBalance()
    {
        if ($this->bank_id) {
            $accountLetter = AccountLetters::find($this->bank_id);
            if (!$accountLetter) {
                throw new Exception('La cuenta bancaria seleccionada no existe.');
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
                    return redirect()->route('loans.view', ['id' => $this->loan->id]);
                } catch (Exception $e) {
                    DB::rollBack();
                    session()->flash('error', 'Error al guardar los datos: ' . $e->getMessage());
                }
            } else {
                session()->flash('error', 'Saldo insuficiente en la cuenta bancaria seleccionada.');
            }
        } else {
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
                } catch (Exception $e) {
                    DB::rollBack();
                    session()->flash('error', 'Error al guardar los datos: ' . $e->getMessage());
                }
            } else {
                session()->flash('error', 'Saldo insuficiente en la cuenta bancaria seleccionada.');
            }
        } else {
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

        $this->loan = Loans::create([
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
            'interest_payment_method' => $this->interest_payment_method,
            'description' => $this->description,
        ]);

        if ($this->interest_payment_method == 'together') {
            $payment_date = Carbon::parse($this->loan_start_date); // Asegúrate de que sea un objeto Carbon
            $fees = $this->total_debt / $this->numberInstalments;

            // Verificamos si la frecuencia es semanal o mensual
            if ($this->payment_frequency == 'weekly') {
                for ($i = 1; $i <= $this->numberInstalments; $i++) {
                    // Nos aseguramos de que $i sea un número entero y lo pasamos como argumento
                    $date = $payment_date->copy()->addWeeks((int)$i); // Asegurándonos de pasar un valor entero
                    paymentPlans::create([
                        'instalmentNumber' => $i,
                        'datePayment' => $date,
                        'paymentStatus' => 'unpaid',
                        'amount' => $fees,
                        'loan_id' => $this->loan->id,
                        'user_id' => Auth::id(),
                    ]);
                }
            } elseif ($this->payment_frequency == 'monthly') {
                for ($i = 1; $i <= $this->numberInstalments; $i++) {
                    // Asegurándonos de pasar un valor entero
                    $date = $payment_date->copy()->addMonths((int)$i); // Asegurándonos de pasar un valor entero
                    paymentPlans::create([
                        'instalmentNumber' => $i,
                        'datePayment' => $date,
                        'paymentStatus' => 'unpaid',
                        'amount' => $fees,
                        'loan_id' => $this->loan->id,
                        'user_id' => Auth::id(),
                    ]);
                }
            }
        } elseif ($this->interest_payment_method == 'separate') {
            // Agregar cuota adicional para el interés
            $payment_date = Carbon::parse($this->loan_start_date);
            $fees = $this->amountLoan / $this->numberInstalments;
            $interest_fee = ($this->amountLoan * $this->interest_rate) / 100;

            // Verificamos si la frecuencia es semanal o mensual
            if ($this->payment_frequency == 'weekly') {
                for ($i = 1; $i <= $this->numberInstalments; $i++) {
                    // Asegurándonos de pasar un valor entero
                    $date = $payment_date->copy()->addWeeks((int)$i); // Asegurándonos de pasar un valor entero
                    paymentPlans::create([
                        'instalmentNumber' => $i,
                        'datePayment' => $date,
                        'paymentStatus' => 'unpaid',
                        'amount' => $fees,
                        'loan_id' => $this->loan->id,
                        'user_id' => Auth::id(),
                    ]);
                }

                // Crear una cuota adicional para el interés
                $interest_payment_date = $payment_date->copy()->addWeeks((int)$this->numberInstalments + 1);
                paymentPlans::create([
                    'instalmentNumber' => $this->numberInstalments + 1,
                    'datePayment' => $interest_payment_date,
                    'paymentStatus' => 'unpaid',
                    'amount' => $interest_fee,
                    'loan_id' => $this->loan->id,
                    'user_id' => Auth::id(),
                ]);
            } elseif ($this->payment_frequency == 'monthly') {
                for ($i = 1; $i <= $this->numberInstalments; $i++) {
                    // Asegurándonos de pasar un valor entero
                    $date = $payment_date->copy()->addMonths((int)$i); // Asegurándonos de pasar un valor entero
                    paymentPlans::create([
                        'instalmentNumber' => $i,
                        'datePayment' => $date,
                        'paymentStatus' => 'unpaid',
                        'amount' => $fees,
                        'loan_id' => $this->loan->id,
                        'user_id' => Auth::id(),
                    ]);
                }

                // Crear una cuota adicional para el interés
                $interest_payment_date = $payment_date->copy()->addMonths((int)$this->numberInstalments + 1);
                paymentPlans::create([
                    'instalmentNumber' => $this->numberInstalments + 1,
                    'datePayment' => $interest_payment_date,
                    'paymentStatus' => 'unpaid',
                    'amount' => $interest_fee,
                    'loan_id' => $this->loan->id,
                    'user_id' => Auth::id(),
                ]);
            }
        }
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

    public function render()
    {
        number_format($this->total_debt = (($this->amountLoan * $this->interest_rate) / 100) + $this->amountLoan, 2);
        return view('livewire.loan.create');
    }
}
