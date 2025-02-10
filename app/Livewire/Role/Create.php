<?php

namespace App\Livewire\Role;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Create extends Component
{
    public $name; // Nombre del rol
    public $selectedPermissions = []; // Permisos seleccionados


    // Método común para la creación de roles y asignación de permisos
    private function createRole()
    {
        // Validar los datos
        $this->validate([
            'name' => 'required|string|max:255|unique:roles,name', // Validación para el nombre del rol
            'selectedPermissions' => 'nullable|array', // Asegurar que selectedPermissions sea un arreglo
            'selectedPermissions.*' => 'exists:permissions,id', // Asegurarse de que cada permiso existe en la tabla 'permissions'
        ]);

        // Iniciar una transacción para la base de datos
        DB::beginTransaction();

        try {
            // Crear el rol
            $role = Role::create([
                'name' => $this->name,
                'guard_name' => 'web', // Usando el guard correcto
            ]);

            // Asignar los permisos seleccionados al rol
            if (count($this->selectedPermissions)) {
                $role->permissions()->sync($this->selectedPermissions);
            }

            // Confirmar la transacción
            DB::commit();

            // Limpiar el formulario
            $this->reset();

            return $role;

        } catch (Exception $e) {
            // Si ocurre un error, revertir la transacción
            DB::rollBack();

            // Registrar el error para depuración
            Log::error('Error al crear rol: ' . $e->getMessage());

            // Lanzar la excepción para que se maneje en el controlador
            throw $e;
        }
    }

    // Método para crear el rol y redirigir
    public function save()
    {
        try {
            $this->createRole();

            // Mensaje de éxito
            session()->flash('message', 'Rol creado y permisos asignados correctamente.');

            // Redirigir a la lista de roles
            return redirect()->route('role.index');

        } catch (Exception $e) {
            // Mostrar un mensaje de error para el usuario
            session()->flash('error', 'Hubo un problema al crear el rol: ' . $e->getMessage());
        }
    }

    // Método para crear el rol y seguir creando otro
    public function saveAndCreateAnother()
    {
        try {
            $this->createRole();

            // Mensaje de éxito
            session()->flash('success', 'Rol creado y permisos asignados correctamente.');

            // No redirigir, solo limpiar el formulario
        } catch (Exception $e) {
            // Mostrar un mensaje de error para el usuario
            session()->flash('error', 'Hubo un problema al crear el rol: ' . $e->getMessage());
        }
    }


    public function render()
    {
        // Obtener todos los permisos
        $permissions = Permission::all();

        // Agrupar los permisos por módulo
        $modules = $permissions->groupBy(function ($permission) {
            // Extraer el nombre del módulo (por ejemplo, "Usuarios" de "crear usuarios")
            $parts = explode(' ', $permission->name);

            // Extraer todo después de la primera palabra (el verbo)
            array_shift($parts); // Eliminar el verbo (la primera palabra)

            // Usar el resto de las palabras como el nombre del módulo
            return ucfirst(implode(' ', $parts));  // Unir el resto y capitalizar
        });

        return view('livewire.role.create', compact('modules'));
    }
}
