<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // Muestra el listado de usuarios
    public function index()
    {
        return Inertia::render('Dashboard/Users/Index', [
            'users' => User::with('roles')->get(),
        ]);
    }

    // Muestra el formulario para crear un nuevo usuario
    public function create()
    {
        return Inertia::render('Dashboard/Users/Create', [
            'availableRoles' => Role::select('id', 'name')->get(),
        ]);
    }

    // Almacena un nuevo usuario
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'roles'    => 'nullable|array|exists:roles,id',
        ]);

        DB::transaction(function () use ($data) {
            // Crear el usuario
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // Asignar roles
            if (!empty($data['roles'])) {
                $user->syncRoles($data['roles']);
            }

            // Registrar el log de la acción
            if (class_exists(UserLog::class)) {
                UserLog::create([
                    'user_id'    => auth()->id(),
                    'action'     => 'create_user',
                    'description'=> 'El usuario ' . auth()->user()->name . ' creó al usuario ' . $user->name,
                ]);
            }
        });

        return redirect()->route('dashboard.users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    // Muestra el formulario para editar un usuario
    public function edit(User $user)
    {
        return Inertia::render('Dashboard/Users/Edit', [
            'user' => $user->load('roles'),
            'availableRoles' => Role::select('id', 'name')->get(),
        ]);
    }

    // Actualiza la información del usuario
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:6|confirmed',
            'roles'     => 'nullable|array|exists:roles,id',
            'is_blocked'=> 'required|boolean',
        ]);
    
        return DB::transaction(function () use ($user, $data) {
            // Verificar si el usuario es el único administrador antes de modificar roles
            if ($user->hasRole('admin') && isset($data['roles'])) {
                $adminCount = User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->count();
                if ($adminCount <= 1 && !in_array('admin', $data['roles'])) {
                    return back()->withErrors(['error' => 'No puedes eliminar el único administrador.']);
                }
            }
    
            // Actualizar usuario
            $user->update([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'password'  => !empty($data['password']) ? Hash::make($data['password']) : $user->password,
                'is_blocked'=> $data['is_blocked'],
            ]);
    
            // Actualizar roles
            if (isset($data['roles'])) {
                $user->syncRoles($data['roles']);
            }
    
            // Registrar log de actualización
            if (class_exists(UserLog::class)) {
                UserLog::create([
                    'user_id'    => auth()->id(),
                    'action'     => 'update_user',
                    'description'=> 'El usuario ' . auth()->user()->name . ' actualizó a ' . $user->name,
                ]);
            }
    
            return redirect()->route('dashboard.users.index')
                ->with('success', 'Usuario actualizado correctamente.');
        });
    }
    
    

    // Elimina un usuario
    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {
            // Verificar si el usuario es el único administrador
            if ($user->hasRole('admin')) {
                $adminCount = User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->count();
                if ($adminCount <= 1) {
                    abort(403, 'No se puede eliminar el último usuario administrador.');
                }
            }

            $userName = $user->name;
            $user->delete();

            // Registrar el log de eliminación
            if (class_exists(UserLog::class)) {
                UserLog::create([
                    'user_id'     => auth()->id(),
                    'action'      => 'delete_user',
                    'description' => 'El usuario ' . auth()->user()->name . ' eliminó al usuario ' . $userName,
                ]);
            }
        });

        return redirect()->route('dashboard.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }

    // Alternar estado de bloqueo del usuario
    public function toggleBlock(User $user)
    {
        DB::transaction(function () use ($user) {
            $user->is_blocked = !$user->is_blocked;
            $user->save();

            // Registrar en el log la acción
            if (class_exists(UserLog::class)) {
                UserLog::create([
                    'user_id'    => auth()->id(),
                    'action'     => $user->is_blocked ? 'block_user' : 'unblock_user',
                    'description'=> 'El usuario ' . auth()->user()->name . ' ' . 
                                    ($user->is_blocked ? 'bloqueó' : 'desbloqueó') . 
                                    ' al usuario ' . $user->name,
                ]);
            }
        });

        return redirect()->route('dashboard.users.index')
            ->with('success', 'El estado del usuario ha sido actualizado.');
    }
}
