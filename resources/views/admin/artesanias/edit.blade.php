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

                <form action="{{ route('admin.artesanias.update', $artesania) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre General:</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $artesania->nombre) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nombre') border-red-500 @enderror" required>
                            @error('nombre')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

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

                        <div>
                            <label for="precio" class="block text-sm font-medium text-gray-700">Precio Base:</label>
                            <input type="number" name="precio" id="precio" step="0.01" value="{{ old('precio', $artesania->precio) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('precio') border-red-500 @enderror">
                            @error('precio')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción General del Tipo de Artesanía:</label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $artesania->descripcion) }}</textarea>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="historia_piezas_general" class="block text-sm font-medium text-gray-700">Historia o Contexto Cultural General:</label>
                        <textarea name="historia_piezas_general" id="historia_piezas_general" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('historia_piezas_general') border-red-500 @enderror">{{ old('historia_piezas_general', $artesania->historia_piezas_general) }}</textarea>
                        @error('historia_piezas_general')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <h5 class="text-sm font-medium text-gray-800">Imágenes Generales Existentes</h5>
                        <div class="mt-2 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @if(is_array($artesania->imagen_artesanias))
                                @foreach($artesania->imagen_artesanias as $imageUrl)
                                    <div class="relative group">
                                        <img src="{{ asset(str_replace('storage/', '', $imageUrl)) }}" alt="Imagen de artesanía" class="w-full h-auto object-cover rounded-md shadow">
                                        <div class="absolute inset-0 bg-red-500 opacity-0 group-hover:opacity-75 transition-opacity duration-300 flex items-center justify-center rounded-md">
                                            <label class="text-white text-sm font-bold cursor-pointer">
                                                Eliminar
                                                <input type="checkbox" name="delete_general_images[]" value="{{ $imageUrl }}" class="hidden">
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="col-span-full mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subir Nuevas Imágenes Generales:</label>
                        <input type="file" name="imagenes_artesanias[]" multiple
                               class="w-full text-sm border-gray-300 rounded-md shadow-sm @error('imagenes_artesanias.*') border-red-500 @enderror">
                        @error('imagenes_artesanias.*')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <hr class="my-6">

                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Variantes de la Artesanía</h3>
                        <button type="button" id="add-variant" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Añadir Nueva Variante
                        </button>
                    </div>
                    
                    <div id="variants-container">
                        @foreach($artesania->variants as $variant)
                            <div class="variant-item p-4 border rounded-md shadow-sm mb-4 bg-gray-50" data-variant-id="{{ $variant->id }}">
                                <input type="hidden" name="variants[{{ $loop->index }}][id]" value="{{ $variant->id }}">
                                
                                <div class="flex justify-end mb-2">
                                    <button type="button" class="remove-variant text-red-500 hover:text-red-700 text-sm">
                                        Eliminar Variante
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div>
                                        <label for="variants[{{ $loop->index }}][sku]" class="block text-sm font-medium text-gray-700">SKU:</label>
                                        <input type="text" name="variants[{{ $loop->index }}][sku]" id="variants[{{ $loop->index }}][sku]" value="{{ old('variants.' . $loop->index . '.sku', $variant->sku) }}"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
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
                                    <div>
                                        <label for="variants[{{ $loop->index }}][peso_item_kg]" class="block text-sm font-medium text-gray-700">Peso del Item (KG):</label>
                                        <input type="number" name="variants[{{ $loop->index }}][peso_item_kg]" id="variants[{{ $loop->index }}][peso_item_kg]" step="0.01" value="{{ old('variants.' . $loop->index . '.peso_item_kg', $variant->peso_item_kg) }}"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        @error('variants.' . $loop->index . '.peso_item_kg')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
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
                                <div class="col-span-full mt-4">
                                    <h5 class="text-sm font-medium text-gray-800">Imágenes de la Variante Existentes</h5>
                                    <div class="mt-2 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                        @if(is_array($variant->imagen_variant))
                                            @foreach($variant->imagen_variant as $variantImageUrl)
                                                <div class="relative group">
                                                    <img src="{{ asset(str_replace('storage/', '', $variantImageUrl)) }}" alt="Imagen de variante" class="w-full h-auto object-cover rounded-md shadow">
                                                    <div class="absolute inset-0 bg-red-500 opacity-0 group-hover:opacity-75 transition-opacity duration-300 flex items-center justify-center rounded-md">
                                                        <label class="text-white text-sm font-bold cursor-pointer">
                                                            Eliminar
                                                            <input type="checkbox" name="delete_variant_images[{{ $variant->id }}][]" value="{{ $variantImageUrl }}" class="hidden">
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="col-span-full mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Subir Nuevas Imágenes a esta Variante:</label>
                                    <input type="file" name="variants[{{ $loop->index }}][new_variant_images][]" multiple
                                            class="w-full text-sm border-gray-300 rounded-md shadow-sm new-variant-image-input">
                                    <div class="mt-2 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 image-preview-container">
                                        </div>
                                </div>
                            </div>
                        @endforeach
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
                            <button type="button" class="remove-variant text-red-500 hover:text-red-700 text-sm">
                                Eliminar Variante
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label for="variants[${variantIndex}][sku]" class="block text-sm font-medium text-gray-700">SKU:</label>
                                <input type="text" name="variants[${variantIndex}][sku]" id="variants[${variantIndex}][sku]"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
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
                            <div>
                                <label for="variants[${variantIndex}][peso_item_kg]" class="block text-sm font-medium text-gray-700">Peso del Item (KG):</label>
                                <input type="number" name="variants[${variantIndex}][peso_item_kg]" id="variants[${variantIndex}][peso_item_kg]" step="0.01"
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
                        </div>
                        <div class="col-span-full mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subir Nuevas Imágenes a esta Variante:</label>
                            <input type="file" name="variants[${variantIndex}][new_variant_images][]" multiple
                                    class="w-full text-sm border-gray-300 rounded-md shadow-sm new-variant-image-input">
                            <div class="mt-2 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 image-preview-container">
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

            // Event listener para la previsualización y eliminación de imágenes en nuevas variantes
            variantsContainer.addEventListener('change', function (e) {
                if (e.target.classList.contains('new-variant-image-input')) {
                    const input = e.target;
                    const previewContainer = input.nextElementSibling;
                    previewContainer.innerHTML = ''; // Limpiar previsualizaciones anteriores
                    
                    if (input.files && input.files.length > 0) {
                        Array.from(input.files).forEach((file, index) => {
                            const reader = new FileReader();
                            reader.onload = function (event) {
                                const imgHtml = `
                                    <div class="relative group" data-file-index="${index}">
                                        <img src="${event.target.result}" alt="Nueva imagen" class="w-full h-auto object-cover rounded-md shadow">
                                        <div class="absolute inset-0 bg-red-500 opacity-0 group-hover:opacity-75 transition-opacity duration-300 flex items-center justify-center rounded-md cursor-pointer remove-preview-image">
                                            <span class="text-white text-sm font-bold">Eliminar</span>
                                        </div>
                                    </div>
                                `;
                                previewContainer.insertAdjacentHTML('beforeend', imgHtml);
                            };
                            reader.readAsDataURL(file);
                        });
                    }
                }
            });

            variantsContainer.addEventListener('click', function (e) {
                const removeBtn = e.target.closest('.remove-preview-image');
                if (removeBtn) {
                    const previewItem = removeBtn.closest('.relative.group');
                    const previewContainer = previewItem.closest('.image-preview-container');
                    const input = previewContainer.previousElementSibling;
                    const fileIndex = parseInt(previewItem.dataset.fileIndex);
                    
                    // Crear una nueva lista de archivos sin la imagen eliminada
                    const dataTransfer = new DataTransfer();
                    Array.from(input.files)
                        .filter((file, index) => index !== fileIndex)
                        .forEach(file => dataTransfer.items.add(file));
                    
                    input.files = dataTransfer.files;

                    // Eliminar el contenedor de la imagen de la vista
                    previewItem.remove();

                    // Actualizar los índices de las imágenes restantes en la previsualización
                    Array.from(previewContainer.children).forEach((child, index) => {
                        child.dataset.fileIndex = index;
                    });
                }
            });
        });
    </script>
    @endpush
</x-app-layout>