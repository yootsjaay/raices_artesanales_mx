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
            'historia_piezas' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'ubicacion_id' => 'nullable|exists:ubicaciones,id',
            'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'imagen_adicionales.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'weight' => 'required|numeric|min:0.01|max:9999.99',
            'length' => 'required|numeric|min:0.1|max:999.9',
            'width' => 'required|numeric|min:0.1|max:999.9',
            'height' => 'required|numeric|min:0.1|max:999.9',

            // Variantes
            'variants' => 'required|array|min:1',
            'variants.*.color' => 'required|string|max:50',
            'variants.*.size' => 'required|string|max:50',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.price_adjustment' => 'nullable|numeric',
        ]);

        if ($request->hasFile('imagen_principal')) {
            $validatedData['imagen_principal'] = $request->file('imagen_principal')->store('images/artesanias', 'public');
        }

        $additionalImagesPaths = [];
        if ($request->hasFile('imagen_adicionales')) {
            foreach ($request->file('imagen_adicionales') as $file) {
                $additionalImagesPaths[] = $file->store('images/artesanias/additional', 'public');
            }
        }
        $validatedData['imagen_adicionales'] = $additionalImagesPaths;

        $artesania = Artesania::create($validatedData);

        foreach ($request->variants as $variant) {
            $artesania->artesania_variants()->create([
                'color' => $variant['color'],
                'size' => $variant['size'],
                'stock' => $variant['stock'],
                'price_adjustment' => $variant['price_adjustment'] ?? 0,
            ]);
        }

        return redirect()->route('admin.artesanias.index')->with('success', 'Artesanía y variantes creadas correctamente.');
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

   
public function update(Request $request, Artesania $artesania)
{
    $validatedData = $request->validate([
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'precio' => 'required|numeric|min:0.01',
        'stock' => 'required|integer|min:0',
        'materiales' => 'nullable|string|max:255',
        'historia_piezas' => 'nullable|string',
        'categoria_id' => 'required|exists:categorias,id',
        'ubicacion_id' => 'nullable|exists:ubicaciones,id',
        'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        'imagen_adicionales.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        'weight' => 'required|numeric|min:0.01|max:9999.99',
        'length' => 'required|numeric|min:0.1|max:999.9',
        'width' => 'required|numeric|min:0.1|max:999.9',
        'height' => 'required|numeric|min:0.1|max:999.9',
    ]);

    // Imagen principal
    if ($request->hasFile('imagen_principal')) {
        if ($artesania->imagen_principal && Storage::disk('public')->exists($artesania->imagen_principal)) {
            Storage::disk('public')->delete($artesania->imagen_principal);
        }
        $validatedData['imagen_principal'] = $request->file('imagen_principal')->store('images/artesanias', 'public');
    } else {
        $validatedData['imagen_principal'] = $artesania->imagen_principal;
    }

    // Imágenes adicionales
    if ($request->hasFile('imagen_adicionales')) {
        $oldImages = (array) $artesania->imagen_adicionales;
        foreach ($oldImages as $img) {
            if (Storage::disk('public')->exists($img)) {
                Storage::disk('public')->delete($img);
            }
        }

        $newPaths = [];
        foreach ($request->file('imagen_adicionales') as $file) {
            $newPaths[] = $file->store('images/artesanias/additional', 'public');
        }
        $validatedData['imagen_adicionales'] = $newPaths;
    } else {
        $validatedData['imagen_adicionales'] = (array) $artesania->imagen_adicionales;
    }

    // Actualizar la artesanía
    $artesania->update($validatedData);

    // ===================== 🔥 VARIANTES ======================
    if ($request->has('variants')) {
        // Eliminar variantes existentes
        $artesania->artesania_variants()->delete();

        foreach ($request->variants as $variant) {
            if (!empty($variant['color']) && !empty($variant['size']) && isset($variant['stock'])) {
                $artesania->artesania_variants()->create([
                    'color' => $variant['color'],
                    'size' => $variant['size'],
                    'stock' => $variant['stock'],
                    'price_adjustment' => $variant['price_adjustment'] ?? 0,
                ]);
            }
        }
    }
    // ========================================================

    return redirect()->route('admin.artesanias.index')->with('success', 'Artesanía actualizada correctamente.');
}
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
