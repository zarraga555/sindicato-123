<?php

namespace App\Livewire\IncomeCategories;

use App\Models\ItemsCashFlow;
use Livewire\Component;

class Edit extends Component
{
    public $itemIncomeId;
    public $name;
    public $confirmingUserDeletion = false;

    protected array $rules = [
        'name' => 'required|string|max:255',
    ];

    // Cargar datos existentes al montar el componente
    public function mount($id)
    {
        $accountLetters = ItemsCashFlow::findOrFail($id);

        $this->itemIncomeId = $accountLetters->id;
        $this->name = $accountLetters->name;
    }

    public function update()
    {
        $this->validate();
        $itemIncome = ItemsCashFlow::findOrFail($this->itemIncomeId);
        $itemIncome->update([
            'name' => $this->name,
        ]);
        session()->flash('message', 'Registro actualizado correctamente.');
        return redirect()->route('incomeCategories.index');
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
        ItemsCashFlow::findOrFail($this->itemIncomeId)->delete();
        $this->closeDelete();
        session()->flash('message', 'Registro eliminado correctamente.');
        return redirect()->route('incomeCategories.index');
    }

    public function render()
    {
        return view(
            'livewire.income-categories.edit'
        )->layout('layouts.app');
    }
}
