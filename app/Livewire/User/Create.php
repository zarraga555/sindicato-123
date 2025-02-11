<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;

class Create extends Component
{
    public $name, $email, $password, $password_confirmation, $role_id, $roles;

    private function validateForm()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required'], // Ajusta los roles según tu sistema
        ]);
    }


    public function save()
    {
        $this->validateForm();
        DB::beginTransaction();
        try {
            $this->createUser();

            DB::commit();
            session()->flash('success', 'Registro exitosamente.');
            return redirect()->route('user.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al guardar los datos: ' . $e->getMessage());
        }
    }

    private function createUser()
    {
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password)
        ]);
        $user->roles()->sync($this->role_id);
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
            'roles',
//            'profile_photo_path'
        ]);
    }

    public function render()
    {
        Gate::authorize('create', User::class);
        $this->roles = Role::all();
        return view('livewire.user.create');
    }
}
