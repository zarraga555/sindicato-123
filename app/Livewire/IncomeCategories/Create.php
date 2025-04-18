<?php

namespace App\Livewire\IncomeCategories;

use App\Models\ItemsCashFlow;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public string $name;
    public float $amount;
    public bool $pending_payment = false;

    protected array $rules = [
        'name' => 'required|string|max:255',
        'amount' => 'nullable|min:0',
        'pending_payment' => 'nullable|boolean',
    ];

    /**
     * Renderiza la vista del componente.
     */
    public function render()
    {
        return view('livewire.income-categories.create');
    }

    /**
     * Guarda un nuevo registro y redirige al índice.
     */
    public function save()
    {
        $this->storeIncomeCategory();

        session()->flash('message', 'Registro guardado con éxito.');
        return redirect()->route('incomeCategories.index');
    }

    /**
     * Guarda un nuevo registro y limpia el formulario para crear otro.
     */
    public function saveAndCreateAnother()
    {
        $this->storeIncomeCategory();

        // Limpia los campos del formulario
        $this->reset(['name']);
        session()->flash('message', 'Registro guardado. Puedes agregar otro.');
    }

    /**
     * Crea un nuevo registro en la base de datos.
     */
    private function storeIncomeCategory()
    {
        $this->validate();

        ItemsCashFlow::create([
            'name' => $this->name,
            'type_income_expense' => 'Income',
            'amount' => $this->amount ?? 0.00,
            'pending_payment' => $this->pending_payment,
            'created_by' => Auth::id(),
        ]);
    }
}
