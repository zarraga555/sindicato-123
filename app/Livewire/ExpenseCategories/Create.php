<?php

namespace App\Livewire\ExpenseCategories;

use App\Models\ItemsCashFlow;
use Livewire\Component;

class Create extends Component
{
    public $name;

    protected array $rules = [
        'name' => 'required|string|max:255',
    ];

    /**
     * Renderizar la vista del componente.
     */
    public function render()
    {
        return view('livewire.expense-categories.create')->layout('layouts.app');
    }

/**
     * Guardar un nuevo registro y redirigir.
     */
    public function save()
    {
        try {
            $this->validate();
            $this->createItemCashFlow();

            session()->flash('message', 'Categoría creada exitosamente.');
            return redirect()->route('expenseCategories.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Guardar un nuevo registro y permitir crear otro.
     */
    public function saveAndCreateAnother()
    {
        try {
            $this->validate();
            $this->createItemCashFlow();

            // Reinicia los campos del formulario.
            $this->reset(['name']);

            session()->flash('message', 'Categoría guardada. Puedes agregar otra.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Lógica compartida para crear un registro en la base de datos.
     */
    private function createItemCashFlow()
    {
        ItemsCashFlow::create([
            'name' => $this->name,
            'type_income_expense' => 'expense',
            'created_by' => auth()->id(),
        ]);
    }
}
