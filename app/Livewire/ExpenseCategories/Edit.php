<?php

namespace App\Livewire\ExpenseCategories;

use App\Models\ItemsCashFlow;
use Livewire\Component;

class Edit extends Component
{
    public $itemExpenseId;
    public $name;
    public $confirmingUserDeletion = false;

    protected array $rules = [
        'name' => 'required|string|max:255',
    ];

    /**
     * Montar datos iniciales al cargar el componente.
     */
    public function mount($id)
    {
        $itemExpense = ItemsCashFlow::findOrFail($id);

        $this->itemExpenseId = $itemExpense->id;
        $this->name = $itemExpense->name;
    }

    /**
     * Actualizar la categoría de gasto.
     */
    public function update()
    {
        $this->validate();

        try {
            $itemExpense = ItemsCashFlow::findOrFail($this->itemExpenseId);
            $itemExpense->update(['name' => $this->name]);

            session()->flash('message', 'La categoría de gasto se actualizó correctamente.');
            return redirect()->route('expenseCategories.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error al actualizar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Abrir la confirmación para eliminar.
     */
    public function openDelete()
    {
        $this->confirmingUserDeletion = true;
    }

    /**
     * Cerrar la confirmación para eliminar.
     */
    public function closeDelete()
    {
        $this->confirmingUserDeletion = false;
    }

    /**
     * Eliminar la categoría de gasto.
     */
    public function delete()
    {
        try {
            $itemExpense = ItemsCashFlow::findOrFail($this->itemExpenseId);
            $itemExpense->delete();

            $this->closeDelete();
            session()->flash('message', 'La categoría de gasto se eliminó correctamente.');
            return redirect()->route('expenseCategories.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error al eliminar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Renderizar la vista del componente.
     */
    public function render()
    {
        return view('livewire.expense-categories.edit')->layout('layouts.app');
    }
}
