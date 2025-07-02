<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Checkout - Raíces Artesanales MX')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Nunito+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body class="font-sans antialiased text-oaxaca-text-dark bg-oaxaca-bg-light bg-texture bg-repeat bg-opacity-30">

    {{-- HEADER MINIMALISTA PARA CHECKOUT --}}
    <header class="bg-white shadow-md py-4">
        <div class="container mx-auto px-4 flex justify-center items-center">
            {{-- LOGO (simplificado para el checkout) --}}
            <a href="{{ url('/') }}" class="flex items-center">
                <div class="bg-oaxaca-accent rounded-xl w-12 h-12 flex items-center justify-center text-white text-2xl font-bold">R</div>
                <div class="ml-3">
                    <span class="font-display text-2xl font-bold text-oaxaca-primary">Raíces</span>
                    <span class="block font-sans text-xs uppercase tracking-widest text-oaxaca-accent">Artesanías Oaxaqueñas</span>
                </div>
            </a>
            {{-- Puedes añadir un enlace "Volver a la tienda" si lo deseas, pero sin demasiada prominencia --}}
            {{-- <a href="{{ route('artesanias.index') }}" class="ml-auto text-oaxaca-primary hover:text-oaxaca-secondary text-sm">Volver a la tienda</a> --}}
        </div>
    </header>

    <main class="py-8">
        @yield('content')
    </main>

    {{-- FOOTER MINIMALISTA PARA CHECKOUT --}}
    <footer class="bg-oaxaca-primary text-oaxaca-text-light py-6 text-center text-sm">
        <div class="container mx-auto px-4">
            &copy; {{ date('Y') }} Raíces Artesanales MX. Todos los derechos reservados.
            <a href="#" class="hover:text-oaxaca-secondary transition-colors ml-2">Términos y Condiciones</a> |
            <a href="#" class="hover:text-oaxaca-secondary transition-colors ml-2">Política de Privacidad</a>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>