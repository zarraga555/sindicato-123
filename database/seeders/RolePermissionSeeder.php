<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Módulos y permisos
        $modules = [
            'usuarios' => ['crear', 'editar', 'eliminar', 'ver'],
            'roles' => ['crear', 'editar', 'eliminar', 'ver'],
            'prestamos' => ['crear', 'editar', 'eliminar', 'ver'],
            'cuentas bancarias' => ['crear', 'editar', 'eliminar', 'ver'],
            'ingresos' => ['crear', 'editar', 'eliminar', 'ver'],
            'egreso' => ['crear', 'editar', 'eliminar', 'ver'],
            'reportes' => ['ver'],
        ];

        // Crear permisos dinámicamente
        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::updateOrCreate(['name' => "{$action} {$module}"]);
            }
        }

        // Crear roles
        $superAdmin = Role::updateOrCreate(['name' => 'super-admin']);
        $admin = Role::updateOrCreate(['name' => 'admin']);
        $contador = Role::updateOrCreate(['name' => 'contador']);
        $cajero = Role::updateOrCreate(['name' => 'cajero']);
        $auditor = Role::updateOrCreate(['name' => 'auditor']);

        // Asignar permisos a cada rol
        $superAdmin->givePermissionTo(Permission::all());

        $admin->givePermissionTo([
            'crear usuarios', 'editar usuarios', 'eliminar usuarios', 'ver usuarios',
            'crear roles', 'editar roles', 'eliminar roles', 'ver roles',
            'crear prestamos', 'editar prestamos', 'eliminar prestamos', 'ver prestamos',
            'crear cuentas bancarias', 'editar cuentas bancarias', 'eliminar cuentas bancarias', 'ver cuentas bancarias',
            'crear ingresos', 'editar ingresos', 'eliminar ingresos', 'ver ingresos',
            'crear egreso', 'editar egreso', 'eliminar egreso', 'ver egreso',
            'ver reportes'
        ]);

        $contador->givePermissionTo([
            'crear prestamos', 'editar prestamos', 'eliminar prestamos', 'ver prestamos',
            'crear cuentas bancarias', 'editar cuentas bancarias', 'eliminar cuentas bancarias', 'ver cuentas bancarias',
            'crear ingresos', 'editar ingresos', 'eliminar ingresos', 'ver ingresos',
            'crear egreso', 'editar egreso', 'eliminar egreso', 'ver egreso',
            'ver reportes'
        ]);

        $cajero->givePermissionTo([
            'crear ingresos', 'editar ingresos', 'eliminar ingresos', 'ver ingresos',
            'crear egreso', 'editar egreso', 'eliminar egreso', 'ver egreso'
        ]);

        $auditor->givePermissionTo(['ver reportes']);
    }
}
