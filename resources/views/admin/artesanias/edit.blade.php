<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Artesanía: ') . $artesania->nombre }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12"> {{-- Ajuste de padding vertical --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6 sm:p-8"> {{-- Sombra y padding mejorados --}}

                <div class="mb-6 border-b pb-4">
                    <h3 class="text-lg font-medium text-gray-900">Detalles de la Artesanía</h3>
                    <p class="mt-1 text-sm text-gray-600">Edita la información básica y las dimensiones para el envío.</p>
                </div>

                <form action="{{ route('admin.artesanias.update', $artesania->slug) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- Importante para las actualizaciones --}}

                    {{-- Sección de Información General --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- Campo Nombre --}}
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre:</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $artesania->nombre) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nombre') border-red-500 @enderror" required>
                            @error('nombre')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo Precio --}}
                        <div>
                            <label for="precio" class="block text-sm font-medium text-gray-700">Precio (MXN):</label>
                            <input type="number" name="precio" id="precio" step="0.01" value="{{ old('precio', $artesania->precio) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('precio') border-red-500 @enderror" required>
                            @error('precio')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo Stock --}}
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700">Stock:</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', $artesania->stock) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('stock') border-red-500 @enderror" required>
                            @error('stock')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo Materiales --}}
                        <div>
                            <label for="materiales" class="block text-sm font-medium text-gray-700">Materiales:</label>
                            <input type="text" name="materiales" id="materiales" value="{{ old('materiales', $artesania->materiales) }}"
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
                                    <option value="{{ $categoria->id }}" {{ old('categoria_id', $artesania->categoria_id) == $categoria->id ? 'selected' : '' }}>
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
                                    <option value="{{ $ubicacion->id }}" {{ old('ubicacion_id', $artesania->ubicacion_id) == $ubicacion->id ? 'selected' : '' }}>
                                        {{ $ubicacion->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ubicacion_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div> {{-- Fin del grid de información general --}}

                  {{-- 📦 Sección: Dimensiones y Peso para Envío --}}
<div class="mb-8 border-t pt-8">
    <h3 class="text-xl font-bold text-oaxaca-primary mb-2">
        📦 Dimensiones y Peso para Envío
        <span class="text-sm font-normal text-gray-500">(con embalaje individual)</span>
    </h3>
    <p class="text-sm text-oaxaca-text-dark mb-6">
        Ingresa las medidas y el peso de la artesanía <strong>ya embalada</strong> y lista para envío. Esto es indispensable para calcular el costo real de paquetería.
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        {{-- Peso --}}
        <div>
            <label for="weight" class="block text-sm font-semibold text-oaxaca-text-dark">Peso (kg)</label>
            <input type="number" name="weight" id="weight" step="0.01" min="0.01"
                placeholder="Ej. 1.25"
                value="{{ old('weight', $artesania->weight) }}"
                class="mt-1 block w-full border border-oaxaca-primary border-opacity-30 rounded-md shadow-sm focus:ring-oaxaca-tertiary focus:border-oaxaca-tertiary @error('weight') border-red-500 @enderror" required>
            @error('weight')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Largo --}}
        <div>
            <label for="length" class="block text-sm font-semibold text-oaxaca-text-dark">Largo (cm)</label>
            <input type="number" name="length" id="length" step="0.1" min="0.1"
                placeholder="Ej. 40.5"
                value="{{ old('length', $artesania->length) }}"
                class="mt-1 block w-full border border-oaxaca-primary border-opacity-30 rounded-md shadow-sm focus:ring-oaxaca-tertiary focus:border-oaxaca-tertiary @error('length') border-red-500 @enderror" required>
            @error('length')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Ancho --}}
        <div>
            <label for="width" class="block text-sm font-semibold text-oaxaca-text-dark">Ancho (cm)</label>
            <input type="number" name="width" id="width" step="0.1" min="0.1"
                placeholder="Ej. 20.0"
                value="{{ old('width', $artesania->width) }}"
                class="mt-1 block w-full border border-oaxaca-primary border-opacity-30 rounded-md shadow-sm focus:ring-oaxaca-tertiary focus:border-oaxaca-tertiary @error('width') border-red-500 @enderror" required>
            @error('width')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Alto --}}
        <div>
            <label for="height" class="block text-sm font-semibold text-oaxaca-text-dark">Alto (cm)</label>
            <input type="number" name="height" id="height" step="0.1" min="0.1"
                placeholder="Ej. 15.3"
                value="{{ old('height', $artesania->height) }}"
                class="mt-1 block w-full border border-oaxaca-primary border-opacity-30 rounded-md shadow-sm focus:ring-oaxaca-tertiary focus:border-oaxaca-tertiary @error('height') border-red-500 @enderror" required>
            @error('height')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>


                    {{-- Campo Descripción --}}
                    <div class="mt-6">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción General:</label>
                        <textarea name="descripcion" id="descripcion" rows="4"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('descripcion') border-red-500 @enderror" required>{{ old('descripcion', $artesania->descripcion) }}</textarea>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                {{-- Variantes --}}
<div class="mb-6">
    <label class="block font-semibold mb-2">Variantes (Tallas / Colores / Materiales):</label>

    <div id="variants-container">
        @php
            $oldVariants = old('variants');
            $variants = $oldVariants !== null
                ? $oldVariants
                : (isset($artesania->artesania_variants) ? $artesania->artesania_variants->toArray() : []);
        @endphp

        @forelse ($variants as $index => $variant)
            <div class="variant-item grid grid-cols-1 md:grid-cols-6 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                {{-- ID oculto --}}
                @if (isset($variant['id']) || isset($variant->id))
                    <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant['id'] ?? $variant->id }}">
                @endif

                {{-- Nombre Variante --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Variante</label>
                    <input type="text" name="variants[{{ $index }}][variant_name]" placeholder="Ej: Playera Azul"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old("variants.$index.variant_name", $variant['variant_name'] ?? $variant->variant_name ?? '') }}">
                    @error("variants.$index.variant_name")
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Color --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <input type="text" name="variants[{{ $index }}][color]" placeholder="Color"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old("variants.$index.color", $variant['color'] ?? $variant->color ?? '') }}" required>
                </div>

                {{-- Talla --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Talla</label>
                    <input type="text" name="variants[{{ $index }}][size]" placeholder="Talla"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old("variants.$index.size", $variant['size'] ?? $variant->size ?? '') }}">
                </div>

                {{-- Material --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                    <input type="text" name="variants[{{ $index }}][material_variant]" placeholder="Material (opcional)"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old("variants.$index.material_variant", $variant['material_variant'] ?? $variant->material_variant ?? '') }}">
                </div>

                {{-- Stock --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" name="variants[{{ $index }}][stock]" placeholder="Stock" min="0"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old("variants.$index.stock", $variant['stock'] ?? $variant->stock ?? 0) }}" required>
                </div>

                {{-- Ajuste de precio --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ajuste de Precio</label>
                    <input type="number" name="variants[{{ $index }}][price_adjustment]" placeholder="Ajuste $" step="0.01"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old("variants.$index.price_adjustment", $variant['price_adjustment'] ?? $variant->price_adjustment ?? 0.00) }}">
                </div>

                {{-- Imagen --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
                    <input type="file" name="variants[{{ $index }}][image]"
                        class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                    @php
                        $imagePath = $variant['image'] ?? $variant->image ?? null;
                    @endphp
                    @if (!empty($imagePath))
                        <div class="mt-1">
                            <img src="{{ Str::startsWith($imagePath, 'http') ? $imagePath : asset('storage/' . $imagePath) }}"
                                alt="Variante" class="w-16 h-16 object-cover rounded">
                        </div>
                        <label class="inline-flex items-center mt-2 text-xs">
                            <input type="checkbox" name="variants[{{ $index }}][remove_image]" value="1" class="mr-1">
                            Eliminar imagen
                        </label>
                    @endif
                </div>
            </div>
        @empty
            {{-- Variante vacía por defecto --}}
            <div class="variant-item grid grid-cols-1 md:grid-cols-6 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Variante</label>
                    <input type="text" name="variants[0][variant_name]" placeholder="Ej: Variante"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <input type="text" name="variants[0][color]" placeholder="Color"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Talla</label>
                    <input type="text" name="variants[0][size]" placeholder="Talla"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                    <input type="text" name="variants[0][material_variant]" placeholder="Material"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" name="variants[0][stock]" placeholder="Stock" min="0"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ajuste de Precio</label>
                    <input type="number" name="variants[0][price_adjustment]" placeholder="Ajuste $" step="0.01"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
                    <input type="file" name="variants[0][image]"
                        class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                </div>
            </div>
        @endforelse
    </div>
</div>


                    {{-- Campo Imagen Principal --}}
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700">Imagen Principal Actual:</label>
                        
                        @if ($artesania->imagen_principal)
                    
                            <img src="{{ Storage::url($artesania->imagen_principal) }}" alt="Imagen Principal" class="mt-2 h-48 w-auto object-cover rounded-md shadow-md">
                        @else
                            <p class="mt-2 text-gray-500">No hay imagen principal subida.</p>
                        @endif

                        <label for="imagen_principal" class="block text-sm font-medium text-gray-700 mt-4">Cambiar Imagen Principal:</label>
                        <input type="file" name="imagen_principal" id="imagen_principal"
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-sm text-gray-500">Deja en blanco para mantener la imagen actual.</p>
                        @error('imagen_principal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo Imágenes Adicionales (múltiples archivos) --}}
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700">Imágenes Adicionales Actuales:</label>
     
                        @php
                    $imagenes = $artesania->imagen_adicionales;

                    if (is_string($imagenes)) {
                        $imagenes = json_decode($imagenes, true);
                    }

                    $imagenes = is_array($imagenes) ? $imagenes : [];
                @endphp

                @if (count($imagenes))
                    <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach ($imagenes as $imagePath)

                                    <div class="relative">
                                        {{-- Asegurarse de que $imagePath no sea nulo/vacío si algo falló --}}
                                        @if ($imagePath)
                                            <img src="{{ Storage::url($imagePath) }}" alt="Imagen Adicional" class="h-32 w-full object-cover rounded-md shadow-md">
                                        @else
                                            <div class="h-32 w-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs rounded-md">Error de imagen</div>
                                        @endif
                                        {{-- Opcional: botón para eliminar imágenes individuales --}}
                                        {{-- <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 text-xs">X</button> --}}
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-2 text-gray-500">No hay imágenes adicionales subidas.</p>
                        @endif
          
                        <label for="imagen_adicionales" class="block text-sm font-medium text-gray-700 mt-4">Añadir/Reemplazar Imágenes Adicionales:</label>
                        <input type="file" name="imagen_adicionales[]" id="imagen_adicionales" multiple
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-sm text-gray-500">Selecciona nuevos archivos para añadir o reemplazar los existentes.</p>
                        @error('imagen_adicionales')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        @error('imagen_adicionales.*')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-8 flex justify-end space-x-4 border-t pt-6">
                        <a href="{{ route('admin.artesanias.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Actualizar Artesanía
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>