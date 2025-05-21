@extends('layouts.public')

@section('title', $artesano->nombre . ' - Artesano')

@section('content')
    <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-lg p-8 border-2 border-oaxaca-blue border-opacity-30">
        <a href="{{ route('artesanos.index') }}" class="inline-flex items-center text-oaxaca-blue hover:text-oaxaca-red transition-colors mb-6 text-lg font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver a la Lista de Artesanos
        </a>

        <div class="flex flex-col md:flex-row items-center md:items-start gap-8 mb-8">
            <div class="flex-shrink-0">
                @if ($artesano->foto_perfil)
                    <img src="{{ asset('storage/' . $artesano->foto_perfil) }}" alt="Foto de {{ $artesano->nombre }}" class="w-64 h-64 object-cover object-center rounded-full shadow-lg border-4 border-oaxaca-red">
                @else
                    <img src="{{ asset('images/placeholder_artesano.jpg') }}" alt="Foto no disponible" class="w-64 h-64 object-cover object-center rounded-full shadow-lg border-4 border-oaxaca-red bg-gray-200">
                @endif
            </div>
            <div class="text-center md:text-left">
                <h1 class="text-5xl font-extrabold text-oaxaca-blue mb-3">{{ $artesano->nombre }}</h1>
                @if ($artesano->ubicacion)
                    <p class="text-xl text-gray-700 mb-4">
                        Originario de <a href="{{ route('ubicaciones.show', $artesano->ubicacion->id) }}" class="text-oaxaca-green hover:underline font-semibold">{{ $artesano->ubicacion->nombre }}</a>
                    </p>
                @endif
                <p class="text-gray-800 text-lg leading-relaxed max-w-2xl">
                    {{ $artesano->biografia ?? 'Este artesano aún no ha compartido su historia en detalle, pero su dedicación a la tradición se refleja en cada pieza.' }}
                </p>

                {{-- Detalles de Contacto (Opcional, si los quieres públicos) --}}
                <div class="mt-6 text-gray-700 text-md flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-2">
                    @if ($artesano->telefono)
                        <p><strong class="text-oaxaca-blue">Teléfono:</strong> {{ $artesano->telefono }}</p>
                    @endif
                    @if ($artesano->email)
                        <p><strong class="text-oaxaca-blue">Email:</strong> <a href="mailto:{{ $artesano->email }}" class="text-oaxaca-green hover:underline">{{ $artesano->email }}</a></p>
                    @endif
                    @if ($artesano->redes_sociales)
                        <p><strong class="text-oaxaca-blue">Redes:</strong> {{ $artesano->redes_sociales }}</p>
                    @endif
                </div>
            </div>
        </div>

        <hr class="my-10 border-oaxaca-red border-opacity-30">

        <h2 class="text-4xl font-bold text-oaxaca-blue mb-8 text-center">Artesanías de {{ $artesano->nombre }}</h2>

        @if ($artesano->artesanias->isEmpty())
            <p class="text-center text-gray-700 text-xl py-6">
                Este artesano aún no ha subido ninguna pieza al catálogo. ¡Pronto tendremos sus creaciones!
            </p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach ($artesano->artesanias as $artesania)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition-all duration-300 hover:scale-105 hover:shadow-xl border border-gray-200">
                        <a href="{{ route('artesanias.show', $artesania->id) }}">
                            @if ($artesania->imagen_principal)
                                <img src="{{ asset('storage/' . $artesania->imagen_principal) }}" alt="{{ $artesania->nombre }}" class="w-full h-48 object-cover object-center">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" alt="Imagen no disponible" class="w-full h-48 object-cover object-center bg-gray-200">
                            @endif
                        </a>
                        <div class="p-4">
                            <h3 class="text-xl font-semibold mb-1 text-oaxaca-blue leading-tight">
                                <a href="{{ route('artesanias.show', $artesania->id) }}" class="hover:text-oaxaca-red transition-colors">{{ $artesania->nombre }}</a>
                            </h3>
                            <p class="text-lg text-accent-orange font-bold">${{ number_format($artesania->precio, 2) }} MXN</p>
                            @if ($artesania->categoria)
                                <p class="text-sm text-gray-600 mt-1">Categoría: <a href="{{ route('categorias.show', $artesania->categoria->id) }}" class="hover:underline text-oaxaca-green">{{ $artesania->categoria->nombre }}</a></p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection