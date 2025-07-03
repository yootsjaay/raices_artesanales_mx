@role('admin')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Administración') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-4">Bienvenido al Panel de Administración de Raíces Artesanales MX</h3>
                    <p class="text-lg mb-4">Aquí podrás gestionar el contenido de tu sitio web.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                        {{-- Tarjeta de gestión de Artesanías --}}
                        <a href="{{ route('admin.artesanias.index') }}" class="block p-6 bg-blue-100 rounded-lg shadow-md hover:bg-blue-200 transition-colors duration-200">
                            <h4 class="text-xl font-semibold text-blue-800 mb-2">Gestionar Artesanías</h4>
                            <p class="text-blue-700">Añade, edita o elimina productos artesanales de tu catálogo.</p>
                        </a>

                        {{-- Tarjeta de gestión de Categorías --}}
                        <a href="{{route('admin.categorias.index')}}" class="block p-6 bg-green-100 rounded-lg shadow-md hover:bg-green-200 transition-colors duration-200">
                            <h4 class="text-xl font-semibold text-green-800 mb-2">Gestionar Categorías</h4>
                            <p class="text-green-700">Administra las categorías para organizar tus artesanías.</p>
                        </a>

                        {{-- Tarjeta de gestión de Ubicaciones --}}
                        <a href="{{route('admin.ubicacion.index')}}" class="block p-6 bg-purple-100 rounded-lg shadow-md hover:bg-purple-200 transition-colors duration-200">
                            <h4 class="text-xl font-semibold text-purple-800 mb-2">Gestionar Ubicaciones</h4>
                            <p class="text-purple-700">Define y organiza los lugares de origen de tus artesanías.</p>
                        </a>

                         {{-- Tarjeta de gestión de Usuarios --}}
                        <a href="{{route('admin.usuarios.index')}}" class="block p-6 bg-purple-100 rounded-lg shadow-md hover:bg-purple-200 transition-colors duration-200">
                            <h4 class="text-xl font-semibold text-purple-800 mb-2">Gestionar Usuarios</h4>
                            <p class="text-purple-700">Administra los usuarios para mejor uso del sistema.</p>
                        </a>
                          

                        {{-- Puedes agregar más tarjetas aquí para otras funcionalidades --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@else
    {{-- Opcional: mensaje para quien no tenga permiso --}}
    <div class="p-8 text-red-600 font-bold">
        No tienes permiso para acceder a esta sección.
    </div>
@endrole