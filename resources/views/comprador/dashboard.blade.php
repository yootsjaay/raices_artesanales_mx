<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel del Comprador') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-6 bg-white shadow sm:rounded-lg">
                    <h3 class="text-xl font-semibold mb-2">Mis Órdenes</h3>
                    <p class="mb-3 text-gray-700">Consulta tu historial de compras.</p>
                    <a href="#" class="text-blue-600 hover:underline">Ver órdenes</a>
                </div>

                <div class="p-6 bg-white shadow sm:rounded-lg">
                    <h3 class="text-xl font-semibold mb-2">Direcciones guardadas</h3>
                    <p class="mb-3 text-gray-700">Administra tus direcciones de envío.</p>
                    <a href="{{ route('profile.addresses.index') }}" class="text-blue-600 hover:underline">Ver direcciones</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
