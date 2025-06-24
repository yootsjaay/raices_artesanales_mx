<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artesania;
use App\Models\Categoria;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel; // <-- ¡Asegúrate de que esta línea esté presente!
use App\Imports\ArtesaniasImport;
use Spatie\Activitylog\Models\Activity; // Importa Activitylog
use Spatie\Permission\Exceptions\UnauthorizedException;
use Maatwebsite\Excel\Validators\ValidationException;
//

class ArtesaniaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __contruct(){
        // Protege el controlador con permisos específicos
       /* $this->middleware('permission:crear artesanias')->only(['create', 'store']);
        $this->middleware('permission:editar artesanias')->only(['edit', 'update']);
        $this->middleware('permission:eliminar artesanias')->only(['destroy']);*/

        }
    public function index()
    {
        $artesanias=Artesania::with('categoria','ubicacion')->get();

        return view('admin.artesanias.index',compact('artesanias'));
    }

    /**
     * Show the form for creating a new resource.
     */
 

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all(); // Necesitamos las categorías
        $ubicaciones = Ubicacion::all(); // Necesitamos las ubicaciones
        return view('admin.artesanias.create', compact('categorias', 'ubicaciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0|max:9999999.99', // Precio con decimales
            'stock' => 'required|integer|min:0',

            // --- REGLAS DE IMAGEN PRINCIPAL (REQUERIDA AL CREAR) ---
            'imagen_principal' => [
                'required', // <-- Es OBLIGATORIA al crear una nueva artesanía
                'image',
                'mimes:jpeg,png,jpg,gif,webp,svg', // Tipos de archivo permitidos
                'max:51200', // 50MB como tamaño máximo de archivo
                // 'dimensions:min_width=3840,min_height=2160', // Mantener esta línea COMENTADA si no quieres forzar 4K
            ],
            // --- REGLAS DE IMÁGENES ADICIONALES (OPCIONALES AL CREAR) ---
            'imagen_adicionales.*' => [ // Nota el '.*' para validar cada archivo en un array
                'nullable', // Es OPCIONAL al crear, no se requiere subir imágenes adicionales
                'image',
                'mimes:jpeg,png,jpg,gif,webp,svg', // Tipos de archivo permitidos
                'max:51200', // 50MB por imagen adicional
                // 'dimensions:min_width=3840,min_height=2160', // Mantener esta línea COMENTADA
            ],
            // --- FIN REGLAS DE IMAGENES ---

            'materiales' => 'nullable|string|max:255',
            'dimensiones' => 'nullable|string|max:255',
            'historia_pieza' => 'nullable|string',
            'tecnica_empleada' => 'nullable|string|max:255',
            'categoria_id' => [
                'required',
                Rule::exists('categorias', 'id'), // Asegura que la categoría seleccionada exista
            ],
            'ubicacion_id' => [
                'required',
                Rule::exists('ubicaciones', 'id'), // Asegura que la ubicación seleccionada exista
            ],
        ]);

        // Manejo de la IMAGEN PRINCIPAL
        $imagenPrincipalPath = null;
        if ($request->hasFile('imagen_principal')) {
            $imagenPrincipalPath = $request->file('imagen_principal')->store('images/artesanias', 'public');
        }
        $validatedData['imagen_principal'] = $imagenPrincipalPath;

        // Manejo de las IMÁGENES ADICIONALES
        $additionalImagesPaths = [];
        if ($request->hasFile('imagen_adicionales')) {
            foreach ($request->file('imagen_adicionales') as $file) {
                $additionalImagesPaths[] = $file->store('images/artesanias/additional', 'public');
            }
        }
        // Guardar las rutas como un string JSON en la base de datos
        $validatedData['imagen_adicionales'] = json_encode($additionalImagesPaths);


        // Crear la nueva Artesanía en la base de datos
        Artesania::create($validatedData);

        // Redireccionar al listado de artesanías con un mensaje de éxito
        return redirect()->route('admin.artesanias.index')
                         ->with('success', 'Artesanía creada exitosamente.');
    }

    /**
     * Display the specified resource. (Generalmente no se usa para admin, o se hace una vista simple)
     */
    public function show(Artesania $artesania)
    {
        // Puedes redirigir a la vista pública o crear una vista 'admin.artesanias.show' si necesitas más detalles de admin
        return view('admin.artesanias.show', compact('artesania'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Artesania $artesania)
    {
        $categorias=Categoria::all();
        $ubicaciones=Ubicacion::all();

        return view('admin.artesanias.edit', compact('artesania', 'categorias', 'ubicaciones'));
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, Artesania $artesania)
    {
        // 1. Validación de los datos del formulario
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0|max:9999999.99', // Añadí un máximo para precio
            'stock' => 'required|integer|min:0',
            'imagen_principal' => [
                        'nullable', // O 'required' si es para el método store
                        'image',
                        'mimes:jpeg,png,jpg,gif,webp,svg', // Añadí SVG, si es necesario, y WEBP por eficiencia
                        'max:51200', // ¡Aumentado a 50MB! Esto permite imágenes muy grandes.
            ],
            'imagen_adicionales.*' => [
                        'nullable',
                        'image',
                        'mimes:jpeg,png,jpg,gif,webp,svg', // Añadí SVG, si es necesario
                        'max:51200', // 50MB por imagen adicional.
                        // 'dimensions:min_width=X,min_height=Y', // Mantener esta línea SIEMPRE COMENTADA
                    ],
                    'materiales' => 'nullable|string|max:255',
            'dimensiones' => 'nullable|string|max:255',
            'historia_pieza' => 'nullable|string',
            'tecnica_empleada' => 'nullable|string|max:255',
            'categoria_id' => [
                'required',
                Rule::exists('categorias', 'id'), // Asegura que la categoría exista
            ],
            'ubicacion_id' => [
                'required',
                Rule::exists('ubicaciones', 'id'), // Asegura que la ubicación exista
            ],
        ]);

        // 2. Manejo de la IMAGEN PRINCIPAL
        if ($request->hasFile('imagen_principal')) {
            // Eliminar la imagen principal antigua si existe
            if ($artesania->imagen_principal && Storage::disk('public')->exists($artesania->imagen_principal)) {
                Storage::disk('public')->delete($artesania->imagen_principal);
            }
            // Almacenar la nueva imagen principal
            $validatedData['imagen_principal'] = $request->file('imagen_principal')->store('images/artesanias', 'public');
        } else {
            // Si no se sube una nueva imagen principal, mantener la existente
            $validatedData['imagen_principal'] = $artesania->imagen_principal;
        }

        // 3. Manejo de las IMÁGENES ADICIONALES
        if ($request->hasFile('imagen_adicionales')) {
            $newAdditionalImagesPaths = [];
            // Eliminar todas las imágenes adicionales antiguas asociadas a esta artesanía
            if ($artesania->imagen_adicionales) {
                $oldAdditionalImages = json_decode($artesania->imagen_adicionales, true);
                if (is_array($oldAdditionalImages)) { // Asegurarse de que sea un array
                    foreach ($oldAdditionalImages as $oldPath) {
                        if (Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                }
            }
            // Almacenar las nuevas imágenes adicionales
            foreach ($request->file('imagen_adicionales') as $file) {
                $newAdditionalImagesPaths[] = $file->store('images/artesanias/additional', 'public');
            }
            $validatedData['imagen_adicionales'] = json_encode($newAdditionalImagesPaths);
        } else {
            // Si no se suben nuevas imágenes adicionales, mantener las existentes
            $validatedData['imagen_adicionales'] = $artesania->imagen_adicionales;
        }

        // 4. Actualizar los datos de la artesanía
        $artesania->update($validatedData);

        // 5. Redireccionar con un mensaje de éxito
        return redirect()->route('admin.artesanias.index')
                         ->with('success', 'Artesanía actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy(Artesania $artesania)
    {
        // 1. Eliminar la imagen principal asociada
        if ($artesania->imagen_principal) {
            // Verifica si el archivo existe antes de intentar eliminarlo
            if (Storage::disk('public')->exists($artesania->imagen_principal)) {
                Storage::disk('public')->delete($artesania->imagen_principal);
            }
        }

        // 2. Eliminar las imágenes adicionales asociadas
        if ($artesania->imagen_adicionales) {
            // Decodifica la cadena JSON a un array de rutas
            $additionalImages = json_decode($artesania->imagen_adicionales, true);

            // Asegurarse de que sea un array y recorrerlo para eliminar cada imagen
            if (is_array($additionalImages)) {
                foreach ($additionalImages as $imagePath) {
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }
            }
        }

        // 3. Eliminar la artesanía de la base de datos
        $artesania->delete();

        // 4. Redireccionar al listado con un mensaje de éxito
        return redirect()->route('admin.artesanias.index')
                         ->with('success', 'Artesanía eliminada exitosamente.');
    }

    // ... (métodos index, create, store, show, edit, update, destroy existentes)

    /**
     * Show the form for importing Artesanías.
     * Muestra el formulario para subir un archivo Excel.
     */
    public function importForm()
    {
        return view('admin.artesanias.import'); // Crearemos esta vista en el siguiente paso
    }

    /**
     * Import Artesanías from an Excel file.
     * Procesa el archivo Excel subido.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048', // Valida el archivo Excel
        ]);

        try {
            Excel::import(new ArtesaniasImport, $request->file('file'));

            return redirect()->route('admin.artesanias.index')
                             ->with('success', 'Artesanías importadas exitosamente.');
        } catch (ValidationException $e) {
            // Captura los errores de validación específicos del Excel
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = 'Fila ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return redirect()->back()
                             ->with('error', 'Hubo errores de validación al importar las artesanías.')
                             ->withErrors(['import_errors' => $errors])
                             ->withInput();
        } catch (\Exception $e) {
            // Captura cualquier otra excepción
            return redirect()->back()
                             ->with('error', 'Ocurrió un error inesperado durante la importación: ' . $e->getMessage())
                             ->withInput();
        }
    }

}
