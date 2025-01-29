<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public $name, $email, $password, $password_confirmation, $role;
    public $confirmingUserDeletion = false;
    public $user_id;
    public $nameLabel;

    private function validateForm()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user_id) // Ignora el email del usuario actual
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // Permite que la contraseña sea opcional en la actualización
        ]);
    }

    public function mount($id)
    {
        $user = User::findOrFail($id);

        $this->nameLabel = $user->name;
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
    }

    /**
     * Actualiza el registro en la base de datos.
     */
    public function update()
    {
        $this->validateForm();

        $user = User::findOrFail($this->user_id);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password)
        ]);

        session()->flash('message', 'Registro actualizado correctamente.');
        return redirect()->route('user.index');
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
        User::findOrFail($this->user_id)->delete();

        $this->closeDelete();

        session()->flash('message', 'Registro eliminado correctamente.');
        return redirect()->route('user.index');
    }

    public function render()
    {
        return view('livewire.user.edit');
    }
}
