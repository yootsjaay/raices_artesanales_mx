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
   public function create()
    {
        $categorias = Categoria::all();
        $ubicaciones = Ubicacion::all();
        return view('admin.artesanias.create', compact('categorias', 'ubicaciones'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
            'materiales' => 'nullable|string|max:255',
            'historia_piezas' => 'nullable|string', // Confirma si es 'historia_piezas' o 'historia_pieza'
            'categoria_id' => 'required|exists:categorias,id',
            'ubicacion_id' => 'nullable|exists:ubicaciones,id',
           'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'imagen_adicionales.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',

            // --- VALIDACIÓN DE LOS NUEVOS CAMPOS DE DIMENSIONES Y PESO ---
            'weight' => 'required|numeric|min:0.01|max:9999.99', // Ajusta max si necesitas más
            'length' => 'required|numeric|min:0.1|max:999.9',    // Ajusta max si necesitas más
            'width' => 'required|numeric|min:0.1|max:999.9',
            'height' => 'required|numeric|min:0.1|max:999.9',
            // -----------------------------------------------------------
        ]);

        // Manejo de la imagen principal
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
        $validatedData['imagen_adicionales'] = ($additionalImagesPaths);



        Artesania::create($validatedData);

        return redirect()->route('admin.artesanias.index')->with('success', 'Artesanía creada correctamente.');
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
        $categorias = Categoria::all();
        $ubicaciones = Ubicacion::all();
        return view('admin.artesanias.edit', compact('artesania', 'categorias', 'ubicaciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Artesania $artesania)
{
    $validatedData = $request->validate([
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'precio' => 'required|numeric|min:0.01',
        'stock' => 'required|integer|min:0',
        'materiales' => 'nullable|string|max:255',
        'historia_piezas' => 'nullable|string', // Confirma este nombre
        'categoria_id' => 'required|exists:categorias,id',
        'ubicacion_id' => 'nullable|exists:ubicaciones,id',
        'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        'imagen_adicionales.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',

        'weight' => 'required|numeric|min:0.01|max:9999.99',
        'length' => 'required|numeric|min:0.1|max:999.9',
        'width' => 'required|numeric|min:0.1|max:999.9',
        'height' => 'required|numeric|min:0.1|max:999.9',
    ]);

    // 2. Manejo de la IMAGEN PRINCIPAL
    if ($request->hasFile('imagen_principal')) {
        // Elimina la imagen principal antigua si existe
        if ($artesania->imagen_principal && Storage::disk('public')->exists($artesania->imagen_principal)) {
            Storage::disk('public')->delete($artesania->imagen_principal);
        }
        // Almacena la nueva imagen principal
        $validatedData['imagen_principal'] = $request->file('imagen_principal')->store('images/artesanias', 'public');
    } else {
        // Si no se sube una nueva imagen principal, mantener la existente
        $validatedData['imagen_principal'] = $artesania->imagen_principal;
    }

  // 3. Manejo de las IMÁGENES ADICIONALES
if ($request->hasFile('imagen_adicionales')) {
    $newAdditionalImagesPaths = [];
    // Eliminar todas las imágenes adicionales antiguas asociadas a esta artesanía
    // ¡AHORA $artesania->imagen_adicionales YA ES UN ARRAY GRACIAS AL CASTING DEL MODELO!
    // No necesitamos json_decode() ni is_array() aquí.

    // Aseguramos que sea un array, incluso si es null en la DB.
    $existingImages = (array) $artesania->imagen_adicionales; // <--- Cambio aquí

    if (!empty($existingImages)) { // Ahora verificamos que el array no esté vacío
        foreach ($existingImages as $oldPath) {
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }
    }
    // Almacenar las nuevas imágenes adicionales
    foreach ($request->file('imagen_adicionales') as $file) {
        $newAdditionalImagesPaths[] = $file->store('images/artesanias/additional', 'public');
    }
    $validatedData['imagen_adicionales'] = $newAdditionalImagesPaths;
} else {
    // Si no se suben nuevas imágenes adicionales, mantener las existentes
    // También asegúrate de que esto sea siempre un array para la DB.
    $validatedData['imagen_adicionales'] = (array) $artesania->imagen_adicionales; // <--- Posible cambio aquí también
}

        $artesania->update($validatedData);

        return redirect()->route('admin.artesanias.index')->with('success', 'Artesanía actualizada correctamente.');
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
    // ¡AHORA $artesania->imagen_adicionales YA ES UN ARRAY GRACIAS AL CASTING DEL MODELO!
    // No necesitamos json_decode() ni is_array() aquí.
    if ($artesania->imagen_adicionales) { // Esto verifica que no sea null o un array vacío
        foreach ($artesania->imagen_adicionales as $imagePath) {
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
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
