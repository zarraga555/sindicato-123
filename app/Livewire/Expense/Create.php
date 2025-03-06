<?php

namespace App\Livewire\Expense;

use App\Models\AccountLetters;
use App\Models\CashDrawer;
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
        $this->itemsCashFlows = ItemsCashFlow::where('type_income_expense', 'expense')->get();
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

    // Verifica si el saldo es suficiente antes de registrar los flujos de efectivo
    private function checkAccountBalance()
    {
        $this->bank_id = $this->bank_id ?? 1;
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
            return true; // Hay suficiente saldo
        }
        return false; // Si no hay dato en la variable bank_id
    }

    /**
     * @throws \Exception
     */
    public function save()
    {
        $this->validateCashFlows();

        // **Verificar saldo antes de proceder con la creación de flujos**
//            return  dd($this->checkAccountBalance());
        if ($this->checkAccountBalance()) {
            DB::beginTransaction();
            try {
                // Obtiene o crea una caja abierta
                $cash_drawer = $this->getOrCreateCashDrawer();
                // Procesa los flujos de efectivo solo si el saldo es suficiente
                $amountFinal = $this->processCashFlows($cash_drawer->id);

                // Actualiza el saldo de la cuenta bancaria
                $this->updateAccountBalance($amountFinal);

                DB::commit();
                $this->resetForm();
                session()->flash('success', 'Datos guardados correctamente.');
                return redirect()->route('expense.index');
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Ocurrió un error al guardar los datos: ' . $e->getMessage());
            }
        } else {
            session()->flash('error', 'Saldo insuficiente en la cuenta bancaria seleccionada.');
        }
    }

    public function saveAndCreateAnother()
    {
        $this->validateCashFlows();

        // **Verificar saldo antes de proceder con la creación de flujos**
//        return  dd($this->checkAccountBalance());
        if ($this->checkAccountBalance()) {
            DB::beginTransaction();
            try {
                // Obtiene o crea una caja abierta
                $cash_drawer = $this->getOrCreateCashDrawer();
                // Procesa los flujos de efectivo solo si el saldo es suficiente
                $amountFinal = $this->processCashFlows($cash_drawer->id);

                // Actualiza el saldo de la cuenta bancaria
                $this->updateAccountBalance($amountFinal);

                DB::commit();
                $this->resetForm();
                session()->flash('success', 'Datos guardados correctamente.');
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Ocurrió un error al guardar los datos: ' . $e->getMessage());
            }
        } else {
            session()->flash('error', 'Saldo insuficiente en la cuenta bancaria seleccionada.');
        }

    }

    private function processCashFlows($cash_drawer_id, $payment_type = null, $payment_status = null)
    {
        $accountLetter = AccountLetters::find($this->bank_id);
        $amountFinal = 0;

        // Lógica para definir el payment_type
        if ($payment_status === 'receivable') {
            $payment_type = null; // Si payment_status es 'receivable', payment_type debe ser null
        } elseif ($payment_type === 'qr') {
            $payment_type = 'qr'; // Si el payment_type es 'qr', se mantiene como 'qr'
        } else {
            $payment_type = 'cash'; // Si no es 'qr', se guarda como 'cash'
        }

        foreach ($this->cashFlows as $cashFlow) {
            $itemCashFlow = ItemsCashFlow::findOrFail($cashFlow['cashFlowId']);
            // Solo crea el flujo si hay saldo suficiente
            CashFlow::create([
                'user_id' => Auth::id(),
                'transaction_type_income_expense' => 'expense',
                'account_bank_id' => $this->bank_id ?? 1,
                'amount' => $cashFlow['amount'],
                'items_id' => $cashFlow['cashFlowId'],
                'vehicle_id' => $this->vehicle_id,
                'type_transaction' => 'expense',
                'roadmap_series' => $cashFlow['serie'],
                'registration_date' => $this->fecha_registro ? Carbon::parse($this->fecha_registro) : Carbon::now(),
                'detail' => "Egreso de dinero: {$accountLetter->currency_type}. {$cashFlow['amount']} de: {$itemCashFlow->name}",
                'payment_type' => $payment_type,
                'payment_status' => $payment_status ?? 'paid',
                'transaction_status' => 'open',
                'cash_drawer_id' => $cash_drawer_id,
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

    private function getOrCreateCashDrawer()
    {
        // Verifica si ya hay una caja abierta para el usuario actual
        $cash_drawer = CashDrawer::where('status', 'open')
            ->where('user_id', Auth::id())
            ->first();

        // Si no hay una caja abierta, crea una nueva
        if (!$cash_drawer) {
            $cash_drawer = CashDrawer::create([
                'user_id' => Auth::id(),
                'status' => 'open',
                'initial_amount' => 0,
                'start_time' => Carbon::now(),
            ]);
        }

        return $cash_drawer;
    }

    public function render()
    {
        return view('livewire.expense.create')->layout('layouts.app');
    }
}
