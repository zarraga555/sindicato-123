<?php

namespace App\Livewire\Role;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Exception;
use Livewire\Component;

class Edit extends Component
{
    public $idRole;
    public $nameLabel;
    public $name; // Nombre del rol
    public $confirmingUserDeletion = false;
    public $selectedPermissions = []; // Permisos seleccionados

    public function mount($id)
    {
        $role = Role::findOrFail($id);
        $this->idRole = $role->id;
        $this->name = $role->name;
        $this->nameLabel = $role->name;
        foreach ($role->permissions as $permission) {
            $this->selectedPermissions[] = $permission->id;
        }
    }

    /**
     * Actualiza el registro en la base de datos.
     */
    public function update()
    {
        // Validar los datos
        $this->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($this->idRole)], // Ignora el email del usuario actual], // Validación para el nombre del rol
            'selectedPermissions' => 'nullable|array', // Asegurar que selectedPermissions sea un arreglo
            'selectedPermissions.*' => 'exists:permissions,id', // Asegurarse de que cada permiso existe en la tabla 'permissions'
        ]);

        // Iniciar una transacción para la base de datos
        DB::beginTransaction();

        try {
            // actualiza el rol y as
            $role = Role::findOrFail($this->idRole);
            $role->update([
                'name' => $this->name,
            ]);

            // Asignar los permisos seleccionados al rol
            if (count($this->selectedPermissions)) {
                $role->permissions()->sync($this->selectedPermissions);
            }

            // Confirmar la transacción
            DB::commit();

            // Limpiar el formulario
            $this->reset();

            // Mensaje de éxito
            session()->flash('message', 'Rol actualizado y permisos asignados correctamente.');

            // Redirigir a la lista de roles
            return redirect()->route('role.index');

        } catch (Exception $e) {
            // Si ocurre un error, revertir la transacción
            DB::rollBack();

            // Registrar el error para depuración
            Log::error('Error al actualizar el rol: ' . $e->getMessage());

            // Lanzar la excepción para que se maneje en el controlador
            throw $e;
        }
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
        $role = Role::findOrFail($this->idRole);
        foreach ($role->permissions as $permission) {
            $role->revokePermissionTo($permission);
        }

        $role->delete();
        $this->closeDelete();

        session()->flash('message', 'Registro eliminado correctamente.');
        return redirect()->route('role.index');
    }

    public function render()
    {
        // Obtener todos los permisos
        $permissions = Permission::all();

        // Agrupar los permisos por módulo
        $modules = $permissions->groupBy(function ($permission) {
            // Extraer el nombre del módulo (por ejemplo, "Usuarios" de "crear usuarios")
            $parts = explode(' ', $permission->name);
            return ucfirst($parts[1]);  // Usamos la segunda palabra como el módulo
        });

        return view('livewire.role.edit', compact('modules'));
    }
}
