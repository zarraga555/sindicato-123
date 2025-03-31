<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissioFourthSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            // NUEVOS MÓDULOS
            'garantias' => ['crear', 'editar', 'eliminar', 'ver'],
            'cobro cuotas de garantia' => ['cobrar', 'ver'],
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

        // Obtener los permisos recién creados
        $permisosGarantias = Permission::whereIn('name', [
            'crear garantias',
            'editar garantias',
            'eliminar garantias',
            'ver garantias'
        ])->get();

        $permisosCobroGarantias = Permission::whereIn('name', [
            'cobrar cobro cuotas de garantia',
            'ver cobro cuotas de garantia'
        ])->get();

        // Asignar permisos a cada rol
        if ($superAdmin) {
            $superAdmin->givePermissionTo(Permission::all()); // 🔹 Tiene todos los permisos
        }

        if ($admin) {
            $admin->givePermissionTo($permisosGarantias);
            $admin->givePermissionTo($permisosCobroGarantias);
        }

        if ($contador) {
            $contador->givePermissionTo($permisosGarantias);
            $contador->givePermissionTo($permisosCobroGarantias);
        }

        if ($cajero) {
            $cajero->givePermissionTo($permisosCobroGarantias);
        }

        if ($auditor) {
            $auditor->givePermissionTo(['ver garantias', 'ver cobro cuotas de garantia']);
        }
    }
}
