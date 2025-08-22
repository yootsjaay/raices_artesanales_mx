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
use App\Models\Atributo;
use App\Models\AtributoArtesaniaVariant;


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
    $atributos=Atributo::all();

    // Variante vacía para crear la primera variante en el formulario
    $variants = [[]];

    return view('admin.artesanias.create', compact('categorias', 'ubicaciones', 'variants', 'artesaniasVariants',
    'atributos', 'tipos_embalaje'));
}
 public function store(Request $request)
    {
        Log::debug('Datos recibidos para el almacenamiento de la artesanía:', $request->all());

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'ubicacion_id' => 'nullable|exists:ubicaciones,id',
            'precio' => 'required|numeric|min:0.01',
            'imagenes_artesanias.*' => 'nullable|image|max:10240',
            'variants' => 'required|array',
            'variants.*.variant_name' => 'required|string|max:255',
            'variants.*.precio' => 'required|numeric|min:0.01',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.tipo_embalaje_id' => 'nullable|exists:tipos_embalaje,id',
            'variants.*.is_active' => 'nullable|boolean',
            'variants.*.new_variant_images.*' => 'nullable|image|max:10240',
            'variants.*.attributes' => 'nullable|array',
            'variants.*.attributes.*.valor' => 'required|string|max:255',
            'variants.*.attributes.*.atributo_id' => 'nullable|exists:atributo,id',
            'variants.*.attributes.*.new_attribute_name' => 'nullable|string|max:255',
        ]);
        
        DB::beginTransaction();
    
        try {
            // Subir imágenes generales y crear la Artesanía
            $imagenesArtesaniasUrls = [];
            if ($request->hasFile('imagenes_artesanias')) {
                foreach ($request->file('imagenes_artesanias') as $imageFile) {
                    $path = $imageFile->store('images/artesanias/general', 'public');
                    $imagenesArtesaniasUrls[] = Storage::url($path);
                }
            }
    
            $artesania = Artesania::create([
                'nombre' => $validatedData['nombre'],
                'slug' => Str::slug($validatedData['nombre']),
                'descripcion' => $validatedData['descripcion'] ?? null,
                'precio' => $validatedData['precio'],
                'imagen_artesanias' => $imagenesArtesaniasUrls,
                'categoria_id' => $validatedData['categoria_id'],
                'ubicacion_id' => $validatedData['ubicacion_id'] ?? null,
                'is_active' => true,
            ]);
    
            // Crear las variantes
            if (isset($validatedData['variants'])) {
                foreach ($validatedData['variants'] as $index => $variantData) {
                    $imagenesVariantUrls = [];
                    if ($request->hasFile("variants.{$index}.new_variant_images")) {
                        foreach ($request->file("variants.{$index}.new_variant_images") as $imageFile) {
                            $path = $imageFile->store('images/artesanias/variants', 'public');
                            $imagenesVariantUrls[] = Storage::url($path);
                        }
                    }
                    
                    $artesaniaVariant = $artesania->variants()->create([
                        'sku' => $this->generateSku($artesania->id, $variantData),
                        'variant_name' => $variantData['variant_name'],
                        'precio' => $variantData['precio'],
                        'stock' => $variantData['stock'],
                        'imagen_variant' => $imagenesVariantUrls,
                        'tipo_embalaje_id' => $variantData['tipo_embalaje_id'] ?? null,
                        'is_active' => $variantData['is_active'] ?? true,
                    ]);
    
                    // Crear los atributos de la variante
                    if (isset($variantData['attributes'])) {
                        foreach ($variantData['attributes'] as $attributeData) {
                            $atributo = null;
                            if (!empty($attributeData['atributo_id'])) {
                                // El usuario seleccionó un atributo existente
                                $atributo = Atributo::find($attributeData['atributo_id']);
                            } elseif (!empty($attributeData['new_attribute_name'])) {
                                // El usuario creó un nuevo atributo
                                $atributo = Atributo::firstOrCreate(
                                    ['nombre' => $attributeData['new_attribute_name']]
                                );
                            }
    
                            if ($atributo) {
                                AtributoArtesaniaVariant::create([
                                    'artesania_variant_id' => $artesaniaVariant->id,
                                    'atributo_id' => $atributo->id,
                                    'valor' => $attributeData['valor'],
                                ]);
                            }
                        }
                    }
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
            
            $artesania->load('variants.atributos', 'categoria', 'ubicacion', 'variants.tipoEmbalaje');

            // Obtener las colecciones de apoyo para los selectores del formulario
            $categorias = Categoria::all();
            $ubicaciones = Ubicacion::all();
            $tipos_embalaje = TipoEmbalaje::all();
            $atributos = Atributo::all();
            
        
            
            return view('admin.artesanias.edit', compact('artesania', 'categorias', 'ubicaciones', 'atributos', 'tipos_embalaje'));
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
        'categoria_id' => 'required|integer|exists:categorias,id',
        'ubicacion_id' => 'nullable|integer|exists:ubicaciones,id',
        'precio' => 'required|numeric|min:0',
        'imagenes_artesanias.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',

        'variants.*.id' => 'sometimes|integer|exists:artesania_variants,id',
        'variants.*.variant_name' => 'nullable|string|max:255',
        'variants.*.sku' => 'nullable|string|max:255', // validación se hace manual abajo
        'variants.*.precio' => 'nullable|numeric|min:0',
        'variants.*.stock' => 'nullable|integer|min:0',
        'variants.*.tipo_embalaje_id' => 'nullable|integer|exists:tipos_embalaje,id',
        'variants.*.imagenes_variant.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',

        'delete_general_images' => 'nullable|array',
        'delete_general_images.*' => 'string',
        'delete_variant_images' => 'nullable|array',
        'delete_variant_images.*.*' => 'string',

        'variants.*.attributes.*.id' => 'sometimes|nullable|integer|exists:atributo,id',
        'variants.*.attributes.*.valor' => 'nullable|string',
    ]);

    DB::beginTransaction();

    try {
        // ==== actualizar artesanía principal ====
        $artesania->fill([
            'nombre' => $validatedData['nombre'],
            'slug' => Str::slug($validatedData['nombre']),
            'descripcion' => $validatedData['descripcion'],
            'historia_piezas_general' => $validatedData['historia_piezas_general'],
            'categoria_id' => $validatedData['categoria_id'],
            'ubicacion_id' => $validatedData['ubicacion_id'],
            'precio' => $validatedData['precio'],
        ]);

        // === imágenes principales ===
        $existingImages = is_string($artesania->imagen_artesanias)
            ? json_decode($artesania->imagen_artesanias, true) ?? []
            : (is_array($artesania->imagen_artesanias) ? $artesania->imagen_artesanias : []);

        foreach ($request->input('delete_general_images', []) as $imageUrl) {
            $imagePath = Str::after($imageUrl, 'storage/');
            Storage::delete('public/' . $imagePath);
            $existingImages = array_filter($existingImages, fn($url) => $url !== $imageUrl);
        }

        $uploadedImages = [];
        if ($request->hasFile('imagenes_artesanias')) {
            foreach ($request->file('imagenes_artesanias') as $file) {
                $path = $file->store('public/images/artesanias/general');
                $uploadedImages[] = Storage::url($path);
            }
        }

        $artesania->imagen_artesanias = json_encode(array_values(array_merge($existingImages, $uploadedImages)));
        $artesania->save();

        // === Variantes ===
        $submittedVariants = $request->input('variants', []);
        $submittedVariantIds = collect($submittedVariants)->pluck('id')->filter()->all();

        // eliminar variantes que ya no existen en request
        $artesania->variants()->whereNotIn('id', $submittedVariantIds)->delete();

        foreach ($submittedVariants as $variantData) {
            $variant = isset($variantData['id'])
                ? ArtesaniaVariant::findOrFail($variantData['id'])
                : new ArtesaniaVariant(['artesania_id' => $artesania->id]);

            // validar SKU único manualmente
            if (!empty($variantData['sku'])) {
                $exists = ArtesaniaVariant::where('sku', $variantData['sku'])
                    ->where('id', '!=', $variant->id)
                    ->exists();
                if ($exists) {
                    throw new \Exception("El SKU {$variantData['sku']} ya está en uso.");
                }
            }

            $variant->fill([
                'sku' => $variantData['sku'] ?? Str::upper(Str::random(8)),
                'variant_name' => $variantData['variant_name'] ?? null,
                'precio' => $variantData['precio'] ?? null,
                'stock' => $variantData['stock'] ?? null,
                'tipo_embalaje_id' => $variantData['tipo_embalaje_id'] ?? null,
            ]);

            // imágenes de variante
            $existingVariantImages = is_string($variant->imagen_variant)
                ? json_decode($variant->imagen_variant, true) ?? []
                : (is_array($variant->imagen_variant) ? $variant->imagen_variant : []);

            if ($request->has("delete_variant_images.{$variant->id}")) {
                foreach ($request->input("delete_variant_images.{$variant->id}") as $imageUrl) {
                    $imagePath = Str::after($imageUrl, 'storage/');
                    Storage::delete('public/' . $imagePath);
                    $existingVariantImages = array_filter($existingVariantImages, fn($url) => $url !== $imageUrl);
                }
            }

            $uploadedVariantImages = [];
            if (isset($variantData['imagenes_variant'])) {
                foreach ($variantData['imagenes_variant'] as $file) {
                    $path = $file->store('public/images/artesanias/variantes');
                    $uploadedVariantImages[] = Storage::url($path);
                }
            }

            $variant->imagen_variant = json_encode(array_values(array_merge($existingVariantImages, $uploadedVariantImages)));
            $variant->save();

            // === atributos ===
            $submittedAttributes = $variantData['attributes'] ?? [];

            $keepAttributeIds = [];
            foreach ($submittedAttributes as $attr) {
                $atributo_id = $attr['id'];
                $valor = $attr['valor'] ?? null;

                $existing = AtributoArtesaniaVariant::where('artesania_variant_id', $variant->id)
                    ->where('atributo_id', $atributo_id)
                    ->first();

                if ($existing) {
                    $existing->update(['valor' => $valor]);
                    $keepAttributeIds[] = $existing->id;
                } else {
                    $newAttr = $variant->atributos()->create([
                        'atributo_id' => $atributo_id,
                        'valor' => $valor,
                    ]);
                    $keepAttributeIds[] = $newAttr->id;
                }
            }

            // eliminar atributos que no vinieron en el request
            AtributoArtesaniaVariant::where('artesania_variant_id', $variant->id)
                ->whereNotIn('id', $keepAttributeIds)
                ->delete();
        }

        DB::commit();
        return redirect()->route('admin.artesanias.index')->with('success', 'Artesanía actualizada con éxito.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Hubo un problema al actualizar la artesanía: ' . $e->getMessage());
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
