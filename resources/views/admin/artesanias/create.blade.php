<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nueva Artesanía') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.artesanias.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Campo Nombre --}}
                            <div>
                                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre:</label>
                                <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('nombre') border-red-500 @enderror">
                                @error('nombre')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Campo Precio --}}
                            <div>
                                <label for="precio" class="block text-sm font-medium text-gray-700">Precio (MXN):</label>
                                <input type="number" name="precio" id="precio" step="0.01" value="{{ old('precio') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('precio') border-red-500 @enderror">
                                @error('precio')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Campo Stock --}}
                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700">Stock:</label>
                                <input type="number" name="stock" id="stock" value="{{ old('stock') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('stock') border-red-500 @enderror">
                                @error('stock')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Campo Materiales --}}
                            <div>
                                <label for="materiales" class="block text-sm font-medium text-gray-700">Materiales:</label>
                                <input type="text" name="materiales" id="materiales" value="{{ old('materiales') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('materiales') border-red-500 @enderror">
                                @error('materiales')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Campo Dimensiones --}}
                            <div>
                                <label for="dimensiones" class="block text-sm font-medium text-gray-700">Dimensiones:</label>
                                <input type="text" name="dimensiones" id="dimensiones" value="{{ old('dimensiones') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('dimensiones') border-red-500 @enderror">
                                @error('dimensiones')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Campo Técnica Empleada --}}
                            <div>
                                <label for="tecnica_empleada" class="block text-sm font-medium text-gray-700">Técnica Empleada:</label>
                                <input type="text" name="tecnica_empleada" id="tecnica_empleada" value="{{ old('tecnica_empleada') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('tecnica_empleada') border-red-500 @enderror">
                                @error('tecnica_empleada')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Selector de Categoría --}}
                            <div>
                                <label for="categoria_id" class="block text-sm font-medium text-gray-700">Categoría:</label>
                                <select name="categoria_id" id="categoria_id"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('categoria_id') border-red-500 @enderror">
                                    <option value="">Seleccione una categoría</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Selector de Ubicación --}}
                            <div>
                                <label for="ubicacion_id" class="block text-sm font-medium text-gray-700">Ubicación:</label>
                                <select name="ubicacion_id" id="ubicacion_id"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('ubicacion_id') border-red-500 @enderror">
                                    <option value="">Seleccione una ubicación</option>
                                    @foreach ($ubicaciones as $ubicacion)
                                        <option value="{{ $ubicacion->id }}" {{ old('ubicacion_id') == $ubicacion->id ? 'selected' : '' }}>
                                            {{ $ubicacion->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ubicacion_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div> {{-- Fin del grid --}}

                        {{-- Campo Descripción --}}
                        <div class="mt-6">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción:</label>
                            <textarea name="descripcion" id="descripcion" rows="4"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo Historia de la Pieza --}}
                        <div class="mt-6">
                            <label for="historia_pieza" class="block text-sm font-medium text-gray-700">Historia de la Pieza:</label>
                            <textarea name="historia_pieza" id="historia_pieza" rows="4"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('historia_pieza') border-red-500 @enderror">{{ old('historia_pieza') }}</textarea>
                            @error('historia_pieza')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo Imagen Principal --}}
                        <div class="mt-6">
                            <label for="imagen_principal" class="block text-sm font-medium text-gray-700">Imagen Principal:</label>
                            <input type="file" name="imagen_principal" id="imagen_principal" required
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('imagen_principal')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo Imágenes Adicionales (múltiples archivos) --}}
                        <div class="mt-6">
                            <label for="imagen_adicionales" class="block text-sm font-medium text-gray-700">Imágenes Adicionales:</label>
                            <input type="file" name="imagen_adicionales[]" id="imagen_adicionales" multiple
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('imagen_adicionales')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @error('imagen_adicionales.*') {{-- Para errores de cada archivo --}}
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('admin.artesanias.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Guardar Artesanía
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 