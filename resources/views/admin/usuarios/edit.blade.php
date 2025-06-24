<x-app-layout>
    <x-slot name="header">Editar Usuario</x-slot>

    <div class="max-w-3xl mx-auto p-4">
<form action="{{ route('admin.usuarios.update', $users->id) }}" method="POST">
            @csrf
             @method('PUT')
            <div class="mb-4">
                <label class="block font-bold mb-1">Roles</label>
                @foreach ($roles as $role)
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                            {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                        <span class="ml-2">{{ $role->name }}</span>
                    </label><br>
                @endforeach
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-1">Permisos</label>
                @foreach ($permissions as $permission)
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                            {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                        <span class="ml-2">{{ $permission->name }}</span>
                    </label><br>
                @endforeach
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
        </form>
    </div>
</x-app-layout>
