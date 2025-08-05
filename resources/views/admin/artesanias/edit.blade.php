<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Artesanía: ') . $artesania->nombre }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-6 sm:p-8">

                <div class="mb-6 border-b pb-4">
                    <h3 class="text-lg font-medium text-gray-900">Información General de la Artesanía</h3>
                    <p class="mt-1 text-sm text-gray-600">Actualiza los detalles del tipo de artesanía.</p>
                </div>

                {{-- Formulario principal para la edición --}}
                <form action="{{ route('admin.artesanias.update', $artesania) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Sección de Información General de la Artesanía --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- Campo: Nombre General --}}
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre General:</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $artesania->nombre) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nombre') border-red-500 @enderror" required>
                            @error('nombre')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo: Categoría --}}
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

                        {{-- Campo: Ubicación --}}
                        <div>
                            <label for="ubicacion_id" class="block text-sm font-medium text-gray-700">Ubicación de Origen/Venta:</label>
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
                    </div>

                    {{-- Campos de Precio, Peso y Dimensiones --}}
                    <div class="mb-6 border-b pb-4">
                        <h3 class="text-lg font-medium text-gray-900">Precio y Dimensiones Generales</h3>
                        <p class="mt-1 text-sm text-gray-600">Define el precio base, peso y dimensiones para la artesanía general.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
                        {{-- Campo: Precio Base --}}
                        <div>
                            <label for="precio" class="block text-sm font-medium text-gray-700">Precio Base:</label>
                            <input type="number" name="precio" id="precio" step="0.01" value="{{ old('precio', $artesania->precio) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('precio') border-red-500 @enderror">
                            @error('precio')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo: Peso (KG) --}}
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700">Peso (KG):</label>
                            <input type="number" name="weight" id="weight" step="0.01" value="{{ old('weight', $artesania->weight) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('weight') border-red-500 @enderror">
                            @error('weight')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo: Largo (CM) --}}
                        <div>
                            <label for="length" class="block text-sm font-medium text-gray-700">Largo (CM):</label>
                            <input type="number" name="length" id="length" step="0.01" value="{{ old('length', $artesania->length) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('length') border-red-500 @enderror">
                            @error('length')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo: Ancho (CM) --}}
                        <div>
                            <label for="width" class="block text-sm font-medium text-gray-700">Ancho (CM):</label>
                            <input type="number" name="width" id="width" step="0.01" value="{{ old('width', $artesania->width) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('width') border-red-500 @enderror">
                            @error('width')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo: Alto (CM) --}}
                        <div>
                            <label for="height" class="block text-sm font-medium text-gray-700">Alto (CM):</label>
                            <input type="number" name="height" id="height" step="0.01" value="{{ old('height', $artesania->height) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('height') border-red-500 @enderror">
                            @error('height')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-span-full">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción General del Tipo de Artesanía:</label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $artesania->descripcion) }}</textarea>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-full">
                        <label for="historia_piezas_general" class="block text-sm font-medium text-gray-700">Historia o Contexto Cultural General:</label>
                        <textarea name="historia_piezas_general" id="historia_piezas_general" rows="3"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('historia_piezas_general') border-red-500 @enderror">{{ old('historia_piezas_general', $artesania->historia_piezas_general) }}</textarea>
                        @error('historia_piezas_general')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Sección de imágenes existentes --}}
                                @if(is_array($artesania->imagen_artesanias))
                    @foreach($artesania->imagen_artesanias as $imageUrl)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . str_replace('storage/', '', $imageUrl)) }}" alt="Imagen de artesanía" class="w-full h-auto object-cover rounded-md shadow">
                            <div class="absolute inset-0 bg-red-500 opacity-0 group-hover:opacity-75 transition-opacity duration-300 flex items-center justify-center rounded-md">
                                <label class="text-white text-sm font-bold cursor-pointer">
                                    Eliminar
                                    <input type="checkbox" name="delete_general_images[]" value="{{ $imageUrl }}" class="hidden">
                                </label>
                            </div>
                        </div>
                    @endforeach
                @endif


                    {{-- Input para subir nuevas imágenes --}}
                    <div class="col-span-full mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subir Nuevas Imágenes Generales:</label>
                        <input type="file" name="imagenes_artesanias[]" multiple
                               class="w-full text-sm border-gray-300 rounded-md shadow-sm @error('imagenes_artesanias.*') border-red-500 @enderror">
                        @error('imagenes_artesanias.*')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <hr class="my-6">

                    {{-- Sección de variantes --}}
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Variantes de la Artesanía</h3>
                        <button type="button" id="add-variant" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Añadir Nueva Variante
                        </button>
                    </div>
                    
                    {{-- Contenedor de variantes existentes --}}
                    <div id="variants-container">
                        @foreach($artesania->variants as $variant)
                            <div class="variant-item p-4 border rounded-md shadow-sm mb-4 bg-gray-50">
                                {{-- Campo oculto para el ID de la variante --}}
                                <input type="hidden" name="variants[{{ $loop->index }}][id]" value="{{ $variant->id }}">

                                {{-- Botón para eliminar la variante existente --}}
                                <div class="flex justify-end mb-2">
                                    <button type="button" class="remove-existing-variant text-red-500 hover:text-red-700 text-sm" data-variant-id="{{ $variant->id }}">
                                        Eliminar Variante
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    {{-- ... Campos de la variante pre-cargados ... --}}
                                    <div>
                                        <label for="variants[{{ $loop->index }}][sku]" class="block text-sm font-medium text-gray-700">SKU:</label>
                                        <input type="text" name="variants[{{ $loop->index }}][sku]" id="variants[{{ $loop->index }}][sku]" value="{{ old('variants.' . $loop->index . '.sku', $variant->sku) }}"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    </div>
                                    <div>
                                        <label for="variants[{{ $loop->index }}][variant_name]" class="block text-sm font-medium text-gray-700">Nombre de la Variante:</label>
                                        <input type="text" name="variants[{{ $loop->index }}][variant_name]" id="variants[{{ $loop->index }}][variant_name]" value="{{ old('variants.' . $loop->index . '.variant_name', $variant->variant_name) }}"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                    <div>
                                        <label for="variants[{{ $loop->index }}][size]" class="block text-sm font-medium text-gray-700">Talla:</label>
                                        <input type="text" name="variants[{{ $loop->index }}][size]" id="variants[{{ $loop->index }}][size]" value="{{ old('variants.' . $loop->index . '.size', $variant->size) }}"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                    <div>
                                        <label for="variants[{{ $loop->index }}][color]" class="block text-sm font-medium text-gray-700">Color:</label>
                                        <input type="text" name="variants[{{ $loop->index }}][color]" id="variants[{{ $loop->index }}][color]" value="{{ old('variants.' . $loop->index . '.color', $variant->color) }}"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                    <div>
                                        <label for="variants[{{ $loop->index }}][material_variant]" class="block text-sm font-medium text-gray-700">Material:</label>
                                        <input type="text" name="variants[{{ $loop->index }}][material_variant]" id="variants[{{ $loop->index }}][material_variant]" value="{{ old('variants.' . $loop->index . '.material_variant', $variant->material_variant) }}"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                    <div>
                                        <label for="variants[{{ $loop->index }}][precio]" class="block text-sm font-medium text-gray-700">Precio:</label>
                                        <input type="number" name="variants[{{ $loop->index }}][precio]" id="variants[{{ $loop->index }}][precio]" step="0.01" value="{{ old('variants.' . $loop->index . '.precio', $variant->precio) }}"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    </div>
                                    <div>
                                        <label for="variants[{{ $loop->index }}][stock]" class="block text-sm font-medium text-gray-700">Stock:</label>
                                        <input type="number" name="variants[{{ $loop->index }}][stock]" id="variants[{{ $loop->index }}][stock]" value="{{ old('variants.' . $loop->index . '.stock', $variant->stock) }}"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                    <div class="col-span-full">
                                        <label for="variants[{{ $loop->index }}][description_variant]" class="block text-sm font-medium text-gray-700">Descripción de la Variante:</label>
                                        <textarea name="variants[{{ $loop->index }}][description_variant]" id="variants[{{ $loop->index }}][description_variant]" rows="2"
                                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('variants.' . $loop->index . '.description_variant', $variant->description_variant) }}</textarea>
                                    </div>
                                    <div>
                                        <label for="variants[{{ $loop->index }}][tipo_embalaje_id]" class="block text-sm font-medium text-gray-700">Tipo de Embalaje:</label>
                                        <select name="variants[{{ $loop->index }}][tipo_embalaje_id]" id="variants[{{ $loop->index }}][tipo_embalaje_id]"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                            <option value="">Seleccione un embalaje</option>
                                            @foreach ($tipos_embalaje as $tipo)
                                                <option value="{{ $tipo->id }}" {{ old('variants.' . $loop->index . '.tipo_embalaje_id', $variant->tipo_embalaje_id) == $tipo->id ? 'selected' : '' }}>
                                                    {{ $tipo->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="variants[{{ $loop->index }}][is_active]" value="1"
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm" {{ old('variants.' . $loop->index . '.is_active', $variant->is_active) ? 'checked' : '' }}>
                                        <label class="ml-2 block text-sm font-medium text-gray-700">Activa</label>
                                    </div>
                                </div>
                                {{-- Sección de imágenes existentes de la variante --}}
                                <div class="col-span-full mt-4">
                                    <h5 class="text-sm font-medium text-gray-800">Imágenes de la Variante Existentes</h5>
                                    <div class="mt-2 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                        @foreach(($variant->imagen_variant) as $variantImageUrl)
                                            <div class="relative group">
                                                <img src="{{ asset('storage/' . str_replace('storage/', '', $variantImageUrl)) }}" alt="Imagen de variante" class="w-full h-auto object-cover rounded-md shadow">
                                                <div class="absolute inset-0 bg-red-500 opacity-0 group-hover:opacity-75 transition-opacity duration-300 flex items-center justify-center rounded-md">
                                                    <label class="text-white text-sm font-bold cursor-pointer">
                                                        Eliminar
                                                        <input type="checkbox" name="delete_variant_images[{{ $variant->id }}][]" value="{{ $variantImageUrl }}" class="hidden">
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                {{-- Input para subir nuevas imágenes a esta variante --}}
                                <div class="col-span-full mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Subir Nuevas Imágenes a esta Variante:</label>
                                    <input type="file" name="variants[{{ $loop->index }}][new_variant_images][]" multiple
                                           class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                                </div>
                            </div>
                        @endforeach

                        {{-- Aquí se añadirán las nuevas variantes dinámicamente --}}
                    </div>
                    
                    <div class="flex justify-end mt-6">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Actualizar Artesanía
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
            let variantIndex = {{ $artesania->variants->count() }};
            const tiposEmbalaje = @json($tipos_embalaje);

            addVariantButton.addEventListener('click', function () {
                const embalajeOptions = tiposEmbalaje.map(tipo => `<option value="${tipo.id}">${tipo.nombre}</option>`).join('');

                const variantHtml = `
                    <div class="variant-item p-4 border rounded-md shadow-sm mb-4 bg-gray-50">
                        <div class="flex justify-end mb-2">
                             <button type="button" class="remove-new-variant text-red-500 hover:text-red-700 text-sm">
                                Eliminar Variante
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            {{-- ... Campos de la nueva variante ... --}}
                            <div>
                                <label for="variants[${variantIndex}][sku]" class="block text-sm font-medium text-gray-700">SKU:</label>
                                <input type="text" name="variants[${variantIndex}][sku]" id="variants[${variantIndex}][sku]"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label for="variants[${variantIndex}][variant_name]" class="block text-sm font-medium text-gray-700">Nombre de la Variante:</label>
                                <input type="text" name="variants[${variantIndex}][variant_name]" id="variants[${variantIndex}][variant_name]"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="variants[${variantIndex}][size]" class="block text-sm font-medium text-gray-700">Talla:</label>
                                <input type="text" name="variants[${variantIndex}][size]" id="variants[${variantIndex}][size]"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="variants[${variantIndex}][color]" class="block text-sm font-medium text-gray-700">Color:</label>
                                <input type="text" name="variants[${variantIndex}][color]" id="variants[${variantIndex}][color]"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="variants[${variantIndex}][material_variant]" class="block text-sm font-medium text-gray-700">Material:</label>
                                <input type="text" name="variants[${variantIndex}][material_variant]" id="variants[${variantIndex}][material_variant]"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="variants[${variantIndex}][precio]" class="block text-sm font-medium text-gray-700">Precio:</label>
                                <input type="number" name="variants[${variantIndex}][precio]" id="variants[${variantIndex}][precio]" step="0.01"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label for="variants[${variantIndex}][stock]" class="block text-sm font-medium text-gray-700">Stock:</label>
                                <input type="number" name="variants[${variantIndex}][stock]" id="variants[${variantIndex}][stock]"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="col-span-full">
                                <label for="variants[${variantIndex}][description_variant]" class="block text-sm font-medium text-gray-700">Descripción de la Variante:</label>
                                <textarea name="variants[${variantIndex}][description_variant]" id="variants[${variantIndex}][description_variant]" rows="2"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            </div>
                            <div>
                                <label for="variants[${variantIndex}][tipo_embalaje_id]" class="block text-sm font-medium text-gray-700">Tipo de Embalaje:</label>
                                <select name="variants[${variantIndex}][tipo_embalaje_id]" id="variants[${variantIndex}][tipo_embalaje_id]"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Seleccione un embalaje</option>
                                    ${embalajeOptions}
                                </select>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="variants[${variantIndex}][is_active]" value="1"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm" checked>
                                <label class="ml-2 block text-sm font-medium text-gray-700">Activa</label>
                            </div>
                            <div class="col-span-full mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Imágenes de la Variante (Principal y Adicionales)</label>
                                <input type="file" name="variants[${variantIndex}][new_variant_images][]" multiple
                                       class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                    </div>
                `;
                variantsContainer.insertAdjacentHTML('beforeend', variantHtml);
                variantIndex++;
            });
            
            // Lógica para eliminar variantes nuevas
            variantsContainer.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-new-variant')) {
                    e.target.closest('.variant-item').remove();
                }
            });
            
            // Lógica para marcar variantes existentes para eliminación
            document.querySelectorAll('.remove-existing-variant').forEach(button => {
                button.addEventListener('click', function() {
                    const variantId = this.dataset.variantId;
                    const confirmation = confirm('¿Estás seguro de que quieres eliminar esta variante? Esto no se puede deshacer.');
                    if (confirmation) {
                        // Puedes enviar una solicitud AJAX aquí o marcar un input hidden para que el controlador lo procese.
                        // Por simplicidad, aquí lo ocultamos y lo marcamos para que el controlador lo elimine.
                        const variantItem = this.closest('.variant-item');
                        variantItem.style.display = 'none';
                        variantItem.innerHTML += `<input type="hidden" name="variants_to_delete[]" value="${variantId}">`;
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
