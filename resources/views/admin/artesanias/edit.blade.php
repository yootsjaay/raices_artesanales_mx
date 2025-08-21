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

                    {{-- Sección para imágenes generales --}}
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

                    {{-- Sección de Variantes --}}
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Variantes de la Artesanía</h3>
                        <button type="button" id="add-variant" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Añadir Nueva Variante
                        </button>
                    </div>
                    
                    <div id="variants-container">
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
                                {{-- Sección para atributos de la variante --}}
                                <div class="mt-4">
                                    <h5 class="text-sm font-medium text-gray-800 flex items-center justify-between">
                                        Atributos de la Variante
                                        <button type="button" class="add-attribute-button text-sm px-2 py-1 bg-gray-200 rounded-md" data-variant-index="{{ $loop->index }}">
                                            + Atributo
                                        </button>
                                    </h5>
                                    <div class="attributes-container mt-2" id="attributes-container-{{ $loop->index }}">
                                        @foreach($variant->atributos as $attribute)
                                            <div class="flex gap-2 items-center attribute-item">
                                                <input type="hidden" name="variants[{{ $loop->parent->index }}][attributes][{{ $loop->index }}][id]" value="{{ $attribute->id }}">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-500">Atributo Existente</label>
                                                    <select name="variants[{{ $loop->parent->index }}][attributes][{{ $loop->index }}][atributo_id]" class="block w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                        <option value="">Selecciona</option>
                                                        @foreach ($atributos as $attr)
                                                            <option value="{{ $attr->id }}" {{ old('variants.' . $loop->parent->index . '.attributes.' . $loop->index . '.atributo_id', $attribute->atributo_id) == $attr->id ? 'selected' : '' }}>
                                                                {{ $attr->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-500">Valor</label>
                                                    <input type="text" name="variants[{{ $loop->parent->index }}][attributes][{{ $loop->index }}][valor]" placeholder="Valor"
                                                        value="{{ old('variants.' . $loop->parent->index . '.attributes.' . $loop->index . '.valor', $attribute->valor) }}" class="block w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                                                </div>
                                                <button type="button" class="remove-attribute-button text-red-400 hover:text-red-600 text-xs self-center mt-4">X</button>
                                            </div>
                                        @endforeach
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
            
            // Inicializar el índice de la variante basado en las variantes existentes
            let variantIndex = {{ $artesania->variants->count() }};
            const tiposEmbalaje = @json($tipos_embalaje);
            const atributosExistentes = @json($atributos);
            
            // Función para generar el HTML de un nuevo atributo
            function createAttributeHtml(currentVariantIndex, attributeIndex) {
                const existingAttributesOptions = atributosExistentes.map(attr => `<option value="${attr.id}">${attr.nombre}</option>`).join('');
                return `
                    <div class="flex gap-2 items-center attribute-item">
                        <input type="hidden" name="variants[${currentVariantIndex}][attributes][${attributeIndex}][id]" value="">
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Atributo Existente</label>
                            <select name="variants[${currentVariantIndex}][attributes][${attributeIndex}][atributo_id]" class="block w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">Selecciona</option>
                                ${existingAttributesOptions}
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Valor</label>
                            <input type="text" name="variants[${currentVariantIndex}][attributes][${attributeIndex}][valor]" placeholder="Valor"
                                class="block w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                        </div>
                        <button type="button" class="remove-attribute-button text-red-400 hover:text-red-600 text-xs self-center mt-4">X</button>
                    </div>
                `;
            }

            // Función para generar el HTML de una nueva variante
            function createVariantHtml(index) {
                const embalajeOptionsHtml = tiposEmbalaje.map(tipo => `<option value="${tipo.id}">${tipo.nombre}</option>`).join('');
                return `
                    <div class="variant-item p-4 border rounded-md shadow-sm mb-4 bg-gray-50">
                        <input type="hidden" name="variants[${index}][id]" value="">
                        <div class="flex justify-end mb-2">
                            <button type="button" class="remove-variant text-red-500 hover:text-red-700 text-sm">
                                Eliminar Variante
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label for="variants[${index}][sku]" class="block text-sm font-medium text-gray-700">SKU:</label>
                                <input type="text" name="variants[${index}][sku]" id="variants[${index}][sku]"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="variants[${index}][variant_name]" class="block text-sm font-medium text-gray-700">Nombre de la Variante:</label>
                                <input type="text" name="variants[${index}][variant_name]" id="variants[${index}][variant_name]"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="variants[${index}][precio]" class="block text-sm font-medium text-gray-700">Precio:</label>
                                <input type="number" name="variants[${index}][precio]" id="variants[${index}][precio]" step="0.01" value="0.00"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label for="variants[${index}][stock]" class="block text-sm font-medium text-gray-700">Stock:</label>
                                <input type="number" name="variants[${index}][stock]" id="variants[${index}][stock]" value="0"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="variants[${index}][peso_item_kg]" class="block text-sm font-medium text-gray-700">Peso del Item (KG):</label>
                                <input type="number" name="variants[${index}][peso_item_kg]" id="variants[${index}][peso_item_kg]" step="0.01" value="0.00"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="variants[${index}][tipo_embalaje_id]" class="block text-sm font-medium text-gray-700">Tipo de Embalaje:</label>
                                <select name="variants[${index}][tipo_embalaje_id]" id="variants[${index}][tipo_embalaje_id]"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Seleccione un embalaje</option>
                                    ${embalajeOptionsHtml}
                                </select>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="variants[${index}][is_active]" value="1" checked
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                <label class="ml-2 block text-sm font-medium text-gray-700">Activa</label>
                            </div>
                        </div>
                        <div class="col-span-full mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subir Nuevas Imágenes a esta Variante:</label>
                            <input type="file" name="variants[${index}][new_variant_images][]" multiple
                                class="w-full text-sm border-gray-300 rounded-md shadow-sm new-variant-image-input">
                            <div class="mt-2 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 image-preview-container">
                            </div>
                        </div>
                        {{-- Sección de atributos para la variante --}}
                        <div class="mt-4">
                            <h5 class="text-sm font-medium text-gray-800 flex items-center justify-between">
                                Atributos de la Variante
                                <button type="button" class="add-attribute-button text-sm px-2 py-1 bg-gray-200 rounded-md" data-variant-index="${index}">
                                    + Atributo
                                </button>
                            </h5>
                            <div class="attributes-container mt-2" id="attributes-container-${index}">
                            </div>
                        </div>
                    </div>
                `;
            }

            // Event listener para añadir una variante
            addVariantButton.addEventListener('click', function () {
                variantsContainer.insertAdjacentHTML('beforeend', createVariantHtml(variantIndex));
                variantIndex++;
            });
            
            // Event listener delegada para manejar la eliminación de variantes y atributos
            variantsContainer.addEventListener('click', function (e) {
                // Eliminar variante
                if (e.target.classList.contains('remove-variant')) {
                    const variantItem = e.target.closest('.variant-item');
                    if (variantItem) {
                        variantItem.remove();
                    }
                // Añadir atributo
                } else if (e.target.classList.contains('add-attribute-button')) {
                    const variantIdx = e.target.getAttribute('data-variant-index');
                    const containerId = `attributes-container-${variantIdx}`;
                    const attributesContainer = document.getElementById(containerId);
                    if (attributesContainer) {
                        const attributeCount = attributesContainer.children.length;
                        attributesContainer.insertAdjacentHTML('beforeend', createAttributeHtml(variantIdx, attributeCount));
                    }
                // Eliminar atributo
                } else if (e.target.classList.contains('remove-attribute-button')) {
                    const attributeItem = e.target.closest('.attribute-item');
                    if (attributeItem) {
                        attributeItem.remove();
                    }
                }
            });

            // Event listener para la previsualización de imágenes en nuevas variantes
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

            // Event listener delegada para eliminar las previsualizaciones de nuevas imágenes
            variantsContainer.addEventListener('click', function (e) {
                const removeBtn = e.target.closest('.remove-preview-image');
                if (removeBtn) {
                    const previewItem = removeBtn.closest('.relative.group');
                    const previewContainer = previewItem.closest('.image-preview-container');
                    const input = previewContainer.previousElementSibling;
                    const fileIndex = parseInt(previewItem.dataset.file-index);
                    
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
