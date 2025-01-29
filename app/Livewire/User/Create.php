<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public $name, $email, $password, $password_confirmation, $role;

    private function validateForm()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            //'role' => ['required', Rule::in(['admin', 'user'])], // Ajusta los roles según tu sistema
        ]);
    }


    public function save()
    {
        $this->validateForm();
        DB::beginTransaction();
        try {
            $this->createUser();

            DB::commit();
            session()->flash('message', 'Registro exitosamente.');
            return redirect()->route('user.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al guardar los datos: ' . $e->getMessage());
        }
    }

    private function createUser()
    {
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password)
        ]);
    }

    public function saveAndCreateAnother()
    {
        $this->validateForm();
        DB::beginTransaction();
        try {
            $this->createUser();

            DB::commit();
            $this->resetForm();
            session()->flash('success', 'Datos guardados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al guardar los datos: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->reset([
            'name',
            'email',
            'password',
            'password_confirmation',
            'role',
//            'profile_photo_path'
        ]);
    }

    public function render()
    {
        return view('livewire.user.create');
    }
}
