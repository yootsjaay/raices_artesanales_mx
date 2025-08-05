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
                    <h3 class="text-lg font-medium text-gray-900">Información General de la Artesanía</h3>
                    <p class="mt-1 text-sm text-gray-600">Completa los detalles para añadir un nuevo tipo de artesanía a tu inventario.</p>
                </div>

                <form action="{{ route('admin.artesanias.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Sección de Información General de la Artesanía (Producto Padre) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- Campo: Nombre General --}}
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre General:</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nombre') border-red-500 @enderror" required>
                            @error('nombre')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo: Slug (opcional, se puede dejar para que se genere automáticamente) --}}
                        {{-- Si prefieres que el usuario no lo edite, no lo incluyas aquí y genéralo en el controlador --}}
                        {{-- <div class="col-span-1">
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug:</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('slug') border-red-500 @enderror">
                            @error('slug')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div> --}}

                        {{-- Campo: Categoría --}}
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

                        {{-- Campo: Ubicación --}}
                        <div>
                            <label for="ubicacion_id" class="block text-sm font-medium text-gray-700">Ubicación de Origen/Venta:</label>
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
                    </div>

                    {{-- Agregado: Campos de Precio, Peso y Dimensiones --}}
                    <div class="mb-6 border-b pb-4">
                        <h3 class="text-lg font-medium text-gray-900">Precio y Dimensiones Generales</h3>
                        <p class="mt-1 text-sm text-gray-600">Define el precio base, peso y dimensiones para la artesanía general. Estos pueden ser sobrescritos por las variantes.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
                        {{-- Campo: Precio Base --}}
                        <div>
                            <label for="precio" class="block text-sm font-medium text-gray-700">Precio Base:</label>
                            <input type="number" name="precio" id="precio" step="0.01" value="{{ old('precio') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('precio') border-red-500 @enderror">
                            @error('precio')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo: Peso (KG) --}}
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700">Peso (KG):</label>
                            <input type="number" name="weight" id="weight" step="0.01" value="{{ old('weight', 0.00) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('weight') border-red-500 @enderror">
                            @error('weight')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo: Largo (CM) --}}
                        <div>
                            <label for="length" class="block text-sm font-medium text-gray-700">Largo (CM):</label>
                            <input type="number" name="length" id="length" step="0.01" value="{{ old('length', 0.00) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('length') border-red-500 @enderror">
                            @error('length')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo: Ancho (CM) --}}
                        <div>
                            <label for="width" class="block text-sm font-medium text-gray-700">Ancho (CM):</label>
                            <input type="number" name="width" id="width" step="0.01" value="{{ old('width', 0.00) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('width') border-red-500 @enderror">
                            @error('width')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo: Alto (CM) --}}
                        <div>
                            <label for="height" class="block text-sm font-medium text-gray-700">Alto (CM):</label>
                            <input type="number" name="height" id="height" step="0.01" value="{{ old('height', 0.00) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('height') border-red-500 @enderror">
                            @error('height')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-span-full">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción General del Tipo de Artesanía:</label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-full">
                        <label for="historia_piezas_general" class="block text-sm font-medium text-gray-700">Historia o Contexto Cultural General:</label>
                        <textarea name="historia_piezas_general" id="historia_piezas_general" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('historia_piezas_general') border-red-500 @enderror">{{ old('historia_piezas_general') }}</textarea>
                        @error('historia_piezas_general')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-full mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Imágenes Generales de la Artesanía (Tipo)</label>
                        <input type="file" name="imagenes_artesanias[]" multiple
                               class="w-full text-sm border-gray-300 rounded-md shadow-sm @error('imagenes_artesanias.*') border-red-500 @enderror">
                        @error('imagenes_artesanias.*')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <hr class="my-6">

                    {{-- Sección para añadir variantes dinámicamente --}}
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Variantes de la Artesanía</h3>
                        <button type="button" id="add-variant" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Añadir Variante
                        </button>
                    </div>

                    <div id="variants-container">
                        @if (old('variants'))
                            @foreach(old('variants') as $variantIndex => $oldVariant)
                                <div class="variant-item p-4 border rounded-md shadow-sm mb-4 bg-gray-50">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                        {{-- Campo: SKU --}}
                                        <div>
                                            <label for="variants[{{ $variantIndex }}][sku]" class="block text-sm font-medium text-gray-700">SKU:</label>
                                            <input type="text" name="variants[{{ $variantIndex }}][sku]" id="variants[{{ $variantIndex }}][sku]" value="{{ $oldVariant['sku'] }}"
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('variants.' . $variantIndex . '.sku') border-red-500 @enderror" required>
                                            @error('variants.' . $variantIndex . '.sku')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Campo: Nombre de Variante --}}
                                        <div>
                                            <label for="variants[{{ $variantIndex }}][variant_name]" class="block text-sm font-medium text-gray-700">Nombre de la Variante:</label>
                                            <input type="text" name="variants[{{ $variantIndex }}][variant_name]" id="variants[{{ $variantIndex }}][variant_name]" value="{{ $oldVariant['variant_name'] }}"
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('variants.' . $variantIndex . '.variant_name') border-red-500 @enderror">
                                            @error('variants.' . $variantIndex . '.variant_name')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Campo: Talla --}}
                                        <div>
                                            <label for="variants[{{ $variantIndex }}][size]" class="block text-sm font-medium text-gray-700">Talla:</label>
                                            <input type="text" name="variants[{{ $variantIndex }}][size]" id="variants[{{ $variantIndex }}][size]" value="{{ $oldVariant['size'] }}"
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('variants.' . $variantIndex . '.size') border-red-500 @enderror">
                                            @error('variants.' . $variantIndex . '.size')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Campo: Color --}}
                                        <div>
                                            <label for="variants[{{ $variantIndex }}][color]" class="block text-sm font-medium text-gray-700">Color:</label>
                                            <input type="text" name="variants[{{ $variantIndex }}][color]" id="variants[{{ $variantIndex }}][color]" value="{{ $oldVariant['color'] }}"
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('variants.' . $variantIndex . '.color') border-red-500 @enderror">
                                            @error('variants.' . $variantIndex . '.color')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Campo: Material --}}
                                        <div>
                                            <label for="variants[{{ $variantIndex }}][material_variant]" class="block text-sm font-medium text-gray-700">Material:</label>
                                            <input type="text" name="variants[{{ $variantIndex }}][material_variant]" id="variants[{{ $variantIndex }}][material_variant]" value="{{ $oldVariant['material_variant'] }}"
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('variants.' . $variantIndex . '.material_variant') border-red-500 @enderror">
                                            @error('variants.' . $variantIndex . '.material_variant')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        {{-- Campo: Precio de la variante --}}
                                        <div>
                                            <label for="variants[{{ $variantIndex }}][precio]" class="block text-sm font-medium text-gray-700">Precio:</label>
                                            <input type="number" name="variants[{{ $variantIndex }}][precio]" id="variants[{{ $variantIndex }}][precio]" step="0.01" value="{{ $oldVariant['precio'] }}"
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('variants.' . $variantIndex . '.precio') border-red-500 @enderror" required>
                                            @error('variants.' . $variantIndex . '.precio')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Campo: Stock --}}
                                        <div>
                                            <label for="variants[{{ $variantIndex }}][stock]" class="block text-sm font-medium text-gray-700">Stock:</label>
                                            <input type="number" name="variants[{{ $variantIndex }}][stock]" id="variants[{{ $variantIndex }}][stock]" value="{{ $oldVariant['stock'] }}"
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('variants.' . $variantIndex . '.stock') border-red-500 @enderror">
                                            @error('variants.' . $variantIndex . '.stock')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Campo: Descripción corta de la variante --}}
                                        <div class="col-span-full">
                                            <label for="variants[{{ $variantIndex }}][description_variant]" class="block text-sm font-medium text-gray-700">Descripción de la Variante:</label>
                                            <textarea name="variants[{{ $variantIndex }}][description_variant]" id="variants[{{ $variantIndex }}][description_variant]" rows="2"
                                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('variants.' . $variantIndex . '.description_variant') border-red-500 @enderror">{{ $oldVariant['description_variant'] }}</textarea>
                                            @error('variants.' . $variantIndex . '.description_variant')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Campo: Tipo de Embalaje --}}
                                        <div>
                                            <label for="variants[{{ $variantIndex }}][tipo_embalaje_id]" class="block text-sm font-medium text-gray-700">Tipo de Embalaje:</label>
                                            <select name="variants[{{ $variantIndex }}][tipo_embalaje_id]" id="variants[{{ $variantIndex }}][tipo_embalaje_id]"
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('variants.' . $variantIndex . '.tipo_embalaje_id') border-red-500 @enderror">
                                                <option value="">Seleccione un embalaje</option>
                                                @foreach ($tipos_embalaje as $tipo)
                                                    <option value="{{ $tipo->id }}" {{ old('variants.' . $variantIndex . '.tipo_embalaje_id') == $tipo->id ? 'selected' : '' }}>
                                                        {{ $tipo->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('variants.' . $variantIndex . '.tipo_embalaje_id')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        {{-- Campo: Activa --}}
                                        <div class="flex items-center">
                                            <input type="checkbox" name="variants[{{ $variantIndex }}][is_active]" value="1"
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                   {{ (isset($oldVariant['is_active']) && $oldVariant['is_active']) ? 'checked' : (empty($oldVariant) ? 'checked' : '') }}>
                                            <label class="ml-2 block text-sm font-medium text-gray-700">Activa</label>
                                            @error('variants.' . $variantIndex . '.is_active')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Campo para todas las imágenes de la variante --}}
                                        <div class="col-span-full mt-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Imágenes de la Variante (Principal y Adicionales)</label>
                                            <input type="file" name="variants[{{ $variantIndex }}][new_variant_images][]" multiple
                                                   class="w-full text-sm border-gray-300 rounded-md shadow-sm @error('variants.' . $variantIndex . '.new_variant_images.*') border-red-500 @enderror">
                                            @error('variants.' . $variantIndex . '.new_variant_images.*')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="flex items-end md:col-span-full lg:col-span-1">
                                            <button type="button" class="remove-variant text-red-500 hover:text-red-700 text-sm">Eliminar Variante</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <div class="flex justify-end mt-6">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Crear Artesanía
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const variantsContainer = document.getElementById('variants-container');
            const addVariantButton = document.getElementById('add-variant');
            let variantIndex = {{ old('variants') ? count(old('variants')) : 0 }};

            addVariantButton.addEventListener('click', function () {
                const variantHtml = `
                    <div class="variant-item p-4 border rounded-md shadow-sm mb-4 bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            {{-- Campo: SKU --}}
                            <div>
                                <label for="variants[${variantIndex}][sku]" class="block text-sm font-medium text-gray-700">SKU:</label>
                                <input type="text" name="variants[${variantIndex}][sku]" id="variants[${variantIndex}][sku]"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>

                            {{-- Campo: Nombre de Variante --}}
                            <div>
                                <label for="variants[${variantIndex}][variant_name]" class="block text-sm font-medium text-gray-700">Nombre de la Variante:</label>
                                <input type="text" name="variants[${variantIndex}][variant_name]" id="variants[${variantIndex}][variant_name]"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            {{-- Campo: Talla --}}
                            <div>
                                <label for="variants[${variantIndex}][size]" class="block text-sm font-medium text-gray-700">Talla:</label>
                                <input type="text" name="variants[${variantIndex}][size]" id="variants[${variantIndex}][size]"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            {{-- Campo: Color --}}
                            <div>
                                <label for="variants[${variantIndex}][color]" class="block text-sm font-medium text-gray-700">Color:</label>
                                <input type="text" name="variants[${variantIndex}][color]" id="variants[${variantIndex}][color]"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            {{-- Campo: Material --}}
                            <div>
                                <label for="variants[${variantIndex}][material_variant]" class="block text-sm font-medium text-gray-700">Material:</label>
                                <input type="text" name="variants[${variantIndex}][material_variant]" id="variants[${variantIndex}][material_variant]"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            {{-- Campo: Precio de la variante --}}
                            <div>
                                <label for="variants[${variantIndex}][precio]" class="block text-sm font-medium text-gray-700">Precio:</label>
                                <input type="number" name="variants[${variantIndex}][precio]" id="variants[${variantIndex}][precio]" step="0.01"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>

                            {{-- Campo: Stock --}}
                            <div>
                                <label for="variants[${variantIndex}][stock]" class="block text-sm font-medium text-gray-700">Stock:</label>
                                <input type="number" name="variants[${variantIndex}][stock]" id="variants[${variantIndex}][stock]"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            {{-- Campo: Descripción corta de la variante --}}
                            <div class="col-span-full">
                                <label for="variants[${variantIndex}][description_variant]" class="block text-sm font-medium text-gray-700">Descripción de la Variante:</label>
                                <textarea name="variants[${variantIndex}][description_variant]" id="variants[${variantIndex}][description_variant]" rows="2"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>

                            {{-- Campo: Tipo de Embalaje --}}
                            <div>
                                <label for="variants[${variantIndex}][tipo_embalaje_id]" class="block text-sm font-medium text-gray-700">Tipo de Embalaje:</label>
                                <select name="variants[${variantIndex}][tipo_embalaje_id]" id="variants[${variantIndex}][tipo_embalaje_id]"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleccione un embalaje</option>
                                    @foreach ($tipos_embalaje as $tipo)
                                        <option value="{{ $tipo->id }}">
                                            {{ $tipo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            {{-- Campo: Activa --}}
                            <div class="flex items-center">
                                <input type="checkbox" name="variants[${variantIndex}][is_active]" value="1"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" checked>
                                <label class="ml-2 block text-sm font-medium text-gray-700">Activa</label>
                            </div>

                            {{-- Campo para todas las imágenes de la variante --}}
                            <div class="col-span-full mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Imágenes de la Variante (Principal y Adicionales)</label>
                                <input type="file" name="variants[${variantIndex}][new_variant_images][]" multiple
                                       class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                            </div>

                            <div class="flex items-end md:col-span-full lg:col-span-1">
                                <button type="button" class="remove-variant text-red-500 hover:text-red-700 text-sm">Eliminar Variante</button>
                            </div>
                        </div>
                    </div>
                `;
                variantsContainer.insertAdjacentHTML('beforeend', variantHtml);
                variantIndex++;
            });

            variantsContainer.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-variant')) {
                    e.target.closest('.variant-item').remove();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
