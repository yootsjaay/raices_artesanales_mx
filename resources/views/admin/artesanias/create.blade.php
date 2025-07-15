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
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $artesania->nombre ?? '') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nombre') border-red-500 @enderror" required>
                            @error('nombre')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo Precio --}}
                        <div>
                            <label for="precio" class="block text-sm font-medium text-gray-700">Precio (MXN):</label>
                            <input type="number" name="precio" id="precio" step="0.01" value="{{ old('precio', $artesania->precio ?? '') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('precio') border-red-500 @enderror" required>
                            @error('precio')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo Stock --}}
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700">Stock:</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', $artesania->stock ?? '') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('stock') border-red-500 @enderror" required>
                            @error('stock')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo Materiales --}}
                        <div>
                            <label for="materiales" class="block text-sm font-medium text-gray-700">Materiales:</label>
                            <input type="text" name="materiales" id="materiales" value="{{ old('materiales', $artesania->materiales ?? '') }}"
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
                                    <option value="{{ $categoria->id }}" {{ old('categoria_id', $artesania->categoria_id ?? '') == $categoria->id ? 'selected' : '' }}>
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
                                    <option value="{{ $ubicacion->id }}" {{ old('ubicacion_id', $artesania->ubicacion_id ?? '') == $ubicacion->id ? 'selected' : '' }}>
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
                                <input type="number" name="weight" id="weight" step="0.01" min="0.01" value="{{ old('weight', $artesania->weight ?? '') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('weight') border-red-500 @enderror" required>
                                @error('weight')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Campo Largo --}}
                            <div>
                                <label for="length" class="block text-sm font-medium text-gray-700">Largo (CM):</label>
                                <input type="number" name="length" id="length" step="0.1" min="0.1" value="{{ old('length', $artesania->length ?? '') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('length') border-red-500 @enderror" required>
                                @error('length')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Campo Ancho --}}
                            <div>
                                <label for="width" class="block text-sm font-medium text-gray-700">Ancho (CM):</label>
                                <input type="number" name="width" id="width" step="0.1" min="0.1" value="{{ old('width', $artesania->width ?? '') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('width') border-red-500 @enderror" required>
                                @error('width')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Campo Alto --}}
                            <div>
                                <label for="height" class="block text-sm font-medium text-gray-700">Alto (CM):</label>
                                <input type="number" name="height" id="height" step="0.1" min="0.1" value="{{ old('height', $artesania->height ?? '') }}"
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
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('descripcion') border-red-500 @enderror" required>{{ old('descripcion', $artesania->descripcion ?? '') }}</textarea>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                 {{-- Sección de Variantes --}}
<div class="mb-6 mt-6 border-t pt-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-medium text-gray-900">Variantes</h3>
        <button type="button" id="add-variant" class="text-sm bg-indigo-100 text-indigo-700 px-3 py-1 rounded-md hover:bg-indigo-200">
            + Añadir Variante
        </button>
    </div>

    <div id="variants-container">
        @forelse(old('variants', $artesania->variants ?? [[]]) as $index => $variant)
            <div class="variant-item grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                {{-- Hidden input para ID si existe --}}
                @if(isset($variant['id']))
                    <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant['id'] }}">
                @endif

                {{-- Nombre Variante --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Variante</label>
                    <input type="text" name="variants[{{ $index }}][variant_name]" placeholder="Ej: Playera Azul"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old("variants.$index.variant_name", $variant['variant_name'] ?? '') }}">
                    @error("variants.$index.variant_name")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción Variante --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción Variante</label>
                    <input type="text" name="variants[{{ $index }}][description_variant]" placeholder="Descripción corta"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old("variants.$index.description_variant", $variant['description_variant'] ?? '') }}">
                    @error("variants.$index.description_variant")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Color --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <input type="text" name="variants[{{ $index }}][color]" placeholder="Ej: Rojo"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old("variants.$index.color", $variant['color'] ?? '') }}" required>
                    @error("variants.$index.color")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Talla (size) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Talla</label>
                    <input type="text" name="variants[{{ $index }}][size]" placeholder="Ej: M, L"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old("variants.$index.size", $variant['size'] ?? '') }}">
                    @error("variants.$index.size")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Material --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                    <input type="text" name="variants[{{ $index }}][material_variant]" placeholder="Ej: Algodón"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old("variants.$index.material_variant", $variant['material_variant'] ?? '') }}">
                    @error("variants.$index.material_variant")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SKU --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" name="variants[{{ $index }}][sku]"
                        class="w-full border-gray-300 rounded-md shadow-sm"
                        value="{{ old("variants.$index.sku", $variant['sku'] ?? '') }}">
                    @error("variants.$index.sku")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Dimensiones --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dimensiones</label>
                    <input type="text" name="variants[{{ $index }}][dimensions]" placeholder="Ej: 10x20x5cm"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old("variants.$index.dimensions", $variant['dimensions'] ?? '') }}">
                    @error("variants.$index.dimensions")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Peso --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Peso (kg)</label>
                    <input type="number" step="0.01" name="variants[{{ $index }}][weight]" placeholder="Ej: 0.50"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old("variants.$index.weight", $variant['weight'] ?? '') }}">
                    @error("variants.$index.weight")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ajuste de Precio --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ajuste de Precio</label>
                    <input type="number" step="0.01" name="variants[{{ $index }}][price_adjustment]"
                        placeholder="+/- $" class="w-full border-gray-300 rounded-md shadow-sm"
                        value="{{ old("variants.$index.price_adjustment", $variant['price_adjustment'] ?? '') }}">
                    @error("variants.$index.price_adjustment")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Stock --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" name="variants[{{ $index }}][stock]" min="0"
                        class="w-full border-gray-300 rounded-md shadow-sm"
                        value="{{ old("variants.$index.stock", $variant['stock'] ?? 0) }}">
                    @error("variants.$index.stock")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Imagen de la variante --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
                    <input type="file" name="variants[{{ $index }}][image]"
                        class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                    @error("variants.$index.image")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @if(isset($variant['image_url']))
                        <img src="{{ asset('storage/' . $variant['image_url']) }}" alt="Imagen Variante" class="mt-2 h-16 w-16 object-cover rounded-md">
                    @endif
                </div>

                {{-- Imágenes adicionales --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Imágenes Adicionales</label>
                    <input type="file" name="variants[{{ $index }}][additional_images_urls][]" multiple
                        class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                    @error("variants.$index.additional_images_urls")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @if(isset($variant['additional_images_urls']) && is_array($variant['additional_images_urls']))
                        <div class="mt-2 flex space-x-2">
                            @foreach($variant['additional_images_urls'] as $img)
                                <img src="{{ asset('storage/' . $img) }}" alt="Imagen Adicional" class="h-12 w-12 object-cover rounded-md">
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Botón eliminar --}}
                <div class="flex items-end">
                    <button type="button" class="remove-variant text-red-500 hover:text-red-700 text-sm">Eliminar</button>
                </div>
            </div>
        @empty
            {{-- Variante vacía por defecto --}}
            <div class="variant-item grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                {{-- Nombre Variante --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Variante</label>
                    <input type="text" name="variants[0][variant_name]" placeholder="Ej: Playera Azul"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                {{-- Descripción Variante --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción Variante</label>
                    <input type="text" name="variants[0][description_variant]" placeholder="Descripción corta"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                {{-- Color --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <input type="text" name="variants[0][color]" placeholder="Ej: Rojo"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                {{-- Talla / Tamaño / Atributo dinámico --}}
                <div class="variant-attribute-field">
                    <label class="block text-sm font-medium text-gray-700 mb-1 variant-attribute-label">Talla</label>
                    <input type="text" name="variants[0][size]" placeholder="Ej: M"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 variant-attribute">
                </div>
                {{-- Material --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                    <input type="text" name="variants[0][material_variant]" placeholder="Ej: Algodón"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                {{-- SKU --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" name="variants[0][sku]"
                        class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                {{-- Dimensiones --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dimensiones</label>
                    <input type="text" name="variants[0][dimensions]" placeholder="Ej: 10x20x5cm"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                {{-- Peso --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Peso (kg)</label>
                    <input type="number" step="0.01" name="variants[0][weight]" placeholder="Ej: 0.50"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                {{-- Ajuste de Precio --}}
                <div class="variant-price-field">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ajuste de Precio</label>
                    <input type="number" step="0.01" name="variants[0][price_adjustment]"
                        placeholder="+/- $" class="w-full border-gray-300 rounded-md shadow-sm variant-price">
                </div>
                {{-- Stock --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" name="variants[0][stock]" min="0"
                        class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                {{-- Imagen --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
                    <input type="file" name="variants[0][image]"
                        class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                </div>
                {{-- Imágenes Adicionales --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Imágenes Adicionales</label>
                    <input type="file" name="variants[0][additional_images_urls][]" multiple
                        class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                </div>
                {{-- Botón eliminar --}}
                <div class="flex items-end">
                    <button type="button" class="remove-variant text-red-500 hover:text-red-700 text-sm hidden">Eliminar</button>
                </div>
            </div>
        @endforelse
    </div>

                    {{-- Campo Imagen Principal --}}
                    <div class="mt-6">
                        <label for="imagen_principal" class="block text-sm font-medium text-gray-700">Imagen Principal:</label>
                        <input type="file" name="imagen_principal" id="imagen_principal" {{ isset($artesania) ? '' : 'required' }}
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('imagen_principal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @if(isset($artesania) && $artesania->imagen_principal_url)
                            <img src="{{ asset('storage/' . $artesania->imagen_principal_url) }}" alt="Imagen Principal Actual" class="mt-2 h-20 w-20 object-cover rounded-md">
                        @endif
                    </div>

                    {{-- Campo Imágenes Adicionales (múltiples archivos) --}}
                    <div class="mt-6">
                        <label for="imagen_adicionales" class="block text-sm font-medium text-gray-700">Imágenes Adicionales:</label>
                        <input type="file" name="imagen_adicionales[]" id="imagen_adicionales" multiple
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('imagen_adicionales')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @error('imagen_adicionales.*')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @if(isset($artesania) && $artesania->imagenes_adicionales)
                            <div class="mt-2 flex space-x-2">
                                @foreach($artesania->imagenes_adicionales as $imagePath)
                                    <img src="{{ asset('storage/' . $imagePath) }}" alt="Imagen Adicional" class="h-20 w-20 object-cover rounded-md">
                                @endforeach
                            </div>
                        @endif
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
    @push('scripts')
        <script>
            // Configuración de categorías y sus atributos
            const categoryAttributes = {
    @foreach ($categorias as $categoria)
        "{{ $categoria->id }}": {
            name: @json($categoria->nombre),
            attributeType: @json(in_array($categoria->nombre, ['Ropas', 'Calzados']) ? 'Talla' : ($categoria->nombre === 'Barros' ? 'Tamaño' : 'Atributo')),
            options: @json($categoria->nombre === 'Barros' ? ['Chico', 'Mediano', 'Grande'] : []),
            requiresAttribute: {{ in_array($categoria->nombre, ['Ropas', 'Calzados', 'Barros']) ? 'true' : 'false' }},
            requiresPriceAdjustment: {{ in_array($categoria->nombre, ['Ropas', 'Calzados']) ? 'true' : 'false' }}
        }{{ !$loop->last ? ',' : '' }}
    @endforeach
};

            let variantCounter = {{ count(old('variants', $artesania->variants ?? [])) > 0 ? count(old('variants', $artesania->variants ?? [])) : 1 }};

            // Función para actualizar los campos de variantes según la categoría
            function updateVariantFields() {
                const categoriaId = document.getElementById('categoria_id').value;
                const config = categoryAttributes[categoriaId] || { attributeType: 'Atributo', options: [], requiresAttribute: false, requiresPriceAdjustment: false };

                document.querySelectorAll('.variant-item').forEach(item => {
                    const attributeField = item.querySelector('.variant-attribute-field');
                    const attributeLabel = item.querySelector('.variant-attribute-label');
                    const attributeInput = item.querySelector('.variant-attribute');
                    const priceField = item.querySelector('.variant-price-field');

                    // Actualizar la etiqueta del campo de atributo
                    attributeLabel.textContent = config.attributeType;

                    // Mostrar u ocultar el campo de atributo según la configuración
                    if (config.requiresAttribute) {
                        attributeField.classList.remove('hidden');
                        attributeInput.required = true;
                    } else {
                        attributeField.classList.add('hidden');
                        attributeInput.required = false;
                        attributeInput.value = '';
                    }

                    // Mostrar u ocultar el campo de ajuste de precio
                    if (config.requiresPriceAdjustment) {
                        priceField.classList.remove('hidden');
                    } else {
                        priceField.classList.add('hidden');
                        item.querySelector('.variant-price').value = '';
                    }

                    // Cambiar entre input y select según las opciones disponibles
                    if (config.options.length > 0) {
                        // Reemplazar input por select
                        const newSelect = document.createElement('select');
                        newSelect.name = attributeInput.name;
                        newSelect.className = attributeInput.className;
                        newSelect.required = config.requiresAttribute;
                        newSelect.innerHTML = '<option value="">Seleccione ' + config.attributeType + '</option>' +
                            config.options.map(option => `<option value="${option}" ${attributeInput.value === option ? 'selected' : ''}>${option}</option>`).join('');
                        attributeInput.replaceWith(newSelect);
                    } else {
                        // Reemplazar select por input si no hay opciones
                        if (attributeInput.tagName.toLowerCase() === 'select') {
                            const newInput = document.createElement('input');
                            newInput.type = 'text';
                            newInput.name = attributeInput.name;
                            newInput.className = attributeInput.className;
                            newInput.placeholder = `Ej: ${config.attributeType === 'Talla' ? 'M, 38, etc' : 'Chico, etc'}`;
                            newInput.required = config.requiresAttribute;
                            attributeInput.replaceWith(newInput);
                        }
                    }
                });
            }

            // Añadir nueva variante
            document.getElementById('add-variant').addEventListener('click', function() {
                const container = document.getElementById('variants-container');
                const config = categoryAttributes[document.getElementById('categoria_id').value] || { attributeType: 'Atributo', options: [], requiresAttribute: false, requiresPriceAdjustment: false };

                const newItem = document.createElement('div');
                 newItem.className = 'variant-item grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-4 p-4 bg-gray-50 rounded-lg';
    newItem.innerHTML = `
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Variante</label>
            <input type="text" name="variants[${variantCounter}][variant_name]" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Ej: Playera Azul">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción Variante</label>
            <input type="text" name="variants[${variantCounter}][description_variant]" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Descripción corta">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
            <input type="text" name="variants[${variantCounter}][color]" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Ej: Rojo">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Talla</label>
            <input type="text" name="variants[${variantCounter}][size]" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Ej: M, L">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Material</label>
            <input type="text" name="variants[${variantCounter}][material_variant]" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Ej: Algodón">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
            <input type="text" name="variants[${variantCounter}][sku]" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="SKU único">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Dimensiones</label>
            <input type="text" name="variants[${variantCounter}][dimensions]" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Ej: 10x20x5cm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Peso (kg)</label>
            <input type="number" step="0.01" name="variants[${variantCounter}][weight]" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Ej: 0.50">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ajuste Precio</label>
            <input type="number" step="0.01" name="variants[${variantCounter}][price_adjustment]" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="+/- $">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
            <input type="number" name="variants[${variantCounter}][stock]" min="0" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Cantidad">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
            <input type="file" name="variants[${variantCounter}][image]" class="w-full text-sm border-gray-300 rounded-md shadow-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Imágenes Adicionales</label>
            <input type="file" name="variants[${variantCounter}][additional_images_urls][]" multiple class="w-full text-sm border-gray-300 rounded-md shadow-sm">
        </div>
        <div class="flex items-end">
            <button type="button" class="remove-variant text-red-500 hover:text-red-700 text-sm">Eliminar</button>
        </div>
    `;

                container.appendChild(newItem);
                variantCounter++;
                updateRemoveButtons();
                updateVariantFields(); // Asegurar que los nuevos campos se ajusten a la categoría
            });

            // Eliminar variante
            document.getElementById('variants-container').addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-variant')) {
                    event.target.closest('.variant-item').remove();
                    updateRemoveButtons();
                    updateVariantFieldNames();
                }
            });

            // Actualizar visibilidad de botones de eliminación
            function updateRemoveButtons() {
                const variantItems = document.querySelectorAll('.variant-item');
                variantItems.forEach((item, index) => {
                    const removeButton = item.querySelector('.remove-variant');
                    if (removeButton) {
                        if (variantItems.length > 1) {
                            removeButton.classList.remove('hidden');
                        } else {
                            removeButton.classList.add('hidden');
                        }
                    }
                });
            }

            // Reindexar nombres de campos de variantes después de eliminación
            function updateVariantFieldNames() {
                document.querySelectorAll('.variant-item').forEach((item, index) => {
                    item.querySelectorAll('input, select').forEach(input => {
                        if (input.name) {
                            input.name = input.name.replace(/variants\[\d+\]/, `variants[${index}]`);
                        }
                    });
                });
                variantCounter = document.querySelectorAll('.variant-item').length;
            }

            // Event listener para cambio de categoría
            document.getElementById('categoria_id').addEventListener('change', updateVariantFields);

            // Inicializar en la carga de la página
            document.addEventListener('DOMContentLoaded', function() {
                updateVariantFields();
                updateRemoveButtons();
            });
        </script>
    @endpush
</x-app-layout>