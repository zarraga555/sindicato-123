<?php

namespace App\Livewire\OtherIncome;

use App\Models\AccountLetters;
use App\Models\CashFlow;
use App\Models\ItemsCashFlow;
use Carbon\Carbon;
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
    public $fecha_registro;

    public function mount()
    {
        $this->itemsCashFlows = ItemsCashFlow::where('type_income_expense', 'income')->get();
        $this->accountLetters = AccountLetters::all();
        $this->addCashFlow(); // Agrega un flujo inicial vacío
    }

    public function addCashFlow()
    {
        $this->cashFlows[] = [
            'amount' => '',
            'cashFlowId' => '',
            'serie' => '',
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
            'cashFlows.*.serie' => 'nullable|string',
            'bank_id' => 'nullable|exists:account_letters,id',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'fecha_registro' => 'nullable'
        ]);
    }

    public function save()
    {
        $this->validateCashFlows();

        DB::beginTransaction();
        try {
            // Procesa los flujos de efectivo solo si el saldo es suficiente
            $amountFinal = $this->processCashFlows();

            // Actualiza el saldo de la cuenta bancaria
            $this->updateAccountBalance($amountFinal);

            DB::commit();
            $this->resetForm();
            session()->flash('success', 'Datos guardados correctamente.');
            return redirect()->route('otherIncome.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ocurrió un error al guardar los datos: ' . $e->getMessage());
        }
    }

    public
    function saveAndCreateAnother()
    {
        $this->validateCashFlows();

        DB::beginTransaction();
        try {
            // Procesa los flujos de efectivo solo si el saldo es suficiente
            $amountFinal = $this->processCashFlows();

            // Actualiza el saldo de la cuenta bancaria
            $this->updateAccountBalance($amountFinal);

            DB::commit();
            $this->resetForm();
            session()->flash('success', 'Datos guardados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ocurrió un error al guardar los datos: ' . $e->getMessage());
        }


    }

    private
    function processCashFlows()
    {
        $this->bank_id = $this->bank_id ?? 1;
        $accountLetter = AccountLetters::find($this->bank_id);

        $amountFinal = 0;

        foreach ($this->cashFlows as $cashFlow) {
            $itemCashFlow = ItemsCashFlow::findOrFail($cashFlow['cashFlowId']);
            // Solo crea el flujo si hay saldo suficiente
            CashFlow::create([
                'user_id' => Auth::id(),
                'transaction_type_income_expense' => 'income',
                'account_bank_id' => $this->bank_id ?? 1,
                'amount' => $cashFlow['amount'],
                'items_id' => $cashFlow['cashFlowId'],
                'vehicle_id' => $this->vehicle_id,
                'roadmap_series' => $cashFlow['serie'],
                'registration_date' => $this->fecha_registro ? Carbon::parse($this->fecha_registro) : Carbon::now(),
                'detail' => "Ingreso de dinero del movil: {$this->vehicle_id} cantidad de: {$accountLetter->currency_type}. {$cashFlow['amount']} de: {$itemCashFlow->name}",
            ]);

            $amountFinal += $cashFlow['amount'];
        }

        return $amountFinal;
    }

    private
    function updateAccountBalance($amountFinal)
    {
        $this->bank_id = $this->bank_id ?? 1;
        if ($this->bank_id) {
            $accountLetter = AccountLetters::find($this->bank_id);

            $newBalance = $accountLetter->initial_account_amount + $amountFinal;
            $accountLetter->update(['initial_account_amount' => $newBalance]);

        }
    }

    private
    function resetForm()
    {
        $this->cashFlows = [];
        $this->addCashFlow(); // Reinicia los flujos
        $this->bank_id = null;
        $this->vehicle_id = null;
    }

    public
    function render()
    {
        return view('livewire.other-income.create')->layout('layouts.app');
    }
}
