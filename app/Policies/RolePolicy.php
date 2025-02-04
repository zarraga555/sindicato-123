<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    /**
     * Determine si el usuario puede ver la lista de usuarios.
     */
    public function viewAny(Role $user): bool
    {
        return $user->hasPermissionTo('ver roles');
    }

    /**
     * Determine si el usuario puede ver un usuario específico.
     */
    public function view(Role $user, Role $model): bool
    {
        return $user->hasPermissionTo('ver roles');
    }

    /**
     * Determine si el usuario puede crear nuevos usuarios.
     */
    public function create(Role $user): bool
    {
        return $user->hasPermissionTo('crear roles');
    }

    /**
     * Determine si el usuario puede actualizar un usuario.
     */
    public function update(Role $user, Role $model): bool
    {
        return $user->hasPermissionTo('editar roles');
    }

    /**
     * Determine si el usuario puede eliminar un usuario.
     */
    public function delete(Role $user, Role $model): bool
    {
        return $user->hasPermissionTo('eliminar roles');
    }
}
