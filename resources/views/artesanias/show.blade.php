@extends('layouts.public')

@section('title', $artesania->nombre . ' - Raíces Artesanales MX')

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8 mt-8">
        <a href="{{ route('artesanias.index') }}" class="inline-flex items-center text-oaxaca-navbar-blue hover:text-oaxaca-title-pink transition-colors mb-6">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver al Catálogo
        </a>

        <h1 class="text-4xl font-extrabold text-oaxaca-title-pink mb-4">{{ $artesania->nombre }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div>
                @if ($artesania->imagen_principal)
                    <a href="{{ asset('storage/' . $artesania->imagen_principal) }}" data-lightbox="artesania-gallery" data-title="{{ $artesania->nombre }}">
                        <img src="{{ asset('storage/' . $artesania->imagen_principal) }}" alt="{{ $artesania->nombre }}" class="w-full h-96 object-cover rounded-lg shadow-md cursor-zoom-in">
                    </a>
                @else
                    <a href="{{ asset('storage/images/artesanias/placeholder-alebrije.jpg') }}" data-lightbox="artesania-gallery" data-title="Imagen no disponible">
                        <img src="{{ asset('storage/images/artesanias/placeholder-alebrije.jpg') }}" alt="Imagen no disponible" class="w-full h-96 object-cover rounded-lg shadow-md bg-gray-200 cursor-zoom-in">
                    </a>
                @endif

                @if ($artesania->imagen_adicionales)
                    @php $imagenesAdicionales = json_decode($artesania->imagen_adicionales, true); @endphp
                    @if (is_array($imagenesAdicionales) && count($imagenesAdicionales) > 0)
                        <div class="mt-4 grid grid-cols-3 gap-2">
                            @foreach ($imagenesAdicionales as $extraImage)
                                <a href="{{ asset('storage/' . $extraImage) }}" data-lightbox="artesania-gallery" data-title="{{ $artesania->nombre }} - Vista adicional">
                                    <img src="{{ asset('storage/' . $extraImage) }}" alt="Imagen adicional de {{ $artesania->nombre }}" class="w-full h-24 object-cover rounded-md shadow-sm cursor-zoom-in hover:opacity-75 transition-opacity">
                                </a>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>

            <div>
                <p class="text-3xl font-bold text-oaxaca-navbar-orange mb-4">${{ number_format($artesania->precio, 2) }} MXN</p>
                <p class="text-oaxaca-text-dark-gray text-lg mb-6 leading-relaxed">{{ $artesania->descripcion }}</p>

                <div class="text-base text-oaxaca-text-dark-gray space-y-2 mb-6">
                    @if ($artesania->categoria)
                        <p><strong>Categoría:</strong> <a href="{{ route('categorias.show', $artesania->categoria->id) }}" class="text-oaxaca-navbar-blue hover:underline">{{ $artesania->categoria->nombre }}</a></p>
                    @endif
                    @if ($artesania->ubicacion)
                        <p><strong>Origen:</strong> <a href="{{ route('ubicaciones.show', $artesania->ubicacion->id) }}" class="text-oaxaca-navbar-blue hover:underline">{{ $artesania->ubicacion->nombre }}</a></p>
                    @endif
                    <p><strong>Stock:</strong> {{ $artesania->stock > 0 ? $artesania->stock . ' disponibles' : 'Agotado' }}</p>
                    <p><strong>Técnica:</strong> {{ $artesania->tecnica_empleada ?? 'N/A' }}</p>
                    <p><strong>Materiales:</strong> {{ $artesania->materiales ?? 'N/A' }}</p>
                    <p><strong>Dimensiones:</strong> {{ $artesania->dimensiones ?? 'N/A' }}</p>
                </div>

                <h3 class="text-2xl font-bold text-oaxaca-navbar-blue mb-3">La Historia Detrás de la Pieza</h3>
                <p class="text-oaxaca-text-dark-gray leading-relaxed">{{ $artesania->historia_pieza ?? 'No hay una historia específica para esta pieza aún.' }}</p>

                <div class="mt-8">
                    <button class="w-full bg-oaxaca-button-mustard text-oaxaca-text-dark-gray px-6 py-3 rounded-lg hover:bg-oaxaca-button-mustard-hover transition-colors text-center text-xl font-semibold shadow-md">
                        Añadir al Carrito
                    </button>
                </div>
            </div>
        </div>

        <hr class="my-8 border-oaxaca-light-gray">

        <div id="comments-section" class="mt-8">
            <h3 class="text-2xl font-bold text-oaxaca-navbar-blue mb-6">{{ __('Comentarios y Reseñas') }}</h3>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">¡Éxito!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-oaxaca-light-beige p-6 rounded-lg shadow-inner mb-8">
                <h4 class="text-xl font-semibold text-oaxaca-text-dark-gray mb-4">{{ __('Deja tu Comentario') }}</h4>
                @auth
                    <form action="{{ route('artesanias.comments.store', $artesania) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="content" class="block text-oaxaca-text-dark-gray text-sm font-bold mb-2">{{ __('Tu Comentario') }}:</label>
                            <textarea name="content" id="content" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('content') border-red-500 @enderror" placeholder="{{ __('Escribe tu comentario aquí...') }}">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="rating" class="block text-oaxaca-text-dark-gray text-sm font-bold mb-2">{{ __('Calificación (1-5 estrellas)') }}:</label>
                            <select name="rating" id="rating" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('rating') border-red-500 @enderror">
                                <option value="">{{ __('Selecciona una calificación') }}</option>
                                @for ($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} {{ __('estrellas') }}</option>
                                @endfor
                            </select>
                            @error('rating')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="bg-oaxaca-button-mustard hover:bg-oaxaca-button-mustard-hover text-oaxaca-text-dark-gray font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            {{ __('Enviar Comentario') }}
                        </button>
                    </form>
                @else
                    <p class="text-oaxaca-text-dark-gray">
                        {{ __('Por favor,') }} <a href="{{ route('login') }}" class="text-oaxaca-navbar-blue hover:underline">{{ __('inicia sesión') }}</a> {{ __('para dejar un comentario.') }}
                    </p>
                @endauth
            </div>

            <div class="mt-8">
                @if ($artesania->comments->isEmpty())
                    <p class="text-oaxaca-text-dark-gray">{{ __('No hay comentarios aprobados para esta artesanía aún.') }}</p>
                @else
                    @foreach ($artesania->comments as $comment)
                        <div class="bg-white p-6 rounded-lg shadow-md mb-4 border border-oaxaca-light-gray">
                            <div class="flex items-center mb-2">
                                <div class="font-bold text-oaxaca-navbar-blue mr-2">{{ $comment->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $comment->created_at->format('d/m/Y') }}</div>
                            </div>
                            <div class="mb-2">
                                <span class="text-yellow-400">@for ($i = 1; $i <= $comment->rating; $i++) ★ @endfor</span>
                            </div>
                            <p class="text-oaxaca-text-dark-gray">{{ $comment->content }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
