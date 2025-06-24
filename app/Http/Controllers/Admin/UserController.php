<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
   // app/Http/Controllers/Admin/UserController.php

public function index()
{
    $users = User::with('roles')->get(); // Here, $users is defined
    return view('admin.usuarios.index', compact('users')); // Here, $users is passed to the view
}

    public function edit(User $user)
    {
    $users = User::with('roles')->get(); // This line should define $users
        $permissions = Permission::all();
        
        return view('admin.usuarios.edit', compact('users', 'roles', 'permissions'));
    }

    public function update(Request $request, User $user)
    {
        $user->syncRoles($request->roles ?? []);
        $user->syncPermissions($request->permissions ?? []);
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }
}
