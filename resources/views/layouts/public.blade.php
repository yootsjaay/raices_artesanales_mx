<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Raíces Artesanales MX - Colores de Oaxaca')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Lightbox2 CSS --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body class="font-sans antialiased text-oaxaca-text-dark-gray bg-oaxaca-bg-cream"> {{-- Fondo principal: Crema cálido, Texto general: Gris oscuro --}}

    <header class="bg-oaxaca-navbar-blue text-oaxaca-text-white p-4 shadow-lg"> {{-- Fondo: Azul profundo, Texto: Blanco --}}
        <nav class="container mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-3xl font-extrabold tracking-wide hover:text-oaxaca-navbar-orange transition-colors"> {{-- Título y hover en naranja quemado --}}
                Raíces Artesanales MX
            </a>
            <ul class="flex space-x-6 text-lg">
                <li><a href="{{ route('artesanias.index') }}" class="hover:text-oaxaca-navbar-orange transition-colors">Artesanías</a></li>
                <li><a href="{{ route('artesanos.index') }}" class="hover:text-oaxaca-navbar-orange transition-colors">Artesanos</a></li>
                <li><a href="{{ route('categorias.index') }}" class="hover:text-oaxaca-navbar-orange transition-colors">Categorías</a></li>
                <li><a href="{{ route('ubicaciones.index') }}" class="hover:text-oaxaca-navbar-orange transition-colors">Ubicaciones</a></li>
                <li><a href="{{ route('login') }}" class="hover:text-oaxaca-navbar-orange transition-colors">Ingresar</a></li>
            </ul>
        </nav>
    </header>

    <main class="py-0">
        @yield('content')
    </main>

    <footer class="bg-oaxaca-navbar-blue text-oaxaca-text-white py-8 mt-12"> {{-- Fondo: Azul profundo --}}
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h3 class="text-2xl font-semibold mb-4">Contáctanos</h3>
            <p>Email: raicesartesanales@gmail.com</p>
            <p>Teléfono: +52 951 453 7503</p>
            <p>Visítanos: Oaxaca, México , calle humbolt 104</p>
            <div class="mt-4">
                <a href="#" class="text-oaxaca-navbar-orange hover:text-oaxaca-text-white mx-2 transition-colors">Facebook</a> {{-- Enlaces y hover --}}
                <a href="#" class="text-oaxaca-navbar-orange hover:text-oaxaca-text-white mx-2 transition-colors">Instagram</a>
                <a href="#" class="text-oaxaca-navbar-orange hover:text-oaxaca-text-white mx-2 transition-colors">Twitter</a>
            </div>
        </div>
    </footer>

    {{-- Lightbox2 JavaScript y su inicialización deben ir aquí, justo antes del cierre del <body> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js"></script>
    <script>
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true
        })
    </script>
    @yield('scripts') {{-- Aquí se insertarán otros scripts específicos de las vistas --}}
</body>
</html>