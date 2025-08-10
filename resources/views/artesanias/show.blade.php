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

            @php
                // Función para obtener ruta completa de imágenes
                function getImagePath($path) {
                    if (!$path) return asset('images/default-image.jpg');
                    return asset($path);
                }

                // Unificar la artesanía base y sus variantes activas en una sola colección.
                $allVariants = collect();

                // Añadir la artesanía base solo si está activa
                if ($artesania->is_active) {
                    $allVariants->push((object) [
                        'id' => 'base',
                        'size' => $artesania->size ?? null,
                        'color' => $artesania->color ?? null,
                        'material_variant' => $artesania->materiales ?? null,
                        'precio' => $artesania->precio,
                        'stock' => $artesania->stock,
                        'imagen_variant' => is_array($artesania->imagen_artesanias) ? $artesania->imagen_artesanias : [$artesania->imagen_artesanias],
                        'is_base' => true,
                        'variant_name' => $artesania->nombre,
                    ]);
                }

                // Concatenar las variantes activas (ya filtradas por el controlador)
                $allVariants = $allVariants->concat($artesania->variants->map(function($variant) {
                    $variant->is_base = false;
                    return $variant;
                }))->keyBy('id');

                // Determinar la variante inicial de manera más robusta
                $initialVariantId = 'base';
                $initialVariant = $allVariants->get('base');
                
                // Si la variante base no tiene stock, busca la primera variante activa con stock
                if ($initialVariant && $initialVariant->stock <= 0) {
                    $firstActiveWithStock = $artesania->variants->firstWhere('stock', '>', 0);
                    if ($firstActiveWithStock) {
                        $initialVariantId = $firstActiveWithStock->id;
                        $initialVariant = $allVariants->get($initialVariantId);
                    }
                }

                // Fallback si no hay variantes disponibles (esto es un caso extremo)
                if (!$initialVariant) {
                    $initialVariant = (object) ['id' => null, 'imagen_variant' => [], 'precio' => 0, 'stock' => 0, 'color' => null, 'size' => null, 'material_variant' => null];
                }

                $initialImage = !empty($initialVariant->imagen_variant) && !empty($initialVariant->imagen_variant[0])
                                ? getImagePath($initialVariant->imagen_variant[0])
                                : getImagePath(null); // Fallback

                $initialPrice = $initialVariant->precio;
                $initialStock = $initialVariant->stock;

                // Extraer atributos únicos para los botones de selección
                $colors = $allVariants->pluck('color')->filter()->unique()->values();
                $sizes = $allVariants->pluck('size')->filter()->unique()->values();
                $materials = $allVariants->pluck('material_variant')->filter()->unique()->values();

            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-8 items-start">
                {{-- Sección de imagen principal y galería --}}
                <div>
                    {{-- Imagen principal con enlace para Lightbox --}}
                    <div id="main-image-container">
                        <a href="{{ $initialImage }}" data-lightbox="artesania-gallery" data-title="{{ $artesania->nombre }}">
                            <img src="{{ $initialImage }}" alt="{{ $artesania->nombre }}" class="w-full h-96 object-cover rounded-lg shadow-md" id="product-main-image">
                        </a>
                    </div>
                    
                    {{-- Miniaturas de galería --}}
                    @if ($allVariants->count() > 0)
                        <h3 class="text-lg font-semibold text-oaxaca-primary mt-4 mb-2">Galería de Imágenes</h3>
                        <div class="flex flex-wrap gap-2 overflow-x-auto scrollbar-hide" id="thumbnail-container">
                            @foreach ($allVariants as $variant)
                                @if (is_array($variant->imagen_variant) && count($variant->imagen_variant) > 0)
                                    @foreach ($variant->imagen_variant as $image)
                                        @if ($image)
                                            <button type="button" class="thumbnail-button p-1 rounded-lg border-2 border-gray-300 hover:border-oaxaca-accent transition-colors {{ ($initialVariantId == $variant->id) ? '' : 'hidden' }}"
                                                data-image-src="{{ getImagePath($image) }}"
                                                data-image-title="{{ $artesania->nombre }} {{ isset($variant->variant_name) ? '('.$variant->variant_name.')' : '' }}"
                                                data-variant-id="{{ $variant->id }}">
                                                <a href="{{ getImagePath($image) }}" data-lightbox="artesania-gallery" data-title="{{ $artesania->nombre }} {{ isset($variant->variant_name) ? '('.$variant->variant_name.')' : '' }}">
                                                    <img src="{{ getImagePath($image) }}" alt="Miniatura de {{ $artesania->nombre }} {{ isset($variant->variant_name) ? 'de la variante '.$variant->variant_name : '' }}" class="w-20 h-20 object-cover rounded">
                                                </a>
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
                        <p id="variant-color-display" class="{{ ($initialVariant->color) ? '' : 'hidden' }}">
                            <strong>Color:</strong> <span id="current-color-value">{{ $initialVariant->color ?? '' }}</span>
                        </p>
                        <p id="variant-size-display" class="{{ ($initialVariant->size) ? '' : 'hidden' }}">
                            <strong>Talla:</strong> <span id="current-size-value">{{ $initialVariant->size ?? '' }}</span>
                        </p>
                        <p id="variant-material-display" class="{{ ($initialVariant->material_variant) ? '' : 'hidden' }}">
                            <strong>Material Variante:</strong> <span id="current-material-value">{{ $initialVariant->material_variant ?? '' }}</span>
                        </p>
                    </div>

                    {{-- Historia de la pieza --}}
                    <h3 class="text-2xl font-display font-bold text-oaxaca-primary mb-3">La Historia Detrás de la Pieza</h3>
                    <p class="text-oaxaca-text-dark leading-relaxed mb-6">{{ $artesania->historia_pieza ?? 'No hay una historia específica para esta pieza aún.' }}</p>

                    {{-- Sección de selección de variantes --}}
                    @if ($allVariants->count() > 1)
                        <div class="mb-6 space-y-4">
                            @if ($colors->isNotEmpty())
                                <div>
                                    <h4 class="font-semibold text-oaxaca-primary mb-2">Color:</h4>
                                    <div class="flex flex-wrap gap-2" id="color-options">
                                        @foreach ($colors as $color)
                                            @php
                                                $variantWithColor = $allVariants->firstWhere('color', $color);
                                                $imagePathForColor = ($variantWithColor && is_array($variantWithColor->imagen_variant) && !empty($variantWithColor->imagen_variant[0]))
                                                                    ? getImagePath($variantWithColor->imagen_variant[0])
                                                                    : getImagePath(null);
                                            @endphp
                                            <button type="button"
                                                class="variant-option color-option p-1 rounded-full border-2 {{ ($initialVariant->color === $color) ? 'border-oaxaca-tertiary shadow-md' : 'border-gray-300 hover:border-oaxaca-accent' }} transition-all duration-200"
                                                data-attribute="color"
                                                data-value="{{ $color }}"
                                                data-variant-id="{{ $variantWithColor->id ?? '' }}"
                                                title="{{ $color }}">
                                                <img src="{{ $imagePathForColor }}" alt="Color {{ $color }}" class="w-10 h-10 rounded-full object-cover">
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
                                                class="variant-option size-option px-4 py-2 rounded-lg border-2 {{ ($initialVariant->size === $size) ? 'border-oaxaca-tertiary bg-oaxaca-tertiary bg-opacity-20 text-oaxaca-primary font-bold' : 'border-gray-300 text-oaxaca-text-dark hover:border-oaxaca-accent' }} transition-all duration-200"
                                                data-attribute="size"
                                                data-value="{{ $size }}">
                                                {{ $size }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($materials->isNotEmpty())
                                
                            @endif
                        </div>
                    @endif

                    {{-- Formulario para añadir al carrito --}}
                    <form method="POST" action="{{ route('carrito.agregar') }}" class="flex flex-col sm:flex-row items-center gap-3 flex-wrap">
                        @csrf
                        <input type="hidden" name="artesania_id" value="{{ $artesania->id }}">
                        <input type="hidden" name="variant_id" id="selected_variant_id" value="{{ $initialVariantId }}">

                        <input type="number" name="cantidad" min="1" value="1"
                            class="w-24 p-3 border border-oaxaca-primary border-opacity-30 rounded-lg focus:outline-none focus:ring-2 focus:ring-oaxaca-tertiary text-oaxaca-text-dark text-lg"
                            required max="{{ $initialStock }}">

                        @php
                            $isAddToCartDisabled = ($initialStock <= 0);
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
        const mainImageLink = document.querySelector('#main-image-container a');
        const mainImage = document.getElementById('product-main-image');
        const thumbnailContainer = document.getElementById('thumbnail-container');
        const variantOptions = document.querySelectorAll('.variant-option');
        
        const allVariants = @json($allVariants);
        let thumbnails = Array.from(thumbnailContainer.querySelectorAll('.thumbnail-button'));

        // Función para actualizar la imagen principal y la miniatura activa
        function updateMainImage(button) {
            if (!button) return;
            const newImageSrc = button.getAttribute('data-image-src');
            
            mainImage.src = newImageSrc;
            mainImageLink.href = newImageSrc;
            mainImageLink.setAttribute('data-title', button.getAttribute('data-image-title'));

            // Actualizar el estado activo de la miniatura
            thumbnails.forEach(btn => btn.classList.remove('border-oaxaca-accent', 'ring', 'ring-offset-2', 'active'));
            button.classList.add('border-oaxaca-accent', 'ring', 'ring-offset-2', 'active');
        }

        // Event listener para las miniaturas
        thumbnailContainer.addEventListener('click', function(event) {
            const button = event.target.closest('.thumbnail-button');
            if (button) {
                updateMainImage(button);
            }
        });

        // Event listeners para las variantes
        variantOptions.forEach(button => {
            button.addEventListener('click', function () {
                const attribute = this.getAttribute('data-attribute');
                const value = this.getAttribute('data-value');
                const selectedVariantId = this.getAttribute('data-variant-id');
                const matchingVariant = allVariants[selectedVariantId];

                // Actualizar el estado activo para los botones de variante
                document.querySelectorAll(`.${attribute}-option`).forEach(btn => {
                    btn.classList.remove('border-oaxaca-tertiary', 'bg-oaxaca-tertiary', 'bg-opacity-20', 'text-oaxaca-primary', 'font-bold', 'shadow-md');
                    btn.classList.add('border-gray-300', 'text-oaxaca-text-dark', 'hover:border-oaxaca-accent');
                });
                this.classList.remove('border-gray-300', 'text-oaxaca-text-dark', 'hover:border-oaxaca-accent');
                this.classList.add('border-oaxaca-tertiary', 'shadow-md');
                if (attribute !== 'color') {
                    this.classList.add('bg-oaxaca-tertiary', 'bg-opacity-20', 'text-oaxaca-primary', 'font-bold');
                }

                // Ocultar todas las miniaturas primero
                thumbnails.forEach(thumb => thumb.classList.add('hidden'));

                if (matchingVariant) {
                    // Mostrar las miniaturas correspondientes a la variante
                    thumbnails.forEach(thumb => {
                        const thumbVariantId = thumb.getAttribute('data-variant-id');
                        if (thumbVariantId == matchingVariant.id) {
                            thumb.classList.remove('hidden');
                        }
                    });

                    // Si hay imágenes de la variante, actualizar la principal
                    const variantImagePaths = matchingVariant.imagen_variant;
                    if (variantImagePaths && variantImagePaths.length > 0) {
                         const firstThumb = thumbnails.find(thumb => thumb.getAttribute('data-variant-id') == matchingVariant.id);
                         if (firstThumb) {
                             updateMainImage(firstThumb);
                         }
                    } else {
                        const defaultImageSrc = `{{ asset('images/default-image.jpg') }}`;
                        mainImage.src = defaultImageSrc;
                        mainImageLink.href = defaultImageSrc;
                        mainImageLink.setAttribute('data-title', '{{ $artesania->nombre }}');
                    }

                    // Actualizar precio, stock, etc.
                    document.getElementById('artesania-price').textContent = `$${parseFloat(matchingVariant.precio).toFixed(2)} MXN`;
                    document.getElementById('artesania-stock').textContent = matchingVariant.stock > 0 ? `${matchingVariant.stock} disponibles` : 'Agotado';
                    document.getElementById('selected_variant_id').value = matchingVariant.id;

                    // Actualizar estado del botón de añadir al carrito
                    const addToCartBtn = document.getElementById('add-to-cart-btn');
                    const quantityInput = document.querySelector('input[name="cantidad"]');
                    if (matchingVariant.stock <= 0) {
                        addToCartBtn.disabled = true;
                        addToCartBtn.querySelector('span').textContent = 'Agotado';
                        quantityInput.max = 0;
                    } else {
                        addToCartBtn.disabled = false;
                        addToCartBtn.querySelector('span').textContent = 'Añadir al Carrito';
                        quantityInput.max = matchingVariant.stock;
                    }

                    // Actualizar detalles de variante
                    document.getElementById('current-color-value').textContent = matchingVariant.color || '';
                    document.getElementById('variant-color-display').classList.toggle('hidden', !matchingVariant.color);
                    document.getElementById('current-size-value').textContent = matchingVariant.size || '';
                    document.getElementById('variant-size-display').classList.toggle('hidden', !matchingVariant.size);
                    document.getElementById('current-material-value').textContent = matchingVariant.material_variant || '';
                    document.getElementById('variant-material-display').classList.toggle('hidden', !matchingVariant.material_variant);
                }
            });
        });
        
        // Configuración inicial al cargar la página
        if (thumbnails.length > 0) {
            const initialThumb = thumbnails.find(thumb => thumb.getAttribute('data-variant-id') == '{{ $initialVariantId }}');
            if (initialThumb) {
                 updateMainImage(initialThumb);
            }
        }
    });
</script>
@endpush