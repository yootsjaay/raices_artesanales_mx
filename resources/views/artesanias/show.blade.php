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
                    @if ($artesania->imagen_principal)
                        <a href="{{ asset('storage/' . $artesania->imagen_principal) }}" data-lightbox="artesania-gallery" data-title="{{ $artesania->nombre }}">
                            <img src="{{ asset('storage/' . $artesania->imagen_principal) }}" alt="{{ $artesania->nombre }}" class="w-full h-96 object-cover rounded-lg shadow-md cursor-zoom-in border border-oaxaca-accent border-opacity-30"> {{-- Borde sutil --}}
                        </a>
                    @else
                        <a href="{{ asset('storage/images/artesanias/placeholder-alebrije.jpg') }}" data-lightbox="artesania-gallery" data-title="Imagen no disponible">
                            <img src="{{ asset('storage/images/artesanias/placeholder-alebrije.jpg') }}" alt="Imagen no disponible" class="w-full h-96 object-cover rounded-lg shadow-md bg-gray-200 cursor-zoom-in border border-oaxaca-accent border-opacity-30">
                        </a>
                    @endif

                    @if ($artesania->imagen_adicionales)
                        @php
                         $imagenesAdicionales = $artesania->imagen_adicionales; 
                        @endphp
                        @if (is_array($imagenesAdicionales) && count($imagenesAdicionales) > 0)
                            <div class="mt-4 grid grid-cols-3 gap-2">
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
                    <p class="text-4xl font-bold text-oaxaca-tertiary mb-6">${{ number_format($artesania->precio, 2) }} MXN</p> {{-- Precio más grande y color terciario --}}
                    <p class="text-oaxaca-text-dark text-lg mb-6 leading-relaxed">{{ $artesania->descripcion }}</p> {{-- Texto oscuro --}}

                    <div class="text-base text-oaxaca-text-dark space-y-3 mb-6"> {{-- Texto oscuro y más espaciado --}}
                        @if ($artesania->categoria)
                            <p><strong>Categoría:</strong> <a href="{{ route('categorias.show', $artesania->categoria->id) }}" class="text-oaxaca-accent hover:underline transition-colors">{{ $artesania->categoria->nombre }}</a></p> {{-- Enlace con color de acento --}}
                        @endif
                        @if ($artesania->ubicacion)
                            <p><strong>Origen:</strong> <a href="{{ route('ubicaciones.show', $artesania->ubicacion->id) }}" class="text-oaxaca-accent hover:underline transition-colors">{{ $artesania->ubicacion->nombre }}</a></p> {{-- Enlace con color de acento --}}
                        @endif
                        <p><strong>Stock:</strong> <span class="{{ $artesania->stock > 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">{{ $artesania->stock > 0 ? $artesania->stock . ' disponibles' : 'Agotado' }}</span></p>
                        <p><strong>Técnica:</strong> {{ $artesania->tecnica_empleada ?? 'N/A' }}</p>
                        <p><strong>Materiales:</strong> {{ $artesania->materiales ?? 'N/A' }}</p>
                        <p><strong>Dimensiones:</strong> {{ $artesania->dimensiones ?? 'N/A' }}</p>
                    </div>

                    <h3 class="text-2xl font-display font-bold text-oaxaca-primary mb-3">La Historia Detrás de la Pieza</h3> {{-- Título con estilo font-display y color primario --}}
                    <p class="text-oaxaca-text-dark leading-relaxed mb-6">{{ $artesania->historia_pieza ?? 'No hay una historia específica para esta pieza aún.' }}</p>

                    <form method="POST" action="{{ route('carrito.agregar') }}" class="flex flex-col sm:flex-row items-center gap-3">
                        @csrf
                        <input type="hidden" name="artesania_id" value="{{ $artesania->id }}">

                        {{-- Selector de cantidad --}}
                        <input type="number" name="cantidad" min="1" max="{{ $artesania->stock }}" value="1"
                               class="w-24 p-3 border border-oaxaca-primary border-opacity-30 rounded-lg focus:outline-none focus:ring-2 focus:ring-oaxaca-tertiary text-oaxaca-text-dark text-lg"
                               required>

                        <button type="submit"
                                class="flex-grow sm:flex-none bg-oaxaca-primary hover:bg-oaxaca-secondary text-white font-semibold px-6 py-3 rounded-lg shadow-md transition duration-200 transform hover:scale-105"
                                {{ $artesania->stock <= 0 ? 'disabled' : '' }}> {{-- Deshabilitar si no hay stock --}}
                            <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            {{ $artesania->stock > 0 ? 'Añadir al Carrito' : 'Agotado' }}
                        </button>
                    </form>

                </div>
            </div>

            <hr class="my-10 border-t-2 border-oaxaca-primary border-opacity-10"> {{-- Separador más robusto --}}

            <div id="comments-section" class="mt-8">
                <h3 class="text-3xl font-display font-bold text-oaxaca-primary mb-6">{{ __('Comentarios y Reseñas') }}</h3>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">¡Éxito!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="bg-oaxaca-bg-light p-6 rounded-lg shadow-inner mb-8 border border-oaxaca-accent border-opacity-10"> {{-- Fondo y borde para la caja de comentarios --}}
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
                            <div class="bg-white p-6 rounded-lg shadow-md mb-4 border border-oaxaca-primary border-opacity-10"> {{-- Fondo, sombra y borde para comentarios individuales --}}
                                <div class="flex items-center mb-2">
                                    <div class="font-bold text-oaxaca-primary mr-2">{{ $comment->user->name }}</div> {{-- Color de usuario --}}
                                    <div class="text-sm text-gray-500">{{ $comment->created_at->format('d/m/Y') }}</div>
                                </div>
                                <div class="mb-2">
                                    <span class="text-oaxaca-tertiary">@for ($i = 1; $i <= $comment->rating; $i++) ★ @endfor</span> {{-- Estrellas con color terciario --}}
                                </div>
                                <p class="text-oaxaca-text-dark">{{ $comment->content }}</p> {{-- Color del contenido --}}
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection