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

    /**
     * Carga los datos existentes del modelo al montar el componente.
     */
    public function mount($id)
    {
        $incomeCategory = ItemsCashFlow::findOrFail($id);

        $this->itemIncomeId = $incomeCategory->id;
        $this->name = $incomeCategory->name;
    }

    /**
     * Actualiza el registro en la base de datos.
     */
    public function update()
    {
        $this->validate();

        $incomeCategory = ItemsCashFlow::findOrFail($this->itemIncomeId);
        $incomeCategory->update([
            'name' => $this->name,
        ]);

        session()->flash('message', 'Registro actualizado correctamente.');
        return redirect()->route('incomeCategories.index');
    }

    /**
     * Abre la confirmación para eliminar un registro.
     */
    public function openDelete()
    {
        $this->confirmingUserDeletion = true;
    }

    /**
     * Cierra la confirmación para eliminar un registro.
     */
    public function closeDelete()
    {
        $this->confirmingUserDeletion = false;
    }

    /**
     * Elimina el registro de la base de datos.
     */
    public function delete()
    {
        ItemsCashFlow::findOrFail($this->itemIncomeId)->delete();

        $this->closeDelete();

        session()->flash('message', 'Registro eliminado correctamente.');
        return redirect()->route('incomeCategories.index');
    }

    /**
     * Renderiza la vista del componente.
     */
    public function render()
    {
        return view('livewire.income-categories.edit')->layout('layouts.app');
    }
}
