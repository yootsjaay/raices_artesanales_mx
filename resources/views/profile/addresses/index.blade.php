{{-- resources/views/user/addresses/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Tus Direcciones') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <a href="{{ route('profile.addresses.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow mb-6 inline-block">
                    + Agregar nueva dirección
                </a>

                @if ($addresses->isEmpty())
                    <p class="text-gray-600">No tienes direcciones registradas aún.</p>
                @else
                    <div class="space-y-6">
                        @foreach ($addresses as $address)
                            <div class="border border-gray-200 rounded-lg shadow-sm p-4 bg-white">
                                <div class="text-gray-800 space-y-1">
                                    <p><span class="font-semibold">Nombre:</span> {{ $address->name }}</p>
                                    <p><span class="font-semibold">Teléfono:</span> {{ $address->phone }}</p>
                                    <p><span class="font-semibold">Calle y número:</span> {{ $address->street }} #{{ $address->number }}</p>
                                    <p><span class="font-semibold">Colonia:</span> {{ $address->district }}</p>
                                    <p><span class="font-semibold">Ciudad:</span> {{ $address->city }}</p>
                                    <p><span class="font-semibold">Estado:</span> {{ $address->state }}</p>
                                    <p><span class="font-semibold">CP:</span> {{ $address->postal_code }}</p>
                                </div>

                                <div class="mt-4 flex gap-3">
                                    <a href="{{ route('profile.addresses.edit', $address) }}"
                                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1 rounded shadow">
                                        Editar
                                    </a>

                                    <form action="{{ route('profile.addresses.destroy', $address) }}" method="POST"
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta dirección?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-1 rounded shadow">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
