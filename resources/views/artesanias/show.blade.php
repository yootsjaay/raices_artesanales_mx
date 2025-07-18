@extends('comprador.layouts.public')

@section('title', $artesania->nombre . ' - Raíces Artesanales MX')

@section('content')
    <div class="container mx-auto px-4 py-8"> {{-- Contenedor principal con padding --}}
        <div class="max-w-5xl mx-auto bg-oaxaca-card-bg rounded-xl shadow-lg p-8 mt-8 border border-oaxaca-primary border-opacity-10"> {{-- Fondo de tarjeta, sombra y borde --}}
            <a href="{{ route('artesanias.index') }}" class="inline-flex items-center text-oaxaca-primary hover:text-oaxaca-secondary transition-colors mb-6 font-semibold"> {{-- Color del enlace y hover --}}
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Catálogo
            </a>

            <h1 class="text-4xl md:text-5xl font-display font-bold text-oaxaca-primary mb-6 leading-tight">{{ $artesania->nombre }}</h1> {{-- Título con estilo font-display y color primario --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-8 items-start"> {{-- Aumentado el gap y añadido items-start para alineación --}}
                <div>
                    {{-- Contenedor de la imagen principal --}}
                    <div id="main-image-container">
                        {{-- La imagen se inicializa con la variante seleccionada o la imagen principal de la artesanía --}}
                        @php
                            $initialImage = ($selectedVariant && $selectedVariant->image) ? asset('storage/' . $selectedVariant->image) : ($artesania->imagen_principal ? asset('storage/' . $artesania->imagen_principal) : asset('storage/images/artesanias/placeholder-alebrije.jpg'));
                            $initialImageAlt = ($selectedVariant && $selectedVariant->variant_name) ? $selectedVariant->variant_name : $artesania->nombre;
                        @endphp
                        <a href="{{ $initialImage }}" data-lightbox="artesania-gallery" data-title="{{ $initialImageAlt }}">
                            <img id="main-artesania-image" src="{{ $initialImage }}" alt="{{ $initialImageAlt }}" class="w-full h-96 object-cover rounded-lg shadow-md cursor-zoom-in border border-oaxaca-accent border-opacity-30">
                        </a>
                    </div>

                    {{-- Miniaturas de imágenes adicionales (se mantienen para la artesanía principal) --}}
                    @if ($artesania->imagen_adicionales)
                        @php
                         $imagenesAdicionales = is_string($artesania->imagen_adicionales) ? json_decode($artesania->imagen_adicionales) : $artesania->imagen_adicionales;
                        @endphp
                        @if (is_array($imagenesAdicionales) && count($imagenesAdicionales) > 0)
                            <div class="mt-4 grid grid-cols-3 gap-2" id="additional-images-container">
                                @foreach ($imagenesAdicionales as $extraImage)
                                    <a href="{{ asset('storage/' . $extraImage) }}" data-lightbox="artesania-gallery" data-title="{{ $artesania->nombre }} - Vista adicional">
                                        <img src="{{ asset('storage/' . $extraImage) }}" alt="Imagen adicional de {{ $artesania->nombre }}" class="w-full h-24 object-cover rounded-md shadow-sm cursor-zoom-in hover:opacity-75 transition-opacity border border-oaxaca-accent border-opacity-20"> {{-- Borde sutil en miniaturas --}}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>

                <div>
                    {{-- Precio dinámico --}}
                    @php
                        $initialPrice = $artesania->precio + (($selectedVariant && $selectedVariant->price_adjustment) ? $selectedVariant->price_adjustment : 0);
                    @endphp
                    <p class="text-4xl font-bold text-oaxaca-tertiary mb-6" id="artesania-price">${{ number_format($initialPrice, 2) }} MXN</p>
                    <p class="text-oaxaca-text-dark text-lg mb-6 leading-relaxed">{{ $artesania->descripcion }}</p>

                    <div class="text-base text-oaxaca-text-dark space-y-3 mb-6">
                        @if ($artesania->categoria)
                            <p><strong>Categoría:</strong> <a href="{{ route('categorias.show', $artesania->categoria->slug) }}" class="text-oaxaca-accent hover:underline transition-colors">{{ $artesania->categoria->nombre }}</a></p>
                        @endif
                        @if ($artesania->ubicacion)
                            <p><strong>Origen:</strong> <a href="{{ route('ubicaciones.show', $artesania->ubicacion->slug) }}" class="text-oaxaca-accent hover:underline transition-colors">{{ $artesania->ubicacion->nombre }}</a></p>
                        @endif
                        {{-- Stock dinámico --}}
                        @php
                            $initialStock = ($selectedVariant && $selectedVariant->stock !== null) ? $selectedVariant->stock : $artesania->stock;
                        @endphp
                        <p><strong>Stock:</strong> <span id="artesania-stock" class="{{ $initialStock > 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">{{ $initialStock > 0 ? $initialStock . ' disponibles' : 'Agotado' }}</span></p>
                        <p><strong>Materiales:</strong> {{ $artesania->materiales ?? 'N/A' }}</p>

                        {{-- Nuevos elementos para mostrar detalles de la variante --}}
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

                    <h3 class="text-2xl font-display font-bold text-oaxaca-primary mb-3">La Historia Detrás de la Pieza</h3>
                    <p class="text-oaxaca-text-dark leading-relaxed mb-6">{{ $artesania->historia_pieza ?? 'No hay una historia específica para esta pieza aún.' }}</p>

                    {{-- Sección de selección de variantes --}}
                    @if ($artesania->artesania_variants->count())
                        <div class="mb-6 space-y-4">
                            {{-- Agrupación de variantes por atributo (Color, Talla, Material) --}}
                            @php
                                $colors = $artesania->artesania_variants->pluck('color')->filter()->unique();
                                $sizes = $artesania->artesania_variants->pluck('size')->filter()->unique();
                                $materials = $artesania->artesania_variants->pluck('material_variant')->filter()->unique();
                            @endphp

                            @if ($colors->isNotEmpty())
                                <div>
                                    <h4 class="font-semibold text-oaxaca-primary mb-2">Color:</h4>
                                    <div class="flex flex-wrap gap-2" id="color-options">
                                        @foreach ($colors as $color)
                                            @php
                                                // Encuentra una variante con este color para obtener su imagen (si aplica)
                                                $colorVariant = $artesania->artesania_variants->where('color', $color)->first();
                                                $colorImage = $colorVariant && $colorVariant->image ? asset('storage/' . $colorVariant->image) : 'https://placehold.co/40x40/cccccc/ffffff?text=' . substr($color, 0, 1);
                                            @endphp
                                            <button type="button"
                                                    class="variant-option p-1 rounded-full border-2 {{ ($selectedVariant && $selectedVariant->color === $color) ? 'border-oaxaca-tertiary shadow-md' : 'border-gray-300 hover:border-oaxaca-accent' }} transition-all duration-200"
                                                    data-attribute="color"
                                                    data-value="{{ $color }}"
                                                    title="{{ $color }}">
                                                <img src="{{ $colorImage }}" alt="{{ $color }}" class="w-10 h-10 rounded-full object-cover">
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
                                                    class="variant-option px-4 py-2 rounded-lg border-2 {{ ($selectedVariant && $selectedVariant->size === $size) ? 'border-oaxaca-tertiary bg-oaxaca-tertiary bg-opacity-20 text-oaxaca-primary font-bold' : 'border-gray-300 text-oaxaca-text-dark hover:border-oaxaca-accent' }} transition-all duration-200"
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
                                                    class="variant-option px-4 py-2 rounded-lg border-2 {{ ($selectedVariant && $selectedVariant->material_variant === $material) ? 'border-oaxaca-tertiary bg-oaxaca-tertiary bg-opacity-20 text-oaxaca-primary font-bold' : 'border-gray-300 text-oaxaca-text-dark hover:border-oaxaca-accent' }} transition-all duration-200"
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

                   <form method="POST" action="{{ route('carrito.agregar') }}" class="flex flex-col sm:flex-row items-center gap-3 flex-wrap">
                        @csrf
                        <input type="hidden" name="artesania_id" value="{{ $artesania->id }}">
                        {{-- Este input oculto se actualizará con el ID de la variante seleccionada --}}
                        <input type="hidden" name="variant_id" id="selected_variant_id" value="{{ $selectedVariant->id ?? '' }}">

                        {{-- Selector de cantidad --}}
                        <input type="number" name="cantidad" min="1" value="1"
                            class="w-24 p-3 border border-oaxaca-primary border-opacity-30 rounded-lg focus:outline-none focus:ring-2 focus:ring-oaxaca-tertiary text-oaxaca-text-dark text-lg"
                            required>

                        {{-- Botón de agregar al carrito --}}
                        @php
                            $isAddToCartDisabled = false;
                            if ($artesania->artesania_variants->isEmpty()) {
                                $isAddToCartDisabled = $artesania->stock <= 0;
                            } else {
                                $isAddToCartDisabled = !isset($selectedVariant) || $selectedVariant->stock <= 0;
                            }
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

            <div id="comments-section" class="mt-8">
                <h3 class="text-3xl font-display font-bold text-oaxaca-primary mb-6">{{ __('Comentarios y Reseñas') }}</h3>

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

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mainArtesaniaImage = document.getElementById('main-artesania-image');
        const mainImageLink = mainArtesaniaImage.closest('a');
        const artesaniaPriceDisplay = document.getElementById('artesania-price');
        const artesaniaStockDisplay = document.getElementById('artesania-stock');
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        const selectedVariantIdInput = document.getElementById('selected_variant_id');
        const noVariantMessage = document.getElementById('no-variant-selected-message');

        // Elementos para mostrar los detalles específicos de la variante (color, talla, material)
        const currentColorValueDisplay = document.getElementById('current-color-value');
        const currentSizeValueDisplay = document.getElementById('current-size-value');
        const currentMaterialValueDisplay = document.getElementById('current-material-value');
        const variantColorDisplayContainer = document.getElementById('variant-color-display');
        const variantSizeDisplayContainer = document.getElementById('variant-size-display');
        const variantMaterialDisplayContainer = document.getElementById('variant-material-display');


        // Datos de la artesanía principal y todas sus variantes
        const artesaniaData = {
            precio: {{ $artesania->precio }},
            stock: {{ $artesania->stock }},
            imagen_principal: '{{ $artesania->imagen_principal ? asset('storage/' . $artesania->imagen_principal) : asset('storage/images/artesanias/placeholder-alebrije.jpg') }}',
            nombre: '{{ $artesania->nombre }}'
        };

        // Incrustar todas las variantes como un objeto JSON
        const allVariants = @json($artesania->artesania_variants);

        // Estado para almacenar las selecciones actuales del usuario
        let selectedAttributes = {};

        // Inicializar selectedAttributes con la variante que viene del controlador (si hay una)
        @if ($selectedVariant)
            @if ($selectedVariant->color)
                selectedAttributes.color = '{{ $selectedVariant->color }}';
            @endif
            @if ($selectedVariant->size)
                selectedAttributes.size = '{{ $selectedVariant->size }}';
            @endif
            @if ($selectedVariant->material_variant)
                selectedAttributes.material_variant = '{{ $selectedVariant->material_variant }}';
            @endif
        @endif

        // Función para encontrar la variante que coincide con los atributos seleccionados
        function findMatchingVariant() {
            if (Object.keys(selectedAttributes).length === 0 && allVariants.length > 0) {
                // Si no hay atributos seleccionados pero hay variantes, intentar encontrar la principal
                return allVariants.find(variant => variant.is_main === true) || allVariants[0];
            }

            // Buscar una variante que coincida con TODOS los atributos seleccionados
            const matchedVariant = allVariants.find(variant => {
                let matches = true;
                for (const attr in selectedAttributes) {
                    if (variant[attr] !== selectedAttributes[attr]) {
                        matches = false;
                        break;
                    }
                }
                return matches;
            });
            return matchedVariant;
        }

        // Función para actualizar la UI
        function updateUI(variant) {
            let currentStock = artesaniaData.stock;
            let currentPrice = artesaniaData.precio;
            let currentImage = artesaniaData.imagen_principal;
            let currentName = artesaniaData.nombre;
            let isButtonDisabled = artesaniaData.stock <= 0;

            // Limpiar detalles de variante
            if (currentColorValueDisplay) currentColorValueDisplay.textContent = '';
            if (currentSizeValueDisplay) currentSizeValueDisplay.textContent = '';
            if (currentMaterialValueDisplay) currentMaterialValueDisplay.textContent = '';
            if (variantColorDisplayContainer) variantColorDisplayContainer.classList.add('hidden');
            if (variantSizeDisplayContainer) variantSizeDisplayContainer.classList.add('hidden');
            if (variantMaterialDisplayContainer) variantMaterialDisplayContainer.classList.add('hidden');

            if (variant) {
                currentStock = variant.stock;
                currentPrice = artesaniaData.precio + (variant.price_adjustment || 0);
                currentImage = variant.image ? `{{ asset('storage') }}/${variant.image}` : artesaniaData.imagen_principal;
                currentName = variant.variant_name || artesaniaData.nombre;
                isButtonDisabled = variant.stock <= 0;
                selectedVariantIdInput.value = variant.id; // Actualizar el ID de la variante en el formulario

                // Actualizar y mostrar detalles de variante
                if (variant.color && currentColorValueDisplay) {
                    currentColorValueDisplay.textContent = variant.color;
                    variantColorDisplayContainer.classList.remove('hidden');
                }
                if (variant.size && currentSizeValueDisplay) {
                    currentSizeValueDisplay.textContent = variant.size;
                    variantSizeDisplayContainer.classList.remove('hidden');
                }
                if (variant.material_variant && currentMaterialValueDisplay) {
                    currentMaterialValueDisplay.textContent = variant.material_variant;
                    variantMaterialDisplayContainer.classList.remove('hidden');
                }

                noVariantMessage.classList.add('hidden'); // Ocultar mensaje de no variante
            } else {
                // Si no se encuentra una variante coincidente, deshabilitar y mostrar mensaje
                isButtonDisabled = true;
                selectedVariantIdInput.value = '';
                noVariantMessage.classList.remove('hidden'); // Mostrar mensaje de no variante
            }

            // Actualizar imagen principal
            mainArtesaniaImage.src = currentImage;
            mainArtesaniaImage.alt = currentName;
            mainImageLink.href = currentImage;
            mainImageLink.dataset.title = currentName;

            // Actualizar precio
            artesaniaPriceDisplay.textContent = `$${currentPrice.toFixed(2)} MXN`;

            // Actualizar stock y estado del botón "Añadir al Carrito"
            artesaniaStockDisplay.textContent = `${currentStock > 0 ? currentStock + ' disponibles' : 'Agotado'}`;
            artesaniaStockDisplay.className = `${currentStock > 0 ? 'text-green-600' : 'text-red-600'} font-semibold`;
            addToCartBtn.disabled = isButtonDisabled;
            addToCartBtn.querySelector('span').textContent = isButtonDisabled ? 'Agotado' : 'Añadir al Carrito';

            // Actualizar clases de 'selected' en los botones de variante
            document.querySelectorAll('.variant-option').forEach(button => {
                const attribute = button.dataset.attribute;
                const value = button.dataset.value;
                if (selectedAttributes[attribute] === value) {
                    button.classList.add('border-oaxaca-tertiary', 'shadow-md');
                    if (attribute !== 'color') { // Los botones de color ya tienen un estilo diferente
                        button.classList.add('bg-oaxaca-tertiary', 'bg-opacity-20', 'text-oaxaca-primary', 'font-bold');
                    }
                } else {
                    button.classList.remove('border-oaxaca-tertiary', 'shadow-md', 'bg-oaxaca-tertiary', 'bg-opacity-20', 'text-oaxaca-primary', 'font-bold');
                    button.classList.add('border-gray-300');
                }
            });
        }

        // Manejador de eventos para las opciones de variante
        document.querySelectorAll('.variant-option').forEach(button => {
            button.addEventListener('click', function() {
                const attribute = this.dataset.attribute;
                const value = this.dataset.value;

                // Si el atributo ya está seleccionado con este valor, deseleccionar
                if (selectedAttributes[attribute] === value) {
                    delete selectedAttributes[attribute];
                } else {
                    selectedAttributes[attribute] = value;
                }

                const matchedVariant = findMatchingVariant();
                updateUI(matchedVariant);
            });
        });

        // Inicializar la UI al cargar la página
        const initialMatchedVariant = findMatchingVariant();
        updateUI(initialMatchedVariant);
    });
</script>
@endsection
