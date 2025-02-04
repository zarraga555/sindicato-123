<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine si el usuario puede ver la lista de usuarios.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ver usuarios');
    }

    /**
     * Determine si el usuario puede ver un usuario específico.
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasPermissionTo('ver usuarios');
    }

    /**
     * Determine si el usuario puede crear nuevos usuarios.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('crear usuarios');
    }

    /**
     * Determine si el usuario puede actualizar un usuario.
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo('editar usuarios');
    }

    /**
     * Determine si el usuario puede eliminar un usuario.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('eliminar usuarios');
    }
}
