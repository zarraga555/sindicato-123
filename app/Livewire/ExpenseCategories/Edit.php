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

    // Cargar datos existentes al montar el componente
    public function mount($id)
    {
        $accountLetters = ItemsCashFlow::findOrFail($id);

        $this->itemExpenseId = $accountLetters->id;
        $this->name = $accountLetters->name;
    }

    public function update()
    {
        $this->validate();
        $itemExpense = ItemsCashFlow::findOrFail($this->itemExpenseId);
        $itemExpense->update([
            'name' => $this->name,
        ]);
        session()->flash('message', 'Registro actualizado correctamente.');
        return redirect()->route('expenseCategories.index');
    }

    public function openDelete()
    {
        $this->confirmingUserDeletion = true;
    }

    public function closeDelete()
    {
        $this->confirmingUserDeletion = false;
    }

    public function delete()
    {
        ItemsCashFlow::findOrFail($this->itemExpenseId)->delete();
        $this->closeDelete();
        session()->flash('message', 'Registro eliminado correctamente.');
        return redirect()->route('expenseCategories.index');
    }

    public function render()
    {
        return view(
            'livewire.expense-categories.edit'
        )->layout('layouts.app');
    }
}
