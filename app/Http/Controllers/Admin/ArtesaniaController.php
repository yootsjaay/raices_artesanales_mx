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
use App\Models\ArtesaniaVariant; // Asegúrate de que este modelo exista y esté correctamente definido
use App\Models\ImagenArtesania;
use App\Models\TipoEmbalaje;
use App\Models\ImagenArtesaniaVariant;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;


class ArtesaniaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __contruct(){
       

        }
public function index(): View
    {
        // Cargamos las relaciones categoria, ubicacion y variants de manera eficiente
        // para evitar el problema de N+1 queries en la vista.
        $artesanias = Artesania::with(['categoria', 'ubicacion', 'variants'])->get();

        return view('admin.artesanias.index', compact('artesanias'));
    }

  
    /**
     * Show the form for creating a new resource.
     */
 public function create()
{
    $categorias = Categoria::all();
    $ubicaciones = Ubicacion::all();
    $tipos_embalaje = TipoEmbalaje::all();
    $artesaniasVariants = ArtesaniaVariant::all();

    // Variante vacía para crear la primera variante en el formulario
    $variants = [[]];

    return view('admin.artesanias.create', compact('categorias', 'ubicaciones', 'variants', 'artesaniasVariants', 'tipos_embalaje'));
}

public function store(Request $request)
    {
        // 1. Validación de los datos
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'historia_piezas_general' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'ubicacion_id' => 'nullable|exists:ubicaciones,id',

            'precio' => 'required|numeric|min:0.01',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',

            'imagenes_artesanias.*' => 'nullable|image|max:10240',

            'variants' => 'required|array',
            'variants.*.variant_name' => 'required|string|max:255',
            'variants.*.sku' => 'nullable|string|unique:artesania_variants,sku|max:255',
            'variants.*.description_variant' => 'nullable|string',
            'variants.*.size' => 'nullable|string|max:255',
            'variants.*.color' => 'nullable|string|max:255',
            'variants.*.material_variant' => 'nullable|string|max:255',
            'variants.*.precio' => 'required|numeric|min:0.01',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.tipo_embalaje_id' => 'nullable|exists:tipos_embalaje,id',
            'variants.*.is_active' => 'nullable|boolean',

            'variants.*.new_variant_images.*' => 'nullable|image|max:10240',
        ]);
        
        // Iniciar una transacción de base de datos para asegurar la atomicidad
        DB::beginTransaction();

        try {
            // 2. Subir imágenes generales y crear el registro de Artesanía
            $imagenesArtesaniasUrls = [];
            if ($request->hasFile('imagenes_artesanias')) {
                foreach ($request->file('imagenes_artesanias') as $imageFile) {
                    $path = $imageFile->store('images/artesanias/general', 'public');
                    $imagenesArtesaniasUrls[] = Storage::url($path);
                }
            }

            $artesania = Artesania::create([
                'nombre'                  => $validatedData['nombre'],
                'descripcion'             => $validatedData['descripcion'] ?? null,
                'precio'                  => $validatedData['precio'],
                'weight'                  => $validatedData['weight'] ?? 0.00,
                'length'                  => $validatedData['length'] ?? 0.00,
                'width'                   => $validatedData['width'] ?? 0.00,
                'height'                  => $validatedData['height'] ?? 0.00,
                'imagen_artesanias'       => $imagenesArtesaniasUrls,
                'historia_piezas_general' => $validatedData['historia_piezas_general'] ?? null,
                'categoria_id'            => $validatedData['categoria_id'],
                'ubicacion_id'            => $validatedData['ubicacion_id'] ?? null,
            ]);

            // 3. Crear las variantes de la Artesanía
            if (isset($validatedData['variants'])) {
                foreach ($validatedData['variants'] as $index => $variantData) {
                    $imagenesVariantUrls = [];

                    if ($request->hasFile("variants.{$index}.new_variant_images")) {
                        foreach ($request->file("variants.{$index}.new_variant_images") as $imageFile) {
                            $path = $imageFile->store('images/artesanias/variants', 'public');
                            $imagenesVariantUrls[] = Storage::url($path);
                        }
                    }

                $artesania->variants()->create([
                    'sku' => $variantData['sku'] ?? $this->generateSku($artesania->id, $variantData),
                    'variant_name' => $variantData['variant_name'],
                    'description_variant' => $variantData['description_variant'] ?? null,
                    'size' => $variantData['size'] ?? null,
                    'color' => $variantData['color'] ?? null,
                    'material_variant' => $variantData['material_variant'] ?? null,
                    'precio' => $variantData['precio'],
                    'stock' => $variantData['stock'],
                    'imagen_variant' => $imagenesVariantUrls,
                    'tipo_embalaje_id' => $variantData['tipo_embalaje_id'] ?? null,
                    'is_active' => $variantData['is_active'] ?? true,
                ]);
            }
        }

            DB::commit();

            return redirect()->route('admin.artesanias.index')->with('success', 'Artesanía y variantes creadas correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear la artesanía: ' . $e->getMessage(), [
                'request' => $request->all(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->back()->withInput()->withErrors(['error' => 'Hubo un error al crear la artesanía y sus variantes. Por favor, verifica el log para más detalles.']);
        }
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
    // Decodificar imágenes generales si son string
    if (is_string($artesania->imagen_artesanias)) {
        $artesania->imagen_artesanias = json_decode($artesania->imagen_artesanias, true) ?? [];
    }

    // Decodificar imágenes por variante
    foreach ($artesania->variants as $variant) {
        if (is_string($variant->imagen_variant)) {
            $variant->imagen_variant = json_decode($variant->imagen_variant, true) ?? [];
        }
    }
    $categorias = Categoria::all();
    $ubicaciones = Ubicacion::all();
    $tipos_embalaje = TipoEmbalaje::all();
    $artesaniasVariants = ArtesaniaVariant::all();  

    return view('admin.artesanias.edit', compact(
        'artesania',
        'categorias',
        'ubicaciones',
        'tipos_embalaje',
        'artesaniasVariants'
    ));
}



    protected function generateSku($artesaniaId, $variantData)
    {
        // Ejemplo simple: ART-[ID_ARTESANIA]-[COLOR]-[SIZE]-[RANDOM]
        $sku = 'ART-' . $artesaniaId;
        if (!empty($variantData['color'])) {
            $sku .= '-' . Str::slug($variantData['color']);
        }
        if (!empty($variantData['size'])) {
            $sku .= '-' . Str::slug($variantData['size']);
        }
        $sku .= '-' . Str::random(4); // Añade un sufijo aleatorio para mayor unicidad
        return strtoupper($sku);
    }


    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Artesania $artesania)
{
    $validatedData = $request->validate([
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'historia_piezas_general' => 'nullable|string',
        'categoria_id' => 'required|exists:categorias,id',
        'ubicacion_id' => 'nullable|exists:ubicaciones,id',

        'precio' => 'required|numeric|min:0.01',
        'weight' => 'nullable|numeric|min:0',
        'length' => 'nullable|numeric|min:0',
        'width' => 'nullable|numeric|min:0',
        'height' => 'nullable|numeric|min:0',

        'imagenes_artesanias.*' => 'nullable|image|max:10240',

        'variants' => 'required|array',
        'variants.*.id' => 'nullable|exists:artesania_variants,id',
        'variants.*.variant_name' => 'required|string|max:255',
        'variants.*.sku' => 'nullable|string|max:255',
        'variants.*.description_variant' => 'nullable|string',
        'variants.*.size' => 'nullable|string|max:255',
        'variants.*.color' => 'nullable|string|max:255',
        'variants.*.material_variant' => 'nullable|string|max:255',
        'variants.*.precio' => 'required|numeric|min:0.01',
        'variants.*.stock' => 'required|integer|min:0',
        'variants.*.tipo_embalaje_id' => 'nullable|exists:tipos_embalaje,id',
        'variants.*.is_active' => 'nullable|boolean',
        'variants.*.new_variant_images.*' => 'nullable|image|max:10240',
    ]);

    DB::beginTransaction();

    try {
        // Aseguramos que sea array
        $imagenesArtesaniasUrls = [];

        if (is_array($artesania->imagen_artesanias)) {
            $imagenesArtesaniasUrls = $artesania->imagen_artesanias;
        } elseif (is_string($artesania->imagen_artesanias)) {
            $imagenesArtesaniasUrls = json_decode($artesania->imagen_artesanias, true) ?? [];
        }

        if ($request->hasFile('imagenes_artesanias')) {
            $imagenesArtesaniasUrls = [];
            foreach ($request->file('imagenes_artesanias') as $imageFile) {
                $path = $imageFile->store('images/artesanias/general', 'public');
                $imagenesArtesaniasUrls[] = Storage::url($path);
            }
        }

        $artesania->update([
            'nombre' => $validatedData['nombre'],
            'descripcion' => $validatedData['descripcion'] ?? null,
            'precio' => $validatedData['precio'],
            'weight' => $validatedData['weight'] ?? 0.00,
            'length' => $validatedData['length'] ?? 0.00,
            'width' => $validatedData['width'] ?? 0.00,
            'height' => $validatedData['height'] ?? 0.00,
            'imagen_artesanias' => $imagenesArtesaniasUrls,
            'historia_piezas_general' => $validatedData['historia_piezas_general'] ?? null,
            'categoria_id' => $validatedData['categoria_id'],
            'ubicacion_id' => $validatedData['ubicacion_id'] ?? null,
        ]);

        $variantsIdsEnviadas = [];

        foreach ($validatedData['variants'] as $index => $variantData) {
            $variantId = $variantData['id'] ?? null;
            $imagenesVariantUrls = [];

            if ($request->hasFile("variants.{$index}.new_variant_images")) {
                foreach ($request->file("variants.{$index}.new_variant_images") as $imageFile) {
                    $path = $imageFile->store('images/artesanias/variants', 'public');
                    $imagenesVariantUrls[] = Storage::url($path);
                }
            }

            if ($variantId) {
                $variant = $artesania->variants()->findOrFail($variantId);

                // Aseguramos que imagen_variant sea array
                $imagenesActuales = [];

                if (is_array($variant->imagen_variant)) {
                    $imagenesActuales = $variant->imagen_variant;
                } elseif (is_string($variant->imagen_variant)) {
                    $imagenesActuales = json_decode($variant->imagen_variant, true) ?? [];
                }

                $variant->update([
                    'sku' => $variantData['sku'] ?? $variant->sku ?? $this->generateSku($artesania->id, $variantData),
                    'variant_name' => $variantData['variant_name'],
                    'description_variant' => $variantData['description_variant'] ?? null,
                    'size' => $variantData['size'] ?? null,
                    'color' => $variantData['color'] ?? null,
                    'material_variant' => $variantData['material_variant'] ?? null,
                    'precio' => $variantData['precio'],
                    'stock' => $variantData['stock'],
                    'imagen_variant' => $imagenesVariantUrls ?: $imagenesActuales,
                    'tipo_embalaje_id' => $variantData['tipo_embalaje_id'] ?? null,
                    'is_active' => $variantData['is_active'] ?? true,
                ]);

                $variantsIdsEnviadas[] = $variant->id;
            } else {
                $newVariant = $artesania->variants()->create([
                    'sku' => $variantData['sku'] ?? $this->generateSku($artesania->id, $variantData),
                    'variant_name' => $variantData['variant_name'],
                    'description_variant' => $variantData['description_variant'] ?? null,
                    'size' => $variantData['size'] ?? null,
                    'color' => $variantData['color'] ?? null,
                    'material_variant' => $variantData['material_variant'] ?? null,
                    'precio' => $variantData['precio'],
                    'stock' => $variantData['stock'],
                    'imagen_variant' => $imagenesVariantUrls,
                    'tipo_embalaje_id' => $variantData['tipo_embalaje_id'] ?? null,
                    'is_active' => $variantData['is_active'] ?? true,
                ]);
                $variantsIdsEnviadas[] = $newVariant->id;
            }
        }

        $artesania->variants()->whereNotIn('id', $variantsIdsEnviadas)->delete();

        DB::commit();

        return redirect()->route('admin.artesanias.index')->with('success', 'Artesanía actualizada correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withInput()->withErrors(['error' => 'Error al actualizar: ' . $e->getMessage()]);
    }
}

public function destroy(Artesania $artesania)
{
    DB::beginTransaction();

    try {
        // 1. Eliminar imágenes generales de la artesanía (del campo imagen_artesanias JSON)
        if ($artesania->imagen_artesanias) {
            $imagenesGenerales = $artesania->imagen_artesanias;

            if (is_string($imagenesGenerales)) {
                $imagenesGenerales = json_decode($imagenesGenerales, true);
            }

            if (is_array($imagenesGenerales)) {
                foreach ($imagenesGenerales as $ruta) {
                    $rutaLimpia = str_replace('/storage/', '', $ruta);
                    if (Storage::disk('public')->exists($rutaLimpia)) {
                        Storage::disk('public')->delete($rutaLimpia);
                    }
                }
            }
        }

        // 2. Eliminar variantes y sus imágenes (JSON)
        foreach ($artesania->variants as $variant) {
            if ($variant->imagen_variant) {
                $imagenes = $variant->imagen_variant;

                if (is_string($imagenes)) {
                    $imagenes = json_decode($imagenes, true);
                }

                if (is_array($imagenes)) {
                    foreach ($imagenes as $ruta) {
                        $rutaLimpia = str_replace('/storage/', '', $ruta);
                        if (Storage::disk('public')->exists($rutaLimpia)) {
                            Storage::disk('public')->delete($rutaLimpia);
                        }
                    }
                }
            }

            // Eliminar la variante
            $variant->delete();
        }

        // 3. Eliminar la artesanía en sí
        $artesania->delete();

        DB::commit();

        return redirect()->route('admin.artesanias.index')
                         ->with('success', 'Artesanía eliminada exitosamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error al eliminar la artesanía: ' . $e->getMessage());
    }
}

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
