<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSecondSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'otros ingresos' => ['crear', 'editar', 'eliminar', 'ver'],
            'items ingreso' => ['crear', 'editar', 'eliminar', 'ver'],
            'item egreso' => ['crear', 'editar', 'eliminar', 'ver'],
            'cuentas bancarias' => ['historial', 'transferencia'],
        ];

        // Crear permisos dinámicamente
        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::updateOrCreate(['name' => "{$action} {$module}"]);
            }
        }

        // buscar roles
        $superAdmin = Role::where('name', 'super-admin')->first();
        $admin = Role::where('name', 'admin')->first();
        $contador = Role::where('name', 'contador')->first();
        $cajero = Role::where('name', 'cajero')->first();
        $auditor = Role::where('name', 'auditor')->first();

        $superAdmin->givePermissionTo([
            'crear otros ingresos', 'editar otros ingresos', 'eliminar otros ingresos', 'ver otros ingresos',
            'crear items ingreso', 'editar items ingreso', 'eliminar items ingreso', 'ver items ingreso',
            'crear items egreso', 'editar items egreso', 'eliminar items egreso', 'ver items egreso',
            'historial cuentas bancarias', 'transferencia cuentas bancarias',
        ]);

        $admin->givePermissionTo([
            'crear otros ingresos', 'editar otros ingresos', 'eliminar otros ingresos', 'ver otros ingresos',
            'crear items ingreso', 'editar items ingreso', 'eliminar items ingreso', 'ver items ingreso',
            'crear items egreso', 'editar items egreso', 'eliminar items egreso', 'ver items egreso',
            'historial cuentas bancarias', 'transferencia cuentas bancarias',
        ]);

        $contador->givePermissionTo([
            'crear otros ingresos', 'editar otros ingresos', 'eliminar otros ingresos', 'ver otros ingresos',
            'crear items ingreso', 'editar items ingreso', 'eliminar items ingreso', 'ver items ingreso',
            'crear items egreso', 'editar items egreso', 'eliminar items egreso', 'ver items egreso',
            'historial cuentas bancarias', 'transferencia cuentas bancarias',
        ]);

        $cajero->givePermissionTo([
            'crear otros ingresos', 'editar otros ingresos', 'eliminar otros ingresos', 'ver otros ingresos',
        ]);

    }
}
