@extends('comprador.layouts.public')

@section('title', 'Catálogo de Artesanías - Raíces Artesanales MX')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl md:text-5xl font-display font-bold text-oaxaca-primary text-center mb-10">
            Catálogo de Artesanías
        </h1>

        {{-- Filtros de búsqueda --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-8 border border-oaxaca-primary border-opacity-10">
            <h2 class="text-2xl font-bold text-oaxaca-primary mb-4">Filtrar Artesanías</h2>
            <form action="{{ route('artesanias.index') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:gap-4">
                
                {{-- Filtro por Categoría --}}
                <div class="flex-1">
                    <label for="categoria_id" class="block text-oaxaca-text-dark font-medium mb-1">Categoría</label>
                    <select name="categoria_id" id="categoria_id" class="w-full border-oaxaca-primary border-opacity-30 rounded-md shadow-sm focus:border-oaxaca-tertiary focus:ring focus:ring-oaxaca-tertiary focus:ring-opacity-50">
                        <option value="">Todas las categorías</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @if(request('categoria_id') == $categoria->id) selected @endif>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filtro por Ubicación --}}
                <div class="flex-1">
                    <label for="ubicacion_id" class="block text-oaxaca-text-dark font-medium mb-1">Origen</label>
                    <select name="ubicacion_id" id="ubicacion_id" class="w-full border-oaxaca-primary border-opacity-30 rounded-md shadow-sm focus:border-oaxaca-tertiary focus:ring focus:ring-oaxaca-tertiary focus:ring-opacity-50">
                        <option value="">Todos los orígenes</option>
                        @foreach ($ubicaciones as $ubicacion)
                            <option value="{{ $ubicacion->id }}" @if(request('ubicacion_id') == $ubicacion->id) selected @endif>
                                {{ $ubicacion->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Botones de acción --}}
                <div class="md:self-end flex gap-2 mt-4 md:mt-0">
                    <button type="submit" class="w-full md:w-auto px-6 py-2 bg-oaxaca-primary text-white font-semibold rounded-md hover:bg-oaxaca-secondary transition-colors">
                        Aplicar Filtros
                    </button>
                    <a href="{{ route('artesanias.index') }}" class="w-full md:w-auto px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-md hover:bg-gray-300 transition-colors text-center">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        {{-- Listado de Artesanías --}}
        @if ($artesanias->isEmpty())
            <div class="text-center py-10">
                <p class="text-2xl text-oaxaca-text-dark">No se encontraron artesanías que coincidan con los filtros.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach ($artesanias as $artesania)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <a href="{{ route('artesanias.show', $artesania->slug) }}">
                            <img 
                                src="{{ asset(is_array($artesania->imagen_artesanias) && !empty($artesania->imagen_artesanias[0]) ? $artesania->imagen_artesanias[0] : 'images/default-image.jpg') }}" 
                                alt="{{ $artesania->nombre }}" 
                                class="w-full h-64 object-cover"
                            >
                        </a>
                        <div class="p-6">
                            <h3 class="font-bold text-xl text-oaxaca-primary mb-2">
                                <a href="{{ route('artesanias.show', $artesania->slug) }}" class="hover:text-oaxaca-accent transition-colors">
                                    {{ $artesania->nombre }}
                                </a>
                            </h3>
                            <p class="text-oaxaca-tertiary text-2xl font-bold mb-4">
                                ${{ number_format($artesania->precio, 2) }}
                            </p>
                            <p class="text-sm text-oaxaca-text-dark mb-4">
                                {{ Str::limit($artesania->descripcion, 100) }}
                            </p>
                            <div class="flex items-center text-oaxaca-text-dark text-sm space-x-2 mb-4">
                                @if ($artesania->categoria)
                                    <span class="bg-gray-100 px-2 py-1 rounded-full text-oaxaca-accent font-semibold">
                                        {{ $artesania->categoria->nombre }}
                                    </span>
                                @endif
                                @if ($artesania->ubicacion)
                                    <span class="bg-gray-100 px-2 py-1 rounded-full text-oaxaca-accent font-semibold">
                                        {{ $artesania->ubicacion->nombre }}
                                    </span>
                                @endif
                            </div>
                            <div class="mt-auto">
                                <a href="{{ route('artesanias.show', $artesania->slug) }}" class="block w-full text-center bg-oaxaca-primary text-white py-2 px-4 rounded-md font-semibold hover:bg-oaxaca-secondary transition-colors">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Paginación --}}
            <div class="mt-8">
                {{ $artesanias->links() }}
            </div>
        @endif
    </div>
@endsection