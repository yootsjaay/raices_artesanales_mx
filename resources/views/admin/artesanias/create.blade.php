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
                    <h3 class="text-lg font-semibold text-gray-900">Información General de la Artesanía</h3>
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
                        
                        {{-- Campo: Precio Base --}}
                        <div>
                            <label for="precio" class="block text-sm font-medium text-gray-700">Precio Base:</label>
                            <input type="number" name="precio" id="precio" step="0.01" value="{{ old('precio') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('precio') border-red-500 @enderror">
                            @error('precio')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción General del Tipo de Artesanía:</label>
                        <textarea name="descripcion" id="descripcion" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('descripcion') border-red-500 @enderror">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
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
                        <h3 class="text-lg font-semibold text-gray-900">Variantes de la Artesanía</h3>
                        <button type="button" id="add-variant" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Añadir Variante
                        </button>
                    </div>

                    <div id="variants-container">
                        @if (old('variants'))
                            @foreach(old('variants') as $variantIndex => $oldVariant)
                                <div class="variant-item p-4 border rounded-md shadow-sm mb-4 bg-gray-50 relative">
                                    <div class="absolute top-2 right-2">
                                        <button type="button" class="remove-variant text-red-500 hover:text-red-700 text-sm">Eliminar</button>
                                    </div>
                                    <h4 class="font-semibold text-gray-800 mb-4">Variante #{{ $variantIndex + 1 }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                        {{-- Campo: Nombre de Variante --}}
                                        <div>
                                            <label for="variants[{{ $variantIndex }}][variant_name]" class="block text-sm font-medium text-gray-700">Nombre de la Variante:</label>
                                            <input type="text" name="variants[{{ $variantIndex }}][variant_name]" id="variants[{{ $variantIndex }}][variant_name]" value="{{ $oldVariant['variant_name'] }}"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('variants.' . $variantIndex . '.variant_name') border-red-500 @enderror">
                                            @error('variants.' . $variantIndex . '.variant_name')
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
                                        <div class="flex items-center self-end">
                                            <input type="checkbox" name="variants[{{ $variantIndex }}][is_active]" value="1"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                {{ old('variants.' . $variantIndex . '.is_active', 1) ? 'checked' : '' }}>
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

                                        {{-- Sección para añadir atributos a la variante --}}
                                        <div class="col-span-full mt-4">
                                            <div class="flex items-center justify-between">
                                                <h4 class="text-sm font-semibold text-gray-800">Atributos (Opcional)</h4>
                                                <button type="button" class="add-attribute-button px-3 py-1 bg-gray-200 text-gray-700 text-xs rounded-md hover:bg-gray-300" data-variant-index="{{ $variantIndex }}">
                                                    Añadir Atributo
                                                </button>
                                            </div>
                                            <div class="attributes-container mt-2 space-y-2" id="attributes-container-{{ $variantIndex }}">
                                                @if (isset($oldVariant['attributes']))
                                                    @foreach($oldVariant['attributes'] as $attributeIndex => $oldAttribute)
                                                        <div class="flex gap-2 items-center attribute-item">
                                                            <div>
                                                                <label class="block text-xs font-medium text-gray-500">Atributo Existente</label>
                                                                <select name="variants[{{ $variantIndex }}][attributes][{{ $attributeIndex }}][atributo_id]" class="block w-full border-gray-300 rounded-md shadow-sm text-sm">
                                                                    <option value="">Selecciona</option>
                                                                    @foreach ($atributos as $atributo)
                                                                        <option value="{{ $atributo->id }}" {{ ($oldAttribute['atributo_id'] ?? '') == $atributo->id ? 'selected' : '' }}>
                                                                            {{ $atributo->nombre }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="block text-xs font-medium text-gray-500">Valor</label>
                                                                <input type="text" name="variants[{{ $variantIndex }}][attributes][{{ $attributeIndex }}][valor]" placeholder="Valor" value="{{ $oldAttribute['valor'] ?? '' }}"
                                                                    class="block w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                                                            </div>
                                                            <button type="button" class="remove-attribute-button text-red-400 hover:text-red-600 text-xs self-center mt-4">X</button>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
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
            const tiposEmbalaje = @json($tipos_embalaje);
            const atributosExistentes = @json($atributos);
            const embalajeOptionsHtml = tiposEmbalaje.map(tipo => `<option value="${tipo.id}">${tipo.nombre}</option>`).join('');

            function createAttributeHtml(currentVariantIndex, attributeIndex) {
                const existingAttributesOptions = atributosExistentes.map(attr => `<option value="${attr.id}">${attr.nombre}</option>`).join('');
                return `
                    <div class="flex gap-2 items-center attribute-item">
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

            function addAttribute(containerId, currentVariantIndex) {
                const attributesContainer = document.getElementById(containerId);
                const attributeIndex = attributesContainer.children.length;
                attributesContainer.insertAdjacentHTML('beforeend', createAttributeHtml(currentVariantIndex, attributeIndex));
            }

            function createVariantHtml() {
                const html = `
                    <div class="variant-item p-4 border rounded-md shadow-sm mb-4 bg-gray-50 relative">
                        <div class="absolute top-2 right-2">
                            <button type="button" class="remove-variant text-red-500 hover:text-red-700 text-sm">Eliminar</button>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-4">Variante #${variantIndex + 1}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            
                            <div>
                                <label for="variants[${variantIndex}][variant_name]" class="block text-sm font-medium text-gray-700">Nombre de la Variante:</label>
                                <input type="text" name="variants[${variantIndex}][variant_name]" id="variants[${variantIndex}][variant_name]"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="variants[${variantIndex}][precio]" class="block text-sm font-medium text-gray-700">Precio:</label>
                                <input type="number" name="variants[${variantIndex}][precio]" id="variants[${variantIndex}][precio]" step="0.01"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>

                            <div>
                                <label for="variants[${variantIndex}][stock]" class="block text-sm font-medium text-gray-700">Stock:</label>
                                <input type="number" name="variants[${variantIndex}][stock]" id="variants[${variantIndex}][stock]"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="variants[${variantIndex}][tipo_embalaje_id]" class="block text-sm font-medium text-gray-700">Tipo de Embalaje:</label>
                                <select name="variants[${variantIndex}][tipo_embalaje_id]" id="variants[${variantIndex}][tipo_embalaje_id]"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleccione un embalaje</option>
                                    ${embalajeOptionsHtml}
                                </select>
                            </div>
                            
                            <div class="flex items-center self-end">
                                <input type="checkbox" name="variants[${variantIndex}][is_active]" value="1"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" checked>
                                <label class="ml-2 block text-sm font-medium text-gray-700">Activa</label>
                            </div>

                            <div class="col-span-full mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Imágenes de la Variante (Principal y Adicionales)</label>
                                <input type="file" name="variants[${variantIndex}][new_variant_images][]" multiple
                                    class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                            </div>

                            <div class="col-span-full mt-4">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-semibold text-gray-800">Atributos</h4>
                                    <button type="button" class="add-attribute-button px-3 py-1 bg-gray-200 text-gray-700 text-xs rounded-md hover:bg-gray-300" data-variant-index="${variantIndex}">
                                        Añadir Atributo
                                    </button>
                                </div>
                                <div class="attributes-container mt-2 space-y-2" id="attributes-container-${variantIndex}">
                                    <!-- Los atributos se añadirán aquí dinámicamente -->
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                return html;
            }

            addVariantButton.addEventListener('click', function () {
                variantsContainer.insertAdjacentHTML('beforeend', createVariantHtml());
                variantIndex++;
            });

            variantsContainer.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-variant')) {
                    const variantItem = e.target.closest('.variant-item');
                    if (variantItem) {
                        variantItem.remove();
                    }
                } else if (e.target.classList.contains('add-attribute-button')) {
                    const variantIdx = e.target.getAttribute('data-variant-index');
                    const containerId = `attributes-container-${variantIdx}`;
                    addAttribute(containerId, variantIdx);
                } else if (e.target.classList.contains('remove-attribute-button')) {
                    const attributeItem = e.target.closest('.attribute-item');
                    if (attributeItem) {
                        attributeItem.remove();
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
