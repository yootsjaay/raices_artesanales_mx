<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule; // Añadir esto para la validación del email

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('admin.usuarios.index', compact('users'));
    }

    public function edit(User $usuario) // $usuario es correcto
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.usuarios.edit', compact('usuario', 'roles', 'permissions'));
    }

    public function update(Request $request, User $usuario) // $usuario es correcto
    {
        // 1. Validación de los datos
        // ES CRUCIAL validar los datos que recibes del formulario
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($usuario->id), // Asegura que el email sea único, excepto para el usuario actual
            ],
            // Puedes añadir validación para roles y permisos si lo necesitas
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,name'], // Valida que los nombres de roles existan
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'], // Valida que los nombres de permisos existan
        ]);

        // 2. Actualizar campos del usuario (nombre y email)
        // Solo actualiza si los campos no están deshabilitados en la vista
        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // 3. Sincronizar roles y permisos (esto ya lo tenías)
        $usuario->syncRoles($request->roles ?? []);
        $usuario->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }
}