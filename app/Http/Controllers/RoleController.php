<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el término de búsqueda
        $search = $request->query('search');
    
        // Obtener roles y ordenarlos correctamente
        $roles = Role::with('permissions')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->get()
            ->sortBy(function ($role) {
                if ($role->name === 'Admin') {
                    return '0'; // Admin siempre primero
                }
                return strtolower($role->name); // Ordenar alfabéticamente ignorando mayúsculas/minúsculas
            });
    
        return Inertia::render('Dashboard/Roles/Index', [
            'roles' => $roles->values(), // Resetear los índices después de ordenar
            'search' => $search,
        ]);
    }
    

    public function create()
    {
        $permissions = Permission::all(['id', 'name']);
        return Inertia::render('Dashboard/Roles/Create', [
            'availablePermissions' => $permissions
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array|exists:permissions,name',
        ]);

        $role = Role::create(['name' => $data['name']]);
        $role->syncPermissions($data['permissions']);

        return redirect()->route('dashboard.roles')->with('success', 'Rol creado correctamente.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return Inertia::render('Dashboard/Roles/Edit', [
            'role' => $role->load('permissions'),
            'permissions' => $permissions,
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'permissions' => 'array|exists:permissions,name',
        ]);

        $role->syncPermissions($data['permissions']);

        return redirect()->route('dashboard.roles')->with('success', 'Permisos actualizados correctamente.');
    }
}
