<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionRoleSeeder extends Seeder
{
    public function run()
    {
        // Limpiar la caché de permisos y roles
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Lista extendida de permisos
        $permissions = [
            // Dashboard
            'view dashboard',
        
            // Gestión de Usuarios
            'view users',
            'create users',
            'edit users',
            'delete users',
            'assign roles to users',
        
            // Gestión de Roles
            'view roles',
            'create roles',
            'edit roles',
        
            // Gestión de Páginas y Templates
            'view pages',
            'create pages',
            'edit pages',
            'delete pages',
            'view templates',
            'create templates',
            'edit templates',
        
            // Configuración del Sitio
            'view settings',
            'edit settings',
        ];
        

        // Crear o actualizar los permisos
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Crear el rol de administrador y asignarle todos los permisos
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($permissions);

        // Crear el rol de usuario con permisos limitados
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->syncPermissions([
            'view dashboard',
            // Puedes agregar más permisos básicos para usuarios regulares si es necesario
        ]);
    }
}
