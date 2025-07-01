@php
    $steps = [
        1 => ['name' => 'Envío', 'route' => 'checkout.shipping'],
        2 => ['name' => 'Método de Envío', 'route' => 'checkout.shipping_method'], // You'll create this route/step next
        3 => ['name' => 'Pago', 'route' => 'checkout.payment'], // And this one
        4 => ['name' => 'Revisar Pedido', 'route' => 'checkout.review'], // And this one
    ];
    $currentStep = $step ?? 1; // Default to step 1 if not provided
@endphp

<div class="mb-8">
    <div class="flex items-center justify-between text-center">
        @foreach($steps as $key => $s)
            <div class="flex-1 flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center
                    @if($key <= $currentStep) bg-indigo-600 text-white @else bg-gray-300 text-gray-600 @endif
                    @if($key == $currentStep) border-2 border-indigo-800 @endif">
                    {{ $key }}
                </div>
                <div class="mt-2 text-sm font-medium
                    @if($key <= $currentStep) text-indigo-700 @else text-gray-500 @endif">
                    <a href="{{ route($s['route']) }}" class="hover:underline">
                        {{ $s['name'] }}
                    </a>
                </div>
            </div>
            @if(!$loop->last)
                <div class="flex-1 border-t-2
                    @if($key < $currentStep) border-indigo-600 @else border-gray-300 @endif -mx-4 h-0"></div>
            @endif
        @endforeach
    </div>
</div>