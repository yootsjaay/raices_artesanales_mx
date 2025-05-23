<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Importar Artesanías desde Excel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold">Subir Archivo de Artesanías</h3>
                        <a href="{{ route('admin.artesanias.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Volver a Artesanías
                        </a>
                    </div>

                    {{-- Mensajes de error de importación --}}
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                            @if ($errors->has('import_errors'))
                                <ul class="mt-3 list-disc list-inside text-sm">
                                    @foreach ($errors->get('import_errors') as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif

                    <form action="{{ route('admin.artesanias.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="file" class="block text-sm font-medium text-gray-700">Seleccionar archivo Excel (.xlsx, .xls, .csv):</label>
                            <input type="file" name="file" id="file"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('file')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <p class="text-sm text-gray-600 mb-4">
                            **Formato de archivo Excel esperado:**
                            Asegúrate de que la primera fila contenga los siguientes encabezados de columna (sin mayúsculas ni espacios):
                            `nombre`, `precio`, `stock`, `descripcion`, `imagen_principal`, `imagen_adicionales`, `categoria`, `ubicacion`
                            <br>
                            * `categoria` y `ubicacion` deben coincidir exactamente con los nombres de categorías y ubicaciones ya existentes en el sistema.
                            * `imagen_principal` y `imagen_adicionales` son rutas (ej. `images/artesanias/mi-imagen.jpg`). Los archivos de imagen deben ser subidos manualmente o por otro proceso.
                        </p>

                        <div class="flex justify-end space-x-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Importar Artesanías
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>