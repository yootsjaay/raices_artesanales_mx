<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nueva Artesanía') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6 sm:p-8">

                <div class="mb-6 border-b pb-4">
                    <h3 class="text-lg font-medium text-gray-900">Detalles de la Artesanía</h3>
                    <p class="mt-1 text-sm text-gray-600">Ingresa la información básica y las dimensiones para el envío.</p>
                </div>

                <form action="{{ route('admin.artesanias.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Sección de Información General --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- Campo Nombre --}}
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre:</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nombre') border-red-500 @enderror" required>
                            @error('nombre')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo Precio --}}
                        <div>
                            <label for="precio" class="block text-sm font-medium text-gray-700">Precio (MXN):</label>
                            <input type="number" name="precio" id="precio" step="0.01" value="{{ old('precio') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('precio') border-red-500 @enderror" required>
                            @error('precio')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo Stock --}}
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700">Stock:</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('stock') border-red-500 @enderror" required>
                            @error('stock')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo Materiales --}}
                        <div>
                            <label for="materiales" class="block text-sm font-medium text-gray-700">Materiales:</label>
                            <input type="text" name="materiales" id="materiales" value="{{ old('materiales') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('materiales') border-red-500 @enderror">
                            @error('materiales')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Selector de Categoría --}}
                        <div>
                            <label for="categoria_id" class="block text-sm font-medium text-gray-700">Categoría:</label>
                            <select name="categoria_id" id="categoria_id" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('categoria_id') border-red-500 @enderror">
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
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('ubicacion_id') border-red-500 @enderror">
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
                    </div> {{-- Fin del grid de información general --}}

                    {{-- Sección de Dimensiones de Envío (¡IMPORTANTE!) --}}
                    <div class="mb-6 border-t pt-6 pb-4">
                        <h3 class="text-lg font-medium text-gray-900">Dimensiones y Peso para Envío <span class="text-sm text-gray-500">(¡Con Embalaje Individual!)</span></h3>
                        <p class="mt-1 text-sm text-gray-600 mb-4">Ingresa las medidas y el peso de la artesanía **ya embalada y lista para ser enviada individualmente**.</p>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            {{-- Campo Peso --}}
                            <div>
                                <label for="weight" class="block text-sm font-medium text-gray-700">Peso (KG):</label>
                                <input type="number" name="weight" id="weight" step="0.01" min="0.01" value="{{ old('weight') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('weight') border-red-500 @enderror" required>
                                @error('weight')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Campo Largo --}}
                            <div>
                                <label for="length" class="block text-sm font-medium text-gray-700">Largo (CM):</label>
                                <input type="number" name="length" id="length" step="0.1" min="0.1" value="{{ old('length') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('length') border-red-500 @enderror" required>
                                @error('length')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Campo Ancho --}}
                            <div>
                                <label for="width" class="block text-sm font-medium text-gray-700">Ancho (CM):</label>
                                <input type="number" name="width" id="width" step="0.1" min="0.1" value="{{ old('width') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('width') border-red-500 @enderror" required>
                                @error('width')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Campo Alto --}}
                            <div>
                                <label for="height" class="block text-sm font-medium text-gray-700">Alto (CM):</label>
                                <input type="number" name="height" id="height" step="0.1" min="0.1" value="{{ old('height') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('height') border-red-500 @enderror" required>
                                @error('height')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                        </div>
                    </div> {{-- Fin de la sección de dimensiones --}}


                    {{-- Campo Descripción --}}
                    <div class="mt-6">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción General:</label>
                        <textarea name="descripcion" id="descripcion" rows="4"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('descripcion') border-red-500 @enderror" required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Variantes --}}
            <div class="mb-6">
                <label class="block font-semibold mb-2">Variantes (Tallas / Colores):</label>

                <div id="variants-container">
                    <div class="variant-item grid grid-cols-4 gap-2 mb-2">
                        <input type="text" name="variants[0][color]" placeholder="Color" class="border p-2 rounded" required>
                        <input type="text" name="variants[0][size]" placeholder="Talla" class="border p-2 rounded" required>
                        <input type="number" name="variants[0][stock]" placeholder="Stock" class="border p-2 rounded" required>
                        <input type="number" name="variants[0][price_adjustment]" placeholder="Ajuste $" step="0.01" class="border p-2 rounded">
                    </div>
                </div>

                    {{-- Campo Imagen Principal --}}
                    <div class="mt-6">
                        <label for="imagen_principal" class="block text-sm font-medium text-gray-700">Imagen Principal:</label>
                        <input type="file" name="imagen_principal" id="imagen_principal" required
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('imagen_principal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo Imágenes Adicionales (múltiples archivos) --}}
                    <div class="mt-6">
                        <label for="imagen_adicionales" class="block text-sm font-medium text-gray-700">Imágenes Adicionales:</label>
                        <input type="file" name="imagen_adicionales[]" id="imagen_adicionales" multiple
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('imagen_adicionales')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @error('imagen_adicionales.*') {{-- Para errores de cada archivo --}}
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-8 flex justify-end space-x-4 border-t pt-6">
                        <a href="{{ route('admin.artesanias.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Guardar Artesanía
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>