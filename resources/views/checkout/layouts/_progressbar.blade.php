<div class="flex items-center justify-between mb-8 text-sm md:text-base">
    {{-- Paso 1: Dirección --}}
    <div class="flex-1 text-center font-semibold {{ $step >= 1 ? 'text-oaxaca-primary' : 'text-gray-400' }}">
        <a href="{{ route('checkout.shipping') }}" class="w-10 h-10 mx-auto mb-2 flex items-center justify-center rounded-full border-2 {{ $step >= 1 ? 'border-oaxaca-primary bg-oaxaca-primary text-white' : 'border-gray-400 bg-transparent text-gray-400' }}">
            1
        </a>
        Dirección
    </div>

    {{-- Línea de progreso 1-2 --}}
    <div class="flex-1 h-1 bg-gray-300 mx-2 {{ $step >= 2 ? 'bg-oaxaca-primary' : '' }}"></div>

    {{-- Paso 2: Envío --}}
    <div class="flex-1 text-center font-semibold {{ $step >= 2 ? 'text-oaxaca-primary' : 'text-gray-400' }}">
        <div class="w-10 h-10 mx-auto mb-2 flex items-center justify-center rounded-full border-2 {{ $step >= 2 ? 'border-oaxaca-primary bg-oaxaca-primary text-white' : 'border-gray-400 bg-transparent text-gray-400' }}">
            2
        </div>
        Envío
    </div>

    {{-- Línea de progreso 2-3 --}}
    <div class="flex-1 h-1 bg-gray-300 mx-2 {{ $step >= 3 ? 'bg-oaxaca-primary' : '' }}"></div>

    {{-- Paso 3: Pago --}}
    <div class="flex-1 text-center font-semibold {{ $step >= 3 ? 'text-oaxaca-primary' : 'text-gray-400' }}">
        <div class="w-10 h-10 mx-auto mb-2 flex items-center justify-center rounded-full border-2 {{ $step >= 3 ? 'border-oaxaca-primary bg-oaxaca-primary text-white' : 'border-gray-400 bg-transparent text-gray-400' }}">
            3
        </div>
        Pago
    </div>

    {{-- Línea de progreso 3-4 --}}
    <div class="flex-1 h-1 bg-gray-300 mx-2 {{ $step >= 4 ? 'bg-oaxaca-primary' : '' }}"></div>

    {{-- Paso 4: Revisar --}}
    <div class="flex-1 text-center font-semibold {{ $step >= 4 ? 'text-oaxaca-primary' : 'text-gray-400' }}">
        <div class="w-10 h-10 mx-auto mb-2 flex items-center justify-center rounded-full border-2 {{ $step >= 4 ? 'border-oaxaca-primary bg-oaxaca-primary text-white' : 'border-gray-400 bg-transparent text-gray-400' }}">
            4
        </div>
        Revisar
    </div>
</div>