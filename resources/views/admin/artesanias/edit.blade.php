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

                <form id="edit-form" action="{{ route('admin.artesanias.update', $artesania) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Sección de Información General de la Artesanía (Producto Padre) --}}
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

                        {{-- Campo: Precio Base --}}
                        <div>
                            <label for="precio" class="block text-sm font-medium text-gray-700">Precio Base:</label>
                            <input type="number" name="precio" id="precio" step="0.01" value="{{ old('precio', $artesania->precio) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('precio') border-red-500 @enderror">
                            @error('precio')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Campo: Descripción --}}
                    <div class="mb-6">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción General del Tipo de Artesanía:</label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('descripcion') border-red-500 @enderror">{{ old('descripcion', $artesania->descripcion) }}</textarea>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo: Historia/Contexto --}}
                    <div class="mb-6">
                        <label for="historia_piezas_general" class="block text-sm font-medium text-gray-700">Historia o Contexto Cultural General:</label>
                        <textarea name="historia_piezas_general" id="historia_piezas_general" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('historia_piezas_general') border-red-500 @enderror">{{ old('historia_piezas_general', $artesania->historia_piezas_general) }}</textarea>
                        @error('historia_piezas_general')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Sección para imágenes generales existentes --}}
                    <div class="mb-6">
                        <h5 class="text-sm font-medium text-gray-800">Imágenes Generales Existentes</h5>
                        <div id="general-images-container" class="mt-2 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @if(is_array($artesania->imagen_artesanias) && count($artesania->imagen_artesanias) > 0)
                                @foreach($artesania->imagen_artesanias as $imageUrl)
                                    <div class="relative group">
                                        {{-- Se ha simplificado la ruta de la imagen --}}
                                        <img src="{{ asset($imageUrl) }}" alt="Imagen de artesanía" class="w-full h-auto object-cover rounded-md shadow">
                                        <div class="absolute inset-0 bg-red-500 opacity-0 group-hover:opacity-75 transition-opacity duration-300 flex items-center justify-center rounded-md cursor-pointer delete-image-btn" data-image-url="{{ $imageUrl }}">
                                            <span class="text-white text-sm font-bold">Eliminar</span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-500 text-sm">No hay imágenes generales cargadas.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Sección para subir nuevas imágenes generales --}}
                    <div class="col-span-full mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subir Nuevas Imágenes Generales:</label>
                        <input type="file" name="imagenes_artesanias[]" multiple
                               class="w-full text-sm border-gray-300 rounded-md shadow-sm @error('imagenes_artesanias.*') border-red-500 @enderror">
                        @error('imagenes_artesanias.*')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <hr class="my-6">

                    {{-- Sección de Variantes --}}
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Variantes de la Artesanía</h3>
                        <button type="button" id="add-variant" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Añadir Nueva Variante
                        </button>
                    </div>
                    
                    <div id="variants-container">
                        {{-- Secciones de variantes existentes se renderizan aquí --}}
                        @foreach($artesania->variants as $variant)
                            <div class="variant-item p-4 border rounded-md shadow-sm mb-4 bg-gray-50" data-variant-id="{{ $variant->id }}">
                                {{-- Input hidden para el ID de la variante existente --}}
                                <input type="hidden" name="variants[{{ $loop->index }}][id]" value="{{ $variant->id }}">
                                
                                <div class="flex justify-end mb-2">
                                    <button type="button" class="remove-variant text-red-500 hover:text-red-700 text-sm">
                                        Eliminar Variante
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    {{-- Campos de la variante --}}
                                    <div>
                                        <label for="variants[{{ $loop->index }}][sku]" class="block text-sm font-medium text-gray-700">SKU:</label>
                                        <input type="text" name="variants[{{ $loop->index }}][sku]" id="variants[{{ $loop->index }}][sku]" value="{{ old('variants.' . $loop->index . '.sku', $variant->sku) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                    <div>
                                        <label for="variants[{{ $loop->index }}][variant_name]" class="block text-sm font-medium text-gray-700">Nombre de la Variante:</label>
                                        <input type="text" name="variants[{{ $loop->index }}][variant_name]" id="variants[{{ $loop->index }}][variant_name]" value="{{ old('variants.' . $loop->index . '.variant_name', $variant->variant_name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                    <div>
                                        <label for="variants[{{ $loop->index }}][precio]" class="block text-sm font-medium text-gray-700">Precio:</label>
                                        <input type="number" name="variants[{{ $loop->index }}][precio]" id="variants[{{ $loop->index }}][precio]" step="0.01" value="{{ old('variants.' . $loop->index . '.precio', $variant->precio) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                    <div>
                                        <label for="variants[{{ $loop->index }}][stock]" class="block text-sm font-medium text-gray-700">Stock:</label>
                                        <input type="number" name="variants[{{ $loop->index }}][stock]" id="variants[{{ $loop->index }}][stock]" value="{{ old('variants.' . $loop->index . '.stock', $variant->stock) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                    {{-- ... otros campos de la variante --}}
                                </div>

                                {{-- Sección para imágenes de la variante --}}
                                <div class="mt-4">
                                    <h6 class="text-sm font-medium text-gray-800">Imágenes de la Variante</h6>
                                    <div class="mt-2 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                        {{-- Imágenes existentes de la variante --}}
                                        @if(is_array($variant->imagen_variant) && count($variant->imagen_variant) > 0)
                                            @foreach($variant->imagen_variant as $imageUrl)
                                                <div class="relative group">
                                                    {{-- Se ha simplificado la ruta de la imagen --}}
                                                    <img src="{{ asset($imageUrl) }}" alt="Imagen de variante" class="w-full h-auto object-cover rounded-md shadow">
                                                    <div class="absolute inset-0 bg-red-500 opacity-0 group-hover:opacity-75 transition-opacity duration-300 flex items-center justify-center rounded-md cursor-pointer delete-variant-image-btn" data-variant-id="{{ $variant->id }}" data-image-url="{{ $imageUrl }}">
                                                        <span class="text-white text-sm font-bold">Eliminar</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-gray-500 text-xs">No hay imágenes para esta variante.</p>
                                        @endif
                                    </div>
                                    {{-- Campo para subir nuevas imágenes de la variante --}}
                                    <div class="mt-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Subir Nuevas Imágenes:</label>
                                        <input type="file" name="variants[{{ $loop->index }}][imagenes_variant][]" multiple
                                            class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                                    </div>
                                </div>

                                {{-- Sección de atributos de la variante --}}
                                <div class="mt-4 border-t pt-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h6 class="text-sm font-medium text-gray-800">Atributos de la Variante</h6>
                                        <button type="button" class="add-attribute-button px-3 py-1 bg-gray-200 text-gray-700 text-xs rounded-md hover:bg-gray-300 focus:outline-none" data-variant-index="{{ $loop->index }}">
                                            Añadir Atributo
                                        </button>
                                    </div>
                                    <div id="attributes-container-{{ $loop->index }}" class="space-y-2">
                                        @foreach($variant->atributos as $attribute)
                                            <div class="attribute-item grid grid-cols-2 gap-2">
                                                {{-- Se han actualizado los nombres de los campos --}}
                                                <input type="text" name="variants[{{ $loop->parent->index }}][attributes][{{ $loop->index }}][name]" value="{{ old('variants.' . $loop->parent->index . '.attributes.' . $loop->index . '.name', $attribute->atributo->nombre) }}" placeholder="Nombre del Atributo" class="block w-full text-sm border-gray-300 rounded-md shadow-sm">
                                                <div class="flex items-center space-x-2">
                                                    {{-- Se han actualizado los nombres de los campos --}}
                                                    <input type="text" name="variants[{{ $loop->parent->index }}][attributes][{{ $loop->index }}][value]" value="{{ old('variants.' . $loop->parent->index . '.attributes.' . $loop->index . '.value', $attribute->valor) }}" placeholder="Valor del Atributo" class="block w-full text-sm border-gray-300 rounded-md shadow-sm">
                                                    <button type="button" class="remove-attribute-button text-red-500 hover:text-red-700 text-sm">
                                                        &times;
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <a href="{{ route('admin.artesanias.index') }}" class="text-sm font-semibold leading-6 text-gray-900">Cancelar</a>
                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
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
            const form = document.getElementById('edit-form');
            const generalImagesContainer = document.getElementById('general-images-container');
            const variantsContainer = document.getElementById('variants-container');
            const addVariantButton = document.getElementById('add-variant');
            let variantIndex = variantsContainer.children.length;

            /**
             * Genera el HTML para un nuevo campo de variante.
             * @param {number} index El índice para el nombre de los campos.
             * @returns {string} El HTML como string.
             */
            function createVariantHtml(index) {
                return `
                    <div class="variant-item p-4 border rounded-md shadow-sm mb-4 bg-gray-50">
                        <div class="flex justify-end mb-2">
                            <button type="button" class="remove-variant text-red-500 hover:text-red-700 text-sm">
                                Eliminar Variante
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label for="variants[${index}][sku]" class="block text-sm font-medium text-gray-700">SKU:</label>
                                <input type="text" name="variants[${index}][sku]" id="variants[${index}][sku]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="variants[${index}][variant_name]" class="block text-sm font-medium text-gray-700">Nombre de la Variante:</label>
                                <input type="text" name="variants[${index}][variant_name]" id="variants[${index}][variant_name]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="variants[${index}][precio]" class="block text-sm font-medium text-gray-700">Precio:</label>
                                <input type="number" name="variants[${index}][precio]" id="variants[${index}][precio]" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="variants[${index}][stock]" class="block text-sm font-medium text-gray-700">Stock:</label>
                                <input type="number" name="variants[${index}][stock]" id="variants[${index}][stock]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="variants[${index}][is_active]" class="block text-sm font-medium text-gray-700">Estado:</label>
                                <select name="variants[${index}][is_active]" id="variants[${index}][is_active]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label for="variants[${index}][description_variant]" class="block text-sm font-medium text-gray-700">Descripción:</label>
                                <textarea name="variants[${index}][description_variant]" id="variants[${index}][description_variant]" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h6 class="text-sm font-medium text-gray-800">Imágenes de la Variante</h6>
                            <div class="mt-2">
                                <input type="file" name="variants[${index}][imagenes_variant][]" multiple class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                        <div class="mt-4 border-t pt-4">
                            <div class="flex items-center justify-between mb-2">
                                <h6 class="text-sm font-medium text-gray-800">Atributos de la Variante</h6>
                                <button type="button" class="add-attribute-button px-3 py-1 bg-gray-200 text-gray-700 text-xs rounded-md hover:bg-gray-300 focus:outline-none" data-variant-index="${index}">
                                    Añadir Atributo
                                </button>
                            </div>
                            <div id="attributes-container-${index}" class="space-y-2">
                                {{-- Los campos de atributo se añadirán aquí dinámicamente --}}
                            </div>
                        </div>
                    </div>
                `;
            }

            /**
             * Genera el HTML para un nuevo campo de atributo.
             * @param {number} variantIndex El índice de la variante padre.
             * @param {number} attributeIndex El índice para el nombre de los campos.
             * @returns {string} El HTML como string.
             */
            function createAttributeHtml(variantIndex, attributeIndex) {
                return `
                    <div class="attribute-item grid grid-cols-2 gap-2">
                        <input type="text" name="variants[${variantIndex}][attributes][${attributeIndex}][name]" placeholder="Nombre del Atributo" class="block w-full text-sm border-gray-300 rounded-md shadow-sm">
                        <div class="flex items-center space-x-2">
                            <input type="text" name="variants[${variantIndex}][attributes][${attributeIndex}][value]" placeholder="Valor del Atributo" class="block w-full text-sm border-gray-300 rounded-md shadow-sm">
                            <button type="button" class="remove-attribute-button text-red-500 hover:text-red-700 text-sm">
                                &times;
                            </button>
                        </div>
                    </div>
                `;
            }

            // Event listener para añadir una variante
            addVariantButton.addEventListener('click', function () {
                variantsContainer.insertAdjacentHTML('beforeend', createVariantHtml(variantIndex));
                variantIndex++;
            });

            // Event listener delegado para manejar la eliminación de variantes, atributos e imágenes de variante
            variantsContainer.addEventListener('click', function (e) {
                const removeVariantButton = e.target.closest('.remove-variant');
                if (removeVariantButton) {
                    const variantItem = removeVariantButton.closest('.variant-item');
                    if (variantItem) {
                        variantItem.remove();
                        reindexVariants();
                    }
                    return;
                }

                const addAttributeButton = e.target.closest('.add-attribute-button');
                if (addAttributeButton) {
                    const variantIdx = addAttributeButton.getAttribute('data-variant-index');
                    const attributesContainer = document.getElementById(`attributes-container-${variantIdx}`);
                    const attributeIndex = attributesContainer.children.length;
                    attributesContainer.insertAdjacentHTML('beforeend', createAttributeHtml(variantIdx, attributeIndex));
                    return;
                }

                const removeAttributeButton = e.target.closest('.remove-attribute-button');
                if (removeAttributeButton) {
                    const attributeItem = removeAttributeButton.closest('.attribute-item');
                    if (attributeItem) {
                        attributeItem.remove();
                    }
                    return;
                }

                const deleteVariantImageBtn = e.target.closest('.delete-variant-image-btn');
                if (deleteVariantImageBtn) {
                    const imageUrl = deleteVariantImageBtn.dataset.imageUrl;
                    const variantId = deleteVariantImageBtn.dataset.variantId;
                    
                    // Crea un input hidden para marcar la imagen de la variante para su eliminación
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `delete_variant_images[${variantId}][]`;
                    hiddenInput.value = imageUrl;
                    form.appendChild(hiddenInput);

                    // Elimina el contenedor visual de la imagen
                    deleteVariantImageBtn.closest('.relative.group').remove();
                }
            });

            /**
             * Función para reindexar los campos de las variantes restantes.
             */
            function reindexVariants() {
                const variantItems = variantsContainer.querySelectorAll('.variant-item');
                variantItems.forEach((item, newIndex) => {
                    const inputs = item.querySelectorAll('[name^="variants["]');
                    inputs.forEach(input => {
                        const oldName = input.name;
                        const newName = oldName.replace(/variants\[\d+\]/, `variants[${newIndex}]`);
                        input.name = newName;
                    });
                    
                    const addAttributeBtn = item.querySelector('.add-attribute-button');
                    if (addAttributeBtn) {
                        addAttributeBtn.setAttribute('data-variant-index', newIndex);
                    }
                    
                    const attributesContainer = item.querySelector('[id^="attributes-container-"]');
                    if (attributesContainer) {
                        attributesContainer.id = `attributes-container-${newIndex}`;
                    }
                });
                variantIndex = variantItems.length;
            }

            // Manejo de la eliminación de imágenes generales existentes
            document.addEventListener('click', function (e) {
                const deleteBtn = e.target.closest('.delete-image-btn');
                if (deleteBtn) {
                    const imageUrl = deleteBtn.dataset.imageUrl;
                    
                    // Crea un input hidden para marcar la imagen para su eliminación
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'delete_general_images[]';
                    hiddenInput.value = imageUrl;
                    form.appendChild(hiddenInput);

                    // Elimina el contenedor visual de la imagen
                    deleteBtn.closest('.relative.group').remove();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
