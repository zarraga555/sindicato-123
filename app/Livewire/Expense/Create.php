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
        // Inicializamos las opciones del select
        $this->itemsCashFlows = ItemsCashFlow::where('type_income_expense', 'expense')->get();
        $this->accountLetters = AccountLetters::all();

        // Agregamos un conjunto vacío por defecto
        $this->addCashFlow();
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
        unset($this->cashFlows[$index]);
        $this->cashFlows = array_values($this->cashFlows);
    }

    private function validateCashFlows()
    {
        $this->validate([
            'cashFlows.*.amount' => 'required|numeric|min:0',
            'cashFlows.*.cashFlowId' => 'required|exists:items_cash_flows,id',
            'bank_id' => 'nullable|exists:account_letters,id',
        ]);
    }

    public function save()
    {
        $this->validateCashFlows();

        DB::beginTransaction();
        try {
            foreach ($this->cashFlows as $cashFlow) {
                CashFlow::create([
                    'user_id' => Auth::id(),
                    'transaction_type_income_expense' => 'expense',
                    'account_bank_id' => $this->bank_id ?? 1,
                    'amount' => $cashFlow['amount'],
                    'items_id' => $cashFlow['cashFlowId'],
                    'vehicle_id' => $this->vehicle_id,
                ]);
            }

            $this->resetForm();
            session()->flash('success', 'Datos guardados correctamente.');
            DB::commit();
            return redirect()->route('expense.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ocurrió un error al guardar los datos: ' . $e->getMessage());
        }
    }

    public function saveAndCreateAnother()
    {
        $this->validateCashFlows();
        $amountFinal = 0;

        DB::beginTransaction();
        try {
            foreach ($this->cashFlows as $cashFlow) {
                $amountFinal += $cashFlow['amount'];
                CashFlow::create([
                    'user_id' => Auth::id(),
                    'transaction_type_income_expense' => 'expense',
                    'account_bank_id' => $this->bank_id ?? 1,
                    'amount' => $cashFlow['amount'],
                    'items_id' => $cashFlow['cashFlowId'],
                    'vehicle_id' => $this->vehicle_id,
                ]);
            }

            // Actualizar el monto inicial de la cuenta bancaria
            if ($this->bank_id) {
                $accountLetter = AccountLetters::find($this->bank_id);
                if ($accountLetter) {
                    $accountLetter->update([
                        'initial_account_amount' => $accountLetter->initial_account_amount - $amountFinal,
                    ]);
                } else {
                    throw new \Exception('La cuenta bancaria seleccionada no existe.');
                }
            }

            $this->resetForm();
            session()->flash('success', 'Datos guardados correctamente.');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ocurrió un error al guardar los datos: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->cashFlows = [];
        $this->addCashFlow();
        $this->bank_id = null;
        $this->vehicle_id = null;
    }

    public function render()
    {
        return view('livewire.expense.create')->layout('layouts.app');
    }
}
