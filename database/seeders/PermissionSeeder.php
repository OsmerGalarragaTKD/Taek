<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    private $modules = [
        'atletas' => 'Atletas',
        'pagos' => 'Pagos',
        'eventos' => 'Eventos',
        'sedes' => 'Sedes',
        'cinturones' => 'Cinturones',
        'categorias' => 'Categorías',
    ];

    private $actions = [
        'ver' => 'Ver',
        'crear' => 'Crear',
        'editar' => 'Editar',
        'eliminar' => 'Eliminar'
    ];

    public function run(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Permission::truncate();
        Role::truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Crear permisos para cada módulo
        foreach ($this->modules as $module => $moduleName) {
            foreach ($this->actions as $action => $actionName) {
                Permission::create([
                    'name' => $action . '_' . $module,
                    'guard_name' => 'web'
                ]);
            }
        }

        Permission::create([
            'name' => 'gestionar_permisos',
            'guard_name' => 'web'
        ]);

        Permission::create([
            'name' => 'usuarios_pagos',
            'guard_name' => 'web'
        ]);

        // Crear rol de Super Admin
        $superAdmin = Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'web'
        ]);

        $athlete = Role::create([
            'name' => 'athlete',
            'guard_name' => 'web'
        ]);

        // Asignar todos los permisos al Super Admin
        $superAdmin->givePermissionTo(Permission::all());

        // Asignar rol de Super Admin al usuario admin@admin.com
        $admin = User::where('email', 'admin@admin.com')->first();
        if ($admin) {
            $admin->assignRole($superAdmin);
        }

        // Crear rol de Usuario Básico con permisos limitados
        $basicUser = Role::create([
            'name' => 'Usuario Básico',
            'guard_name' => 'web'
        ]);

        // Asignar permisos básicos de visualización
        $basicUser->givePermissionTo([
            'ver_atletas',
            'ver_eventos',
            'ver_sedes',
            'ver_categorias'
        ]);
    }
}
