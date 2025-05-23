<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Ubicación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- El formulario apunta al método update y usa PUT para la actualización --}}
                    <form action="{{ route('admin.ubicacion.update', $ubicacion->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- O PATCH, ambos funcionan para update --}}

                        <div class="mb-4">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre de la Ubicación:</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $ubicacion->nombre) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('nombre') border-red-500 @enderror">
                            @error('nombre')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- CAMPO TIPO: SELECT con valor seleccionado --}}
                        <div class="mb-4">
                            <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo de Ubicación:</label>
                            <select name="tipo" id="tipo"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('tipo') border-red-500 @enderror">
                                <option value="">Seleccione un tipo</option>
                                {{-- Asegurarse de que la opción actual esté seleccionada --}}
                                <option value="Municipio" {{ old('tipo', $ubicacion->tipo) == 'Municipio' ? 'selected' : '' }}>Municipio</option>
                                <option value="Localidad" {{ old('tipo', $ubicacion->tipo) == 'Localidad' ? 'selected' : '' }}>Localidad</option>
                                <option value="Región" {{ old('tipo', $ubicacion->tipo) == 'Región' ? 'selected' : '' }}>Región</option>
                                {{-- Agrega más opciones si tienes otros tipos definidos --}}
                            </select>
                            @error('tipo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- CAMPO DESCRIPCIÓN --}}
                        <div class="mb-4">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción:</label>
                            <textarea name="descripcion" id="descripcion" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $ubicacion->descripcion) }}</textarea>
                            @error('descripcion')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admin.ubicacion.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Actualizar Ubicación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>