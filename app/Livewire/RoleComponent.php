<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleComponent extends Component
{
    use WithPagination;

    public $search = "";

    public function render()
    {
        $roles = Role::where('name', 'like', "%{$this->search}%")->paginate();
        $permissions = Permission::all();
        return view('livewire.role-component', compact('roles', 'permissions'));
    }
}
