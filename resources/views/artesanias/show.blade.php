@extends('comprador.layouts.public')

@section('title', $artesania->nombre . ' - Raíces Artesanales MX')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-5xl mx-auto bg-oaxaca-card-bg rounded-xl shadow-lg p-8 mt-8 border border-oaxaca-primary border-opacity-10">
            {{-- Enlace para volver al catálogo --}}
            <a href="{{ route('artesanias.index') }}" class="inline-flex items-center text-oaxaca-primary hover:text-oaxaca-secondary transition-colors mb-6 font-semibold">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Catálogo
            </a>

            {{-- Título de la artesanía --}}
            <h1 class="text-4xl md:text-5xl font-display font-bold text-oaxaca-primary mb-6 leading-tight">{{ $artesania->nombre }}</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-8 items-start">
                {{-- Sección de imagen principal y galería --}}
                <div>
                    @php
                        // Función para obtener ruta completa de imágenes
                        function getImagePath($path) {
                            if (!$path) return asset('images/default-image.jpg');
                            return asset('storage/' . $path);
                        }

                        // Determinar imagen inicial
                        $initialImage = '';
                        $initialPrice = $artesania->precio;
                        $initialStock = $artesania->stock;
                        $variantImages = [];

                        if ($selectedVariant) {
                            // Manejar imágenes de variante
                            $variantImages = is_array($selectedVariant->imagen_variant) 
                                ? $selectedVariant->imagen_variant 
                                : [$selectedVariant->imagen_variant];
                            
                            if (!empty($variantImages[0])) {
                                $initialImage = getImagePath($variantImages[0]);
                            }
                            $initialPrice = $selectedVariant->precio;
                            $initialStock = $selectedVariant->stock;
                        }

                        // Si no hay imagen de variante, usar imagen base
                        $baseImages = is_array($artesania->imagen_artesanias) 
                            ? $artesania->imagen_artesanias 
                            : [];
                        
                        if (!$initialImage && !empty($baseImages[0])) {
                            $initialImage = getImagePath($baseImages[0]);
                        }

                        // Fallback si no hay ninguna imagen
                        if (!$initialImage) {
                            $initialImage = asset('images/default-image.jpg');
                        }
                    @endphp
                    <div id="main-image-container">
                        <a href="{{ $initialImage }}" data-lightbox="artesania-gallery" data-title="{{ $artesania->nombre }}">
                            <img src="{{ $initialImage }}" alt="{{ $artesania->nombre }}" class="w-full h-96 object-cover rounded-lg shadow-md" id="product-main-image">
                        </a>
                    </div>
                    
                    {{-- Miniaturas de galería --}}
                    @if (count($baseImages) > 0 || $artesania->variants->count() > 0)
                        <h3 class="text-lg font-semibold text-oaxaca-primary mt-4 mb-2">Galería de Imágenes</h3>
                        <div class="flex flex-wrap gap-2 overflow-x-auto scrollbar-hide" id="thumbnail-container">
                            {{-- Miniaturas de la artesanía base --}}
                            @foreach ($baseImages as $image)
                                <button type="button" class="thumbnail-button p-1 rounded-lg border-2 border-gray-300 hover:border-oaxaca-accent transition-colors"
                                    data-image-src="{{ getImagePath($image) }}" 
                                    data-image-title="{{ $artesania->nombre }}" 
                                    data-variant-id="base-{{ $loop->index }}">
                                    <img src="{{ getImagePath($image) }}" alt="Miniatura de {{ $artesania->nombre }}" class="w-20 h-20 object-cover rounded">
                                </button>
                            @endforeach
                            
                            {{-- Miniaturas de las variantes --}}
                            @foreach ($artesania->variants as $variant)
                                @if ($variant->imagen_variant)
                                    @php
                                        $images = is_array($variant->imagen_variant) 
                                            ? $variant->imagen_variant 
                                            : [$variant->imagen_variant];
                                    @endphp
                                    @foreach ($images as $image)
                                        @if ($image)
                                            <button type="button" class="thumbnail-button p-1 rounded-lg border-2 border-gray-300 hover:border-oaxaca-accent transition-colors"
                                                data-image-src="{{ getImagePath($image) }}"
                                                data-image-title="{{ $artesania->nombre }} ({{ $variant->variant_name ?? 'Variante' }})"
                                                data-variant-id="{{ $variant->id }}">
                                                <img src="{{ getImagePath($image) }}" alt="Miniatura de la variante {{ $variant->variant_name }}" class="w-20 h-20 object-cover rounded">
                                            </button>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-oaxaca-text-dark mt-4">No hay imágenes adicionales disponibles para esta artesanía.</p>
                    @endif
                </div>

                {{-- Sección de detalles del producto y variantes --}}
                <div>
                    {{-- Precio dinámico --}}
                    <p class="text-4xl font-bold text-oaxaca-tertiary mb-6" id="artesania-price">${{ number_format($initialPrice, 2) }} MXN</p>

                    {{-- Descripción de la artesanía --}}
                    <p class="text-oaxaca-text-dark text-lg mb-6 leading-relaxed">{{ $artesania->descripcion }}</p>

                    {{-- Detalles generales --}}
                    <div class="text-base text-oaxaca-text-dark space-y-3 mb-6">
                        @if ($artesania->categoria)
                            <p><strong>Categoría:</strong> <a href="{{ route('categorias.show', $artesania->categoria->slug) }}" class="text-oaxaca-accent hover:underline transition-colors">{{ $artesania->categoria->nombre }}</a></p>
                        @endif
                        @if ($artesania->ubicacion)
                            <p><strong>Origen:</strong> <a href="{{ route('ubicaciones.show', $artesania->ubicacion->slug) }}" class="text-oaxaca-accent hover:underline transition-colors">{{ $artesania->ubicacion->nombre }}</a></p>
                        @endif
                        {{-- Stock dinámico --}}
                        <p><strong>Stock:</strong> <span id="artesania-stock" class="{{ $initialStock > 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">{{ $initialStock > 0 ? ($initialStock . ' disponibles') : 'Agotado' }}</span></p>
                        <p><strong>Materiales:</strong> {{ $artesania->materiales ?? 'N/A' }}</p>

                        {{-- Detalles de la variante seleccionada --}}
                        <p id="variant-color-display" class="{{ ($selectedVariant && $selectedVariant->color) ? '' : 'hidden' }}">
                            <strong>Color:</strong> <span id="current-color-value">{{ $selectedVariant->color ?? '' }}</span>
                        </p>
                        <p id="variant-size-display" class="{{ ($selectedVariant && $selectedVariant->size) ? '' : 'hidden' }}">
                            <strong>Talla:</strong> <span id="current-size-value">{{ $selectedVariant->size ?? '' }}</span>
                        </p>
                        <p id="variant-material-display" class="{{ ($selectedVariant && $selectedVariant->material_variant) ? '' : 'hidden' }}">
                            <strong>Material Variante:</strong> <span id="current-material-value">{{ $selectedVariant->material_variant ?? '' }}</span>
                        </p>
                    </div>

                    {{-- Historia de la pieza --}}
                    <h3 class="text-2xl font-display font-bold text-oaxaca-primary mb-3">La Historia Detrás de la Pieza</h3>
                    <p class="text-oaxaca-text-dark leading-relaxed mb-6">{{ $artesania->historia_pieza ?? 'No hay una historia específica para esta pieza aún.' }}</p>

                    {{-- Sección de selección de variantes --}}
                    @if ($artesania->variants->count())
                        <div class="mb-6 space-y-4">
                            @php
                                $colors = $artesania->variants->pluck('color')->filter()->unique();
                                $sizes = $artesania->variants->pluck('size')->filter()->unique();
                                $materials = $artesania->variants->pluck('material_variant')->filter()->unique();
                            @endphp

                            @if ($colors->isNotEmpty())
                                <div>
                                    <h4 class="font-semibold text-oaxaca-primary mb-2">Color:</h4>
                                    <div class="flex flex-wrap gap-2" id="color-options">
                                        @foreach ($colors as $color)
                                            <button type="button"
                                                class="variant-option color-option p-1 rounded-full border-2 {{ ($selectedVariant && $selectedVariant->color === $color) ? 'border-oaxaca-tertiary shadow-md' : 'border-gray-300 hover:border-oaxaca-accent' }} transition-all duration-200"
                                                data-attribute="color"
                                                data-value="{{ $color }}"
                                                title="{{ $color }}">
                                                <div class="w-10 h-10 rounded-full object-cover" style="background-color: {{ $color }};"></div>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($sizes->isNotEmpty())
                                <div>
                                    <h4 class="font-semibold text-oaxaca-primary mb-2">Talla:</h4>
                                    <div class="flex flex-wrap gap-2" id="size-options">
                                        @foreach ($sizes as $size)
                                            <button type="button"
                                                class="variant-option size-option px-4 py-2 rounded-lg border-2 {{ ($selectedVariant && $selectedVariant->size === $size) ? 'border-oaxaca-tertiary bg-oaxaca-tertiary bg-opacity-20 text-oaxaca-primary font-bold' : 'border-gray-300 text-oaxaca-text-dark hover:border-oaxaca-accent' }} transition-all duration-200"
                                                data-attribute="size"
                                                data-value="{{ $size }}">
                                                {{ $size }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($materials->isNotEmpty())
                                <div>
                                    <h4 class="font-semibold text-oaxaca-primary mb-2">Material Variante:</h4>
                                    <div class="flex flex-wrap gap-2" id="material-options">
                                        @foreach ($materials as $material)
                                            <button type="button"
                                                class="variant-option material-option px-4 py-2 rounded-lg border-2 {{ ($selectedVariant && $selectedVariant->material_variant === $material) ? 'border-oaxaca-tertiary bg-oaxaca-tertiary bg-opacity-20 text-oaxaca-primary font-bold' : 'border-gray-300 text-oaxaca-text-dark hover:border-oaxaca-accent' }} transition-all duration-200"
                                                data-attribute="material_variant"
                                                data-value="{{ $material }}">
                                                {{ $material }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Formulario para añadir al carrito --}}
                    <form method="POST" action="{{ route('carrito.agregar') }}" class="flex flex-col sm:flex-row items-center gap-3 flex-wrap">
                        @csrf
                        <input type="hidden" name="artesania_id" value="{{ $artesania->id }}">
                        <input type="hidden" name="variant_id" id="selected_variant_id" value="{{ $selectedVariant->id ?? '' }}">

                        <input type="number" name="cantidad" min="1" value="1"
                            class="w-24 p-3 border border-oaxaca-primary border-opacity-30 rounded-lg focus:outline-none focus:ring-2 focus:ring-oaxaca-tertiary text-oaxaca-text-dark text-lg"
                            required max="{{ $initialStock }}">

                        @php
                            $isAddToCartDisabled = ($artesania->variants->isEmpty() && $artesania->stock <= 0) || 
                                                  ($selectedVariant && $selectedVariant->stock <= 0);
                        @endphp
                        
                        <button type="submit" id="add-to-cart-btn"
                            class="bg-oaxaca-primary hover:bg-oaxaca-secondary text-white font-semibold px-6 py-3 rounded-lg shadow-md transition duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ $isAddToCartDisabled ? 'disabled' : '' }}>
                            <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>
                                {{ $isAddToCartDisabled ? 'Agotado' : 'Añadir al Carrito' }}
                            </span>
                        </button>
                        <p id="no-variant-selected-message" class="text-red-600 text-sm mt-2 hidden">Por favor, selecciona una combinación válida de variantes.</p>
                    </form>
                </div>
            </div>

            <hr class="my-10 border-t-2 border-oaxaca-primary border-opacity-10">

            {{-- Sección de comentarios y reseñas --}}
            <div id="comments-section" class="mt-8">
                <h3 class="text-3xl font-display font-bold text-oaxaca-primary mb-6">{{ __('Comentarios y Reseñas') }}</h3>

                {{-- Mensajes de sesión --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">¡Éxito!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">¡Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Formulario para dejar comentario --}}
                <div class="bg-oaxaca-bg-light p-6 rounded-lg shadow-inner mb-8 border border-oaxaca-accent border-opacity-10">
                    <h4 class="text-xl font-display font-semibold text-oaxaca-primary mb-4">{{ __('Deja tu Comentario') }}</h4>
                    @auth
                        <form action="{{ route('artesanias.comments.store', $artesania) }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="content" class="block text-oaxaca-text-dark text-sm font-bold mb-2">{{ __('Tu Comentario') }}:</label>
                                <textarea name="content" id="content" rows="4" class="shadow appearance-none border border-oaxaca-primary border-opacity-30 rounded w-full py-2 px-3 text-oaxaca-text-dark leading-tight focus:outline-none focus:ring-2 focus:ring-oaxaca-tertiary @error('content') border-red-500 @enderror" placeholder="{{ __('Escribe tu comentario aquí...') }}">{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="rating" class="block text-oaxaca-text-dark text-sm font-bold mb-2">{{ __('Calificación (1-5 estrellas)') }}:</label>
                                <select name="rating" id="rating" class="shadow appearance-none border border-oaxaca-primary border-opacity-30 rounded w-full py-2 px-3 text-oaxaca-text-dark leading-tight focus:outline-none focus:ring-2 focus:ring-oaxaca-tertiary @error('rating') border-red-500 @enderror">
                                    <option value="">{{ __('Selecciona una calificación') }}</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} {{ __('estrellas') }}</option>
                                    @endfor
                                </select>
                                @error('rating')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="bg-oaxaca-tertiary hover:bg-oaxaca-primary text-oaxaca-primary hover:text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition-colors shadow-sm">
                                {{ __('Enviar Comentario') }}
                            </button>
                        </form>
                    @else
                        <p class="text-oaxaca-text-dark">
                            {{ __('Por favor,') }} <a href="{{ route('login') }}" class="text-oaxaca-accent hover:underline transition-colors">{{ __('inicia sesión') }}</a> {{ __('para dejar un comentario.') }}
                        </p>
                    @endauth
                </div>

                {{-- Listado de comentarios existentes --}}
                <div class="mt-8">
                    @if ($artesania->comments->isEmpty())
                        <p class="text-oaxaca-text-dark">{{ __('No hay comentarios aprobados para esta artesanía aún.') }}</p>
                    @else
                        @foreach ($artesania->comments as $comment)
                            <div class="bg-white p-6 rounded-lg shadow-md mb-4 border border-oaxaca-primary border-opacity-10">
                                <div class="flex items-center mb-2">
                                    <div class="font-bold text-oaxaca-primary mr-2">{{ $comment->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $comment->created_at->format('d/m/Y') }}</div>
                                </div>
                                <div class="mb-2">
                                    <span class="text-oaxaca-tertiary">@for ($i = 1; $i <= $comment->rating; $i++) ★ @endfor</span>
                                </div>
                                <p class="text-oaxaca-text-dark">{{ $comment->content }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const artesaniaData = @json($artesania->toArray());
        const selectedVariantData = @json($selectedVariant ? $selectedVariant->toArray() : null);
        const variants = artesaniaData.variants || [];
        
        // Elementos DOM
        const mainImage = document.getElementById('product-main-image');
        const mainImageLink = document.querySelector('#main-image-container a');
        const artesaniaPrice = document.getElementById('artesania-price');
        const artesaniaStock = document.getElementById('artesania-stock');
        const quantityInput = document.querySelector('input[name="cantidad"]');
        const addToCartButton = document.getElementById('add-to-cart-btn');
        const selectedVariantInput = document.getElementById('selected_variant_id');
        const thumbnailContainer = document.getElementById('thumbnail-container');
        const variantOptions = document.querySelectorAll('.variant-option');
        
        // Elementos para mostrar atributos de variante
        const variantDisplayElements = {
            color: document.getElementById('variant-color-display'),
            size: document.getElementById('variant-size-display'),
            material_variant: document.getElementById('variant-material-display'),
        };
        
        const currentVariantValues = {
            color: document.getElementById('current-color-value'),
            size: document.getElementById('current-size-value'),
            material_variant: document.getElementById('current-material-value'),
        };
        
        // Atributos seleccionados
        let selectedAttributes = {
            color: selectedVariantData ? selectedVariantData.color : null,
            size: selectedVariantData ? selectedVariantData.size : null,
            material_variant: selectedVariantData ? selectedVariantData.material_variant : null,
        };

        // Función para obtener ruta de imagen
        function getImagePath(imagePath) {
            if (!imagePath) return '';
            return `/storage/${imagePath}`;
        }
        
        // Función para actualizar los atributos visibles de la variante
        function updateVariantAttributes(item) {
            const attributes = ['color', 'size', 'material_variant'];
            
            attributes.forEach(attr => {
                const displayElement = variantDisplayElements[attr];
                const valueElement = currentVariantValues[attr];
                
                if (item && item[attr]) {
                    displayElement.classList.remove('hidden');
                    valueElement.textContent = item[attr];
                } else {
                    displayElement.classList.add('hidden');
                    valueElement.textContent = '';
                }
            });
        }
        
        // Función para actualizar toda la UI
        function updateUI(variant) {
            const currentItem = variant || artesaniaData;
            const isVariant = variant !== null;

            // Actualizar precio y stock
            artesaniaPrice.textContent = `$${currentItem.precio.toFixed(2)} MXN`;
            artesaniaStock.textContent = currentItem.stock > 0 
                ? `${currentItem.stock} disponibles` 
                : 'Agotado';
                
            artesaniaStock.className = currentItem.stock > 0 
                ? 'text-green-600 font-semibold' 
                : 'text-red-600 font-semibold';
            
            // Actualizar controles de cantidad y carrito
            quantityInput.max = currentItem.stock;
            quantityInput.value = Math.min(1, currentItem.stock);
            addToCartButton.disabled = currentItem.stock <= 0;
            addToCartButton.querySelector('span').textContent = 
                currentItem.stock <= 0 ? 'Agotado' : 'Añadir al Carrito';
                
            selectedVariantInput.value = isVariant ? currentItem.id : '';

            // Actualizar imagen principal
            let imagePath = '';
            
            if (isVariant && currentItem.imagen_variant && currentItem.imagen_variant.length > 0) {
                imagePath = getImagePath(currentItem.imagen_variant[0]);
            } else if (artesaniaData.imagen_artesanias && artesaniaData.imagen_artesanias.length > 0) {
                imagePath = getImagePath(artesaniaData.imagen_artesanias[0]);
            } else {
                imagePath = "{{ asset('images/default-image.jpg') }}";
            }
            
            mainImage.src = imagePath;
            mainImageLink.href = imagePath;
            mainImage.alt = currentItem.variant_name || artesaniaData.nombre;
            mainImageLink.dataset.title = currentItem.variant_name || artesaniaData.nombre;
            
            // Actualizar atributos específicos de la variante
            updateVariantAttributes(currentItem);
            
            // Resaltar miniatura activa
            highlightActiveThumbnail(isVariant ? currentItem.id : 'base-0');
        }
        
        // Función para resaltar miniatura activa
        function highlightActiveThumbnail(variantId) {
            document.querySelectorAll('.thumbnail-button').forEach(btn => {
                btn.classList.remove('border-oaxaca-tertiary', 'border-2');
                
                if (btn.dataset.variantId == variantId) {
                    btn.classList.add('border-oaxaca-tertiary', 'border-2');
                }
            });
        }
        
        // Función para inicializar selección de botones
        function initializeButtonSelection() {
            variantOptions.forEach(btn => {
                const attribute = btn.dataset.attribute;
                const value = btn.dataset.value;
                
                // Resetear clases
                btn.classList.remove(
                    'border-oaxaca-tertiary', 
                    'shadow-md', 
                    'bg-oaxaca-tertiary', 
                    'bg-opacity-20', 
                    'text-oaxaca-primary', 
                    'font-bold'
                );
                
                btn.classList.add(
                    'border-gray-300', 
                    'text-oaxaca-text-dark', 
                    'hover:border-oaxaca-accent'
                );
                
                // Aplicar clases si está seleccionado
                if (selectedAttributes[attribute] === value) {
                    btn.classList.add(
                        'border-oaxaca-tertiary', 
                        'shadow-md', 
                        'bg-oaxaca-tertiary', 
                        'bg-opacity-20', 
                        'text-oaxaca-primary', 
                        'font-bold'
                    );
                    
                    btn.classList.remove(
                        'border-gray-300', 
                        'text-oaxaca-text-dark', 
                        'hover:border-oaxaca-accent'
                    );
                }
            });
        }
        
        // Manejar clic en miniaturas
        thumbnailContainer.addEventListener('click', function(event) {
            const button = event.target.closest('.thumbnail-button');
            if (!button) return;

            // Actualizar imagen principal
            mainImage.src = button.dataset.imageSrc;
            mainImageLink.href = button.dataset.imageSrc;

            // Resaltar miniatura activa
            highlightActiveThumbnail(button.dataset.variantId);
            
            // Si es una variante, actualizar atributos
            if (!button.dataset.variantId.startsWith('base-')) {
                const variantId = button.dataset.variantId;
                const variant = variants.find(v => v.id == variantId);
                
                if (variant) {
                    // Actualizar atributos seleccionados
                    selectedAttributes = {
                        color: variant.color || null,
                        size: variant.size || null,
                        material_variant: variant.material_variant || null,
                    };
                    
                    initializeButtonSelection();
                    updateUI(variant);
                }
            } else {
                // Si es imagen base, resetear
                selectedAttributes = { color: null, size: null, material_variant: null };
                initializeButtonSelection();
                updateUI(null);
            }
        });
        
        // Manejar clic en opciones de variante
        variantOptions.forEach(button => {
            button.addEventListener('click', function() {
                const attribute = this.dataset.attribute;
                const value = this.dataset.value;
                
                // Toggle selección
                selectedAttributes[attribute] = 
                    selectedAttributes[attribute] === value ? null : value;
                
                initializeButtonSelection();
                
                // Buscar variante que coincida con los atributos seleccionados
                const matchingVariant = variants.find(v => {
                    return Object.entries(selectedAttributes).every(([attr, val]) => 
                        val === null || v[attr] === val
                    );
                });
                
                updateUI(matchingVariant);
            });
        });
        
        // Inicializar UI
        if (selectedVariantData) {
            updateUI(selectedVariantData);
            initializeButtonSelection();
            highlightActiveThumbnail(selectedVariantData.id);
        } else {
            updateUI(null);
            highlightActiveThumbnail('base-0');
        }
    });
</script>
@endpush