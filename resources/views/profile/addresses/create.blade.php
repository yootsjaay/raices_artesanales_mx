<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ __('Agregar Dirección') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow-sm">

                <form action="{{ route('profile.addresses.store') }}" method="POST" class="space-y-4">
                    @csrf

                    @include('profile.addresses.form')

                    <div class="flex justify-end gap-4 mt-6">
                        <a href="{{ route('profile.addresses.index') }}"
                           class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Cancelar</a>

                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Guardar dirección
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
