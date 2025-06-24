<x-app-layout>
    <x-slot name="header">Usuarios registrados</x-slot>

    <div class="max-w-7xl mx-auto p-4">
        <table class="w-full table-auto border">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Roles</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-t">
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->getRoleNames()->join(', ') }}</td>
                        <td>
                            <a href="{{ route('admin.usuarios.edit', $user) }}" class="text-blue-600">Editar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
