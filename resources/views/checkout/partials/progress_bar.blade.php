@php
    // Define los pasos del checkout con sus nombres y rutas correspondientes
    $steps = [
        1 => ['name' => 'Dirección', 'route' => 'comprador.checkout.shipping'],
        2 => ['name' => 'Envío', 'route' => 'comprador.checkout.shipping_method'],
        3 => ['name' => 'Pago', 'route' => 'comprador.checkout.payment'],
        4 => ['name' => 'Revisar', 'route' => 'comprador.checkout.review'],
    ];
    // Establece el paso actual. Por defecto es 1 si no se proporciona.
    // Asegúrate de pasar $step desde tu controlador a la vista que incluye este parcial.
    $currentStep = $step ?? 1;
@endphp

<div class="mb-10 mt-4"> {{-- Margen inferior y superior para separar del contenido --}}
    <div class="flex items-center justify-between text-center">
        @foreach($steps as $key => $s)
            <div class="flex-1 flex flex-col items-center relative"> {{-- Añadido relative para la línea de conexión --}}
                {{-- Círculo del paso --}}
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg font-bold transition-all duration-300 ease-in-out
                    @if($key <= $currentStep)
                        bg-oaxaca-primary text-white shadow-md
                    @else
                        bg-gray-300 text-gray-600
                    @endif
                    @if($key == $currentStep)
                        border-2 border-oaxaca-accent scale-110 {{-- Resalta el paso actual --}}
                    @endif">
                    {{ $key }}
                </div>
                {{-- Nombre del paso --}}
                <div class="mt-3 text-sm font-medium transition-colors duration-300 ease-in-out
                    @if($key <= $currentStep)
                        text-oaxaca-primary font-semibold
                    @else
                        text-gray-500
                    @endif">
                    {{-- El enlace solo es activo para pasos anteriores al actual o el actual, para permitir navegación hacia atrás --}}
                    @if($key <= $currentStep)
                        <a href="{{ route($s['route']) }}" class="hover:underline hover:text-oaxaca-secondary">
                            {{ $s['name'] }}
                        </a>
                    @else
                        {{ $s['name'] }} {{-- No es un enlace si es un paso futuro --}}
                    @endif
                </div>
            </div>
            {{-- Línea de conexión entre pasos --}}
            @if(!$loop->last)
                <div class="flex-1 border-t-2 mx-2 transition-colors duration-300 ease-in-out
                    @if($key < $currentStep)
                        border-oaxaca-primary {{-- Línea activa --}}
                    @else
                        border-gray-300 {{-- Línea inactiva --}}
                    @endif h-0"></div>
            @endif
        @endforeach
    </div>
</div>
