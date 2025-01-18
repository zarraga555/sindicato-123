<?php

namespace App\Livewire\Expense;

use App\Models\AccountLetters;
use App\Models\CashFlow;
use App\Models\ItemsCashFlow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public array $cashFlows = [];
    public $itemsCashFlows;
    public $accountLetters;
    public $bank_id;
    public $vehicle_id;

    public function mount()
    {
        $this->itemsCashFlows = ItemsCashFlow::where('type_income_expense', 'expense')->get();
        $this->accountLetters = AccountLetters::all();
        $this->addCashFlow(); // Agrega un flujo inicial vacío
    }

    public function addCashFlow()
    {
        $this->cashFlows[] = [
            'amount' => '',
            'cashFlowId' => '',
        ];
    }

    public function removeCashFlow($index)
    {
        if (isset($this->cashFlows[$index])) {
            unset($this->cashFlows[$index]);
            $this->cashFlows = array_values($this->cashFlows); // Reindexa el array
        }
    }

    private function validateCashFlows()
    {
        $this->validate([
            'cashFlows.*.amount' => 'required|numeric|min:0.01',
            'cashFlows.*.cashFlowId' => 'required|exists:items_cash_flows,id',
            'bank_id' => 'nullable|exists:account_letters,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
        ]);
    }

    // Verifica si el saldo es suficiente antes de registrar los flujos de efectivo
    private function checkAccountBalance()
    {
        if ($this->bank_id) {
            $accountLetter = AccountLetters::find($this->bank_id);

            if (!$accountLetter) {
                throw new \Exception('La cuenta bancaria seleccionada no existe.');
            }

            // Calcula el monto total que se desea gastar
            $totalExpense = array_sum(array_column($this->cashFlows, 'amount'));

            // Verifica si el saldo de la cuenta es suficiente para cubrir los gastos
            if ($accountLetter->initial_account_amount < $totalExpense) {
                return false; // No hay suficiente saldo
            }
        }

        return true; // Hay suficiente saldo
    }

    public function save()
    {
        $this->validateCashFlows();

        DB::beginTransaction();
        try {
            // **Verificar saldo antes de proceder con la creación de flujos**
//            return  dd($this->checkAccountBalance());
            if ($this->checkAccountBalance() == false) {
                // Procesa los flujos de efectivo solo si el saldo es suficiente
                $amountFinal = $this->processCashFlows();

                // Actualiza el saldo de la cuenta bancaria
                $this->updateAccountBalance($amountFinal);

                DB::commit();
                $this->resetForm();
                session()->flash('success', 'Datos guardados correctamente.');
                return redirect()->route('expense.index');
            }
            session()->flash('error', 'Saldo insuficiente en la cuenta bancaria seleccionada.');
//            return; // No se realiza ninguna operación si el saldo es insuficiente

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ocurrió un error al guardar los datos: ' . $e->getMessage());
        }
    }

    private function processCashFlows()
    {
        $amountFinal = 0;

        foreach ($this->cashFlows as $cashFlow) {
            // Solo crea el flujo si hay saldo suficiente
            CashFlow::create([
                'user_id' => Auth::id(),
                'transaction_type_income_expense' => 'expense',
                'account_bank_id' => $this->bank_id ?? 1,
                'amount' => $cashFlow['amount'],
                'items_id' => $cashFlow['cashFlowId'],
                'vehicle_id' => $this->vehicle_id,
            ]);

            $amountFinal += $cashFlow['amount'];
        }

        return $amountFinal;
    }

    private function updateAccountBalance($amountFinal)
    {
        if ($this->bank_id) {
            $accountLetter = AccountLetters::find($this->bank_id);

            if ($accountLetter) {
                $newBalance = $accountLetter->initial_account_amount - $amountFinal;
                $accountLetter->update(['initial_account_amount' => $newBalance]);
            }
        }
    }

    private function resetForm()
    {
        $this->cashFlows = [];
        $this->addCashFlow(); // Reinicia los flujos
        $this->bank_id = null;
        $this->vehicle_id = null;
    }

    public function render()
    {
        return view('livewire.expense.create')->layout('layouts.app');
    }
}
