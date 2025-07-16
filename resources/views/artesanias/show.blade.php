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
                        @if ($variant && $variant->image)
                            <a href="{{ asset('storage/' . $variant->image) }}" data-lightbox="artesania-gallery" data-title="{{ $variant->variant_name ?? $artesania->nombre }}">
                                <img id="main-artesania-image" src="{{ asset('storage/' . $variant->image) }}" alt="{{ $variant->variant_name ?? $artesania->nombre }}" class="w-full h-96 object-cover rounded-lg shadow-md cursor-zoom-in border border-oaxaca-accent border-opacity-30">
                            </a>
                        @elseif ($artesania->imagen_principal)
                             <a href="{{ asset('storage/' . $artesania->imagen_principal) }}" data-lightbox="artesania-gallery" data-title="{{ $artesania->nombre }}">
                                <img id="main-artesania-image" src="{{ asset('storage/' . $artesania->imagen_principal) }}" alt="{{ $artesania->nombre }}" class="w-full h-96 object-cover rounded-lg shadow-md cursor-zoom-in border border-oaxaca-accent border-opacity-30">
                            </a>
                        @else
                            <a href="{{ asset('storage/images/artesanias/placeholder-alebrije.jpg') }}" data-lightbox="artesania-gallery" data-title="Imagen no disponible">
                                <img id="main-artesania-image" src="{{ asset('storage/images/artesanias/placeholder-alebrije.jpg') }}" alt="Imagen no disponible" class="w-full h-96 object-cover rounded-lg shadow-md bg-gray-200 cursor-zoom-in border border-oaxaca-accent border-opacity-30">
                            </a>
                        @endif
                    </div>

                    {{-- Miniaturas de imágenes adicionales --}}
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
                    <p class="text-4xl font-bold text-oaxaca-tertiary mb-6" id="artesania-price">${{ number_format($artesania->precio, 2) }} MXN</p>
                    <p class="text-oaxaca-text-dark text-lg mb-6 leading-relaxed">{{ $artesania->descripcion }}</p>

                    <div class="text-base text-oaxaca-text-dark space-y-3 mb-6">
                        @if ($artesania->categoria)
                            <p><strong>Categoría:</strong> <a href="{{ route('categorias.show', $artesania->categoria->slug) }}" class="text-oaxaca-accent hover:underline transition-colors">{{ $artesania->categoria->nombre }}</a></p>
                        @endif
                        @if ($artesania->ubicacion)
                            <p><strong>Origen:</strong> <a href="{{ route('ubicaciones.show', $artesania->ubicacion->slug) }}" class="text-oaxaca-accent hover:underline transition-colors">{{ $artesania->ubicacion->nombre }}</a></p>
                        @endif
                        {{-- Stock dinámico --}}
                        <p><strong>Stock:</strong> <span id="artesania-stock" class="{{ $artesania->stock > 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">{{ $artesania->stock > 0 ? $artesania->stock . ' disponibles' : 'Agotado' }}</span></p>
                        <p><strong>Técnica:</strong> {{ $artesania->tecnica_empleada ?? 'N/A' }}</p>
                        <p><strong>Materiales:</strong> {{ $artesania->materiales ?? 'N/A' }}</p>

                        {{-- Campos de variante dinámicos --}}
                        <p id="variant-color-display" class="{{ $variant && $variant->color ? '' : 'hidden' }}"><strong>Color:</strong> {{ $variant->color ?? 'N/A' }}</p>
                        <p id="variant-size-display" class="{{ $variant && $variant->size ? '' : 'hidden' }}"><strong>Talla:</strong> {{ $variant->size ?? 'N/A' }}</p>
                        <p id="variant-material-display" class="{{ $variant && $variant->material_variant ? '' : 'hidden' }}"><strong>Material Variante:</strong> {{ $variant->material_variant ?? 'N/A' }}</p>
                        {{-- <p id="variant-weight-display" class="{{ $variant && $variant->weight ? '' : 'hidden' }}"><strong>Peso:</strong> {{ $variant->weight ?? $artesania->weight }} kg</p> --}}
                        {{-- <p id="variant-dimensions-display" class="{{ $variant && ($variant->dimensions || ($artesania->length && $artesania->width && $artesania->height)) ? '' : 'hidden' }}"><strong>Dimensiones:</strong> {{ $variant->dimensions ?? ($artesania->length . 'x' . $artesania->width . 'x' . $artesania->height) }} cm</p> --}}
                        <p><strong>Dimensiones:</strong> {{ $artesania->dimensiones ?? 'N/A' }}</p>

                    </div>

                    <h3 class="text-2xl font-display font-bold text-oaxaca-primary mb-3">La Historia Detrás de la Pieza</h3>
                    <p class="text-oaxaca-text-dark leading-relaxed mb-6">{{ $artesania->historia_pieza ?? 'No hay una historia específica para esta pieza aún.' }}</p>

                   <form method="POST" action="{{ route('carrito.agregar') }}" class="flex flex-col sm:flex-row items-center gap-3 flex-wrap">
                        @csrf
                        <input type="hidden" name="artesania_id" value="{{ $artesania->id }}">

                        @if ($artesania->artesania_variants->count())
                            <div class="w-full sm:w-auto">
                                <label for="variant_id" class="block text-oaxaca-text-dark font-semibold mb-1">Selecciona una variante:</label>
                                <select name="variant_id" id="variant_id_select"
                                    class="p-3 border border-oaxaca-primary border-opacity-30 rounded-lg w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-oaxaca-tertiary text-lg text-oaxaca-text-dark"
                                    required>
                                    <option value="">-- Elige Talla y/o Color --</option>
                                    @foreach ($artesania->artesania_variants as $variantLoop)
                                        <option value="{{ $variantLoop->id }}"
                                            data-image="{{ $variantLoop->image ? asset('storage/' . $variantLoop->image) : '' }}"
                                            data-price-adjustment="{{ $variantLoop->price_adjustment }}"
                                            data-stock="{{ $variantLoop->stock }}"
                                            data-color="{{ $variantLoop->color ?? '' }}"
                                            data-size="{{ $variantLoop->size ?? '' }}"
                                            data-material="{{ $variantLoop->material_variant ?? '' }}"
                                            {{ (isset($variant) && $variant->id === $variantLoop->id) ? 'selected' : '' }}
                                            {{ $variantLoop->stock <= 0 ? 'disabled' : '' }}>
                                            {{ $variantLoop->variant_name ?: (($variantLoop->size ?? 'Sin talla') . ' / ' . ($variantLoop->color ?? 'Sin color') . ' / ' . ($variantLoop->material_variant ?? '')) }}
                                            - ${{ number_format($artesania->precio + $variantLoop->price_adjustment, 2) }} MXN
                                            (Stock: {{ $variantLoop->stock }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            {{-- Si no hay variantes, se usará la artesania_id principal y el stock de la artesanía --}}
                            <input type="hidden" name="variant_id" value="">
                        @endif

                        {{-- Selector de cantidad --}}
                        <input type="number" name="cantidad" min="1" value="1"
                            class="w-24 p-3 border border-oaxaca-primary border-opacity-30 rounded-lg focus:outline-none focus:ring-2 focus:ring-oaxaca-tertiary text-oaxaca-text-dark text-lg"
                            required>

                        {{-- Botón de agregar al carrito --}}
                        <button type="submit" id="add-to-cart-btn"
                            class="bg-oaxaca-primary hover:bg-oaxaca-secondary text-white font-semibold px-6 py-3 rounded-lg shadow-md transition duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ ($artesania->artesania_variants->isEmpty() && $artesania->stock <= 0) || ($artesania->artesania_variants->isNotEmpty() && (!isset($variant) || $variant->stock <= 0)) ? 'disabled' : '' }}>
                            <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            {{ ($artesania->artesania_variants->isEmpty() && $artesania->stock <= 0) || ($artesania->artesania_variants->isNotEmpty() && (!isset($variant) || $variant->stock <= 0)) ? 'Agotado' : 'Añadir al Carrito' }}
                        </button>
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
        const variantSelect = document.getElementById('variant_id_select');
        const mainArtesaniaImage = document.getElementById('main-artesania-image');
        const mainImageLink = mainArtesaniaImage.closest('a');
        const artesaniaPriceDisplay = document.getElementById('artesania-price');
        const artesaniaStockDisplay = document.getElementById('artesania-stock');
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        const originalBasePrice = {{ $artesania->precio }};
        const originalStock = {{ $artesania->stock }}; // Stock de la artesanía base
        const variantColorDisplay = document.getElementById('variant-color-display');
        const variantSizeDisplay = document.getElementById('variant-size-display');
        const variantMaterialDisplay = document.getElementById('variant-material-display');

        // Función para actualizar los detalles de la artesanía
        function updateArtesaniaDetails() {
            const selectedOption = variantSelect.options[variantSelect.selectedIndex];
            const variantImage = selectedOption.dataset.image;
            const priceAdjustment = parseFloat(selectedOption.dataset.priceAdjustment || 0);
            const variantStock = parseInt(selectedOption.dataset.stock);
            const variantColor = selectedOption.dataset.color;
            const variantSize = selectedOption.dataset.size;
            const variantMaterial = selectedOption.dataset.material;
            const variantName = selectedOption.text.split(' - ')[0]; // Obtener el nombre de la variante del texto de la opción

            // Actualizar imagen principal
            if (variantImage) {
                mainArtesaniaImage.src = variantImage;
                mainArtesaniaImage.alt = variantName;
                mainImageLink.href = variantImage; // Actualizar el href para Lightbox
                mainImageLink.dataset.title = variantName; // Actualizar el título para Lightbox
            } else {
                // Si no hay imagen de variante, volver a la imagen principal de la artesanía o al placeholder
                mainArtesaniaImage.src = '{{ $artesania->imagen_principal ? asset('storage/' . $artesania->imagen_principal) : asset('storage/images/artesanias/placeholder-alebrije.jpg') }}';
                mainArtesaniaImage.alt = '{{ $artesania->nombre }}';
                mainImageLink.href = '{{ $artesania->imagen_principal ? asset('storage/' . $artesania->imagen_principal) : asset('storage/images/artesanias/placeholder-alebrije.jpg') }}';
                mainImageLink.dataset.title = '{{ $artesania->nombre }}';
            }

            // Actualizar precio
            const newPrice = originalBasePrice + priceAdjustment;
            artesaniaPriceDisplay.textContent = `$${newPrice.toFixed(2)} MXN`;

            // Actualizar stock
            if (variantSelect.value === '') { // Si no hay variante seleccionada (opción "-- Elige Talla y/o Color --")
                artesaniaStockDisplay.textContent = `${originalStock > 0 ? originalStock + ' disponibles' : 'Agotado'}`;
                artesaniaStockDisplay.className = `${originalStock > 0 ? 'text-green-600' : 'text-red-600'} font-semibold`;
                addToCartBtn.disabled = originalStock <= 0;
                addToCartBtn.querySelector('span').textContent = originalStock > 0 ? 'Añadir al Carrito' : 'Agotado'; // Actualiza el texto del botón
            } else {
                artesaniaStockDisplay.textContent = `${variantStock > 0 ? variantStock + ' disponibles' : 'Agotado'}`;
                artesaniaStockDisplay.className = `${variantStock > 0 ? 'text-green-600' : 'text-red-600'} font-semibold`;
                addToCartBtn.disabled = variantStock <= 0;
                addToCartBtn.querySelector('span').textContent = variantStock > 0 ? 'Añadir al Carrito' : 'Agotado'; // Actualiza el texto del botón
            }


            // Actualizar y mostrar/ocultar detalles de variante
            if (variantColor) {
                variantColorDisplay.innerHTML = `<strong>Color:</strong> ${variantColor}`;
                variantColorDisplay.classList.remove('hidden');
            } else {
                variantColorDisplay.classList.add('hidden');
            }

            if (variantSize) {
                variantSizeDisplay.innerHTML = `<strong>Talla:</strong> ${variantSize}`;
                variantSizeDisplay.classList.remove('hidden');
            } else {
                variantSizeDisplay.classList.add('hidden');
            }

            if (variantMaterial) {
                variantMaterialDisplay.innerHTML = `<strong>Material Variante:</strong> ${variantMaterial}`;
                variantMaterialDisplay.classList.remove('hidden');
            } else {
                variantMaterialDisplay.classList.add('hidden');
            }

            // Opcional: Recargar Lightbox para que incluya las nuevas imágenes si la imagen principal cambia
            // Esto dependerá de cómo se inicialice Lightbox. Si se inicializa en DOMContentLoaded,
            // puede que necesites un método para "refrescarlo" o reinicializarlo si el contenido de la galería cambia dinámicamente.
            // Para la imagen principal, el cambio de src y href suele ser suficiente.
        }

        // Ejecutar al cargar la página para la variante seleccionada inicialmente (si hay una)
        updateArtesaniaDetails();

        // Escuchar cambios en el selector de variantes
        variantSelect.addEventListener('change', updateArtesaniaDetails);
    });
</script>
@endsection