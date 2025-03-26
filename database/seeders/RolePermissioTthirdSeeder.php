<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissioTthirdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            // NUEVOS MÓDULOS
            'arqueos' => ['crear', 'editar', 'eliminar', 'ver'],
            'cuentas por cobrar' => ['crear', 'editar', 'eliminar', 'ver'],
            'caja' => ['crear', 'editar', 'eliminar', 'ver'],
            'configuracion de la empresa' => ['actualizar', 'ver'],
            'configuracion de correo electronico' => ['actualizar', 'ver'],
            'perfil' => ['actualizar', 'ver'],
            'cobro cuotas' => ['crear', 'editar', 'eliminar', 'ver'],
            'cuentas incobrables' => ['marcar', 'ver'],
        ];

        // Crear permisos dinámicamente
        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::updateOrCreate(['name' => "{$action} {$module}"]);
            }
        }

        // Buscar roles
        $superAdmin = Role::where('name', 'super-admin')->first();
        $admin = Role::where('name', 'admin')->first();
        $contador = Role::where('name', 'contador')->first();
        $cajero = Role::where('name', 'cajero')->first();
        $auditor = Role::where('name', 'auditor')->first();

        // Asignar permisos a cada rol
        $superAdmin->givePermissionTo(Permission::all()); // 🔹 Tiene todos los permisos

        $admin->givePermissionTo([
            'crear otros ingresos', 'editar otros ingresos', 'eliminar otros ingresos', 'ver otros ingresos',
            'crear item ingreso', 'editar item ingreso', 'eliminar item ingreso', 'ver item ingreso',
            'crear item egreso', 'editar item egreso', 'eliminar item egreso', 'ver item egreso',
            'historial cuentas bancarias', 'transferencia cuentas bancarias',
            'crear arqueos', 'editar arqueos', 'eliminar arqueos', 'ver arqueos',
            'crear cuentas por cobrar', 'editar cuentas por cobrar', 'eliminar cuentas por cobrar', 'ver cuentas por cobrar',
            'crear caja', 'editar caja', 'eliminar caja', 'ver caja',
            'actualizar configuracion de la empresa', 'ver configuracion de la empresa',
            'actualizar configuracion de correo electronico', 'ver configuracion de correo electronico',
            'actualizar perfil', 'ver perfil',
            'crear cobro cuotas', 'editar cobro cuotas', 'eliminar cobro cuotas', 'ver cobro cuotas',
            'marcar cuentas incobrables', 'ver cuentas incobrables',
        ]);

        $contador->givePermissionTo([
            'crear otros ingresos', 'editar otros ingresos', 'eliminar otros ingresos', 'ver otros ingresos',
            'crear item ingreso', 'editar item ingreso', 'eliminar item ingreso', 'ver item ingreso',
            'crear item egreso', 'editar item egreso', 'eliminar item egreso', 'ver item egreso',
            'historial cuentas bancarias', 'transferencia cuentas bancarias',
            'crear cuentas por cobrar', 'editar cuentas por cobrar', 'eliminar cuentas por cobrar', 'ver cuentas por cobrar',
            'ver caja',
            'crear cobro cuotas', 'editar cobro cuotas', 'eliminar cobro cuotas', 'ver cobro cuotas',
            'marcar cuentas incobrables', 'ver cuentas incobrables',
        ]);

        $cajero->givePermissionTo([
            'crear otros ingresos', 'editar otros ingresos', 'eliminar otros ingresos', 'ver otros ingresos',
            'crear arqueos', 'ver arqueos',
            'crear caja', 'editar caja', 'eliminar caja', 'ver caja',
            'crear cobro cuotas', 'editar cobro cuotas', 'eliminar cobro cuotas', 'ver cobro cuotas',
        ]);

        $auditor->givePermissionTo([
            'ver arqueos',
            'ver cuentas por cobrar',
            'ver caja',
            'ver configuracion de la empresa',
            'ver configuracion de correo electronico',
            'ver perfil',
            'ver cobro cuotas',
            'ver cuentas incobrables',
        ]);
    }
}
