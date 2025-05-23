<?php

namespace App\Imports;

use App\Models\Artesania;
use App\Models\Categoria;
use App\Models\Ubicacion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable; // <-- ¡Asegúrate de que esta línea esté presente!
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ArtesaniasImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable; // <-- Y que el 'use Importable;' aquí esté bien escrito.

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // NOTA: Para imagen_principal e imagen_adicionales, este método `model`
        // NO carga archivos. Solo guarda las rutas de los archivos si ya están subidos,
        // o si los path están en el excel y esos archivos ya existen en storage.
        // La importación masiva de imágenes *no* es estándar para Excel,
        // normalmente se importan los datos y luego se suben las imágenes manualmente o por otro proceso.
        // Aquí asumiremos que 'imagen_principal' y 'imagen_adicionales' son solo STRINGS de rutas
        // o que se manejarán después de la importación de datos.

        // Si quieres relacionar por nombre, primero busca la categoría y ubicación
        $categoria = Categoria::where('nombre', $row['categoria'])->first();
        $ubicacion = Ubicacion::where('nombre', $row['ubicacion'])->first();

        // Puedes añadir validación o manejo de errores si la categoría/ubicación no existen
        if (!$categoria) {
            // Manejar error, por ejemplo, loguear o lanzar una excepción
            Log::warning("Categoría no encontrada para la artesanía: " . $row['nombre'] . " Categoría: " . $row['categoria']);
            return null; // O throw new \Exception('Categoría no encontrada');
        }
        if (!$ubicacion) {
            // Manejar error
            Log::warning("Ubicación no encontrada para la artesanía: " . $row['nombre'] . " Ubicación: " . $row['ubicacion']);
            return null; // O throw new \Exception('Ubicación no encontrada');
        }

        return new Artesania([
            'nombre' => $row['nombre'],
            'precio' => $row['precio'],
            'stock' => $row['stock'],
            'descripcion' => $row['descripcion'],
            'imagen_principal' => $row['imagen_principal'] ?? null, // Asume que el Excel tiene la ruta
            'imagen_adicionales' => $row['imagen_adicionales'] ?? null, // Asume JSON string o null
            'categoria_id' => $categoria->id,
            'ubicacion_id' => $ubicacion->id,
            // Agrega cualquier otro campo que tu modelo Artesania tenga y quieras importar
        ]);
    }

    // Reglas de validación para cada fila/columna del Excel
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'descripcion' => 'nullable|string',
            'imagen_principal' => 'nullable|string', // Aquí se espera la ruta, no el archivo
            'imagen_adicionales' => 'nullable|string', // Se espera un JSON string de rutas
            'categoria' => 'required|string|exists:categorias,nombre', // Validar que la categoría exista por nombre
            'ubicacion' => 'required|string|exists:ubicaciones,nombre', // Validar que la ubicación exista por nombre
        ];
    }

    /**
     * Personalizar mensajes de error de validación (opcional)
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'precio.required' => 'El campo Precio es obligatorio.',
            'precio.numeric' => 'El Precio debe ser un número.',
            'stock.required' => 'El campo Stock es obligatorio.',
            'stock.integer' => 'El Stock debe ser un número entero.',
            'categoria.required' => 'El campo Categoría es obligatorio.',
            'categoria.exists' => 'La Categoría no existe en la base de datos.',
            'ubicacion.required' => 'El campo Ubicación es obligatorio.',
            'ubicacion.exists' => 'La Ubicación no existe en la base de datos.',
        ];
    }
}