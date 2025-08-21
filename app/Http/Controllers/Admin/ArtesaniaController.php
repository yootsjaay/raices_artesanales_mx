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
        Log::debug('Datos recibidos para la actualización de la artesanía:', $request->all());

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'ubicacion_id' => 'nullable|exists:ubicaciones,id',
            'precio' => 'required|numeric|min:0.01',
            'historia_piezas_general' => 'nullable|string',
            'imagenes_artesanias.*' => 'nullable|image|max:10240',
            'delete_general_images' => 'nullable|array',
            'delete_general_images.*' => 'string',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:artesania_variants,id',
            'variants.*.sku' => 'nullable|string|max:255',
            'variants.*.variant_name' => 'required|string|max:255',
            'variants.*.descripcion_variant' => 'nullable|string',
            'variants.*.precio' => 'required|numeric|min:0.01',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.tipo_embalaje_id' => 'nullable|exists:tipos_embalaje,id',
            'variants.*.is_active' => 'nullable|boolean',
            'variants.*.imagenes_variant.*' => 'nullable|image|max:10240',
            'delete_variant_images' => 'nullable|array',
            'delete_variant_images.*' => 'array',
            'delete_variant_images.*.*' => 'string',
            'variants.*.attributes' => 'nullable|array',
            'variants.*.attributes.*.id' => 'nullable|exists:atributo_artesania_variant,id',
            'variants.*.attributes.*.name' => 'nullable|string|max:255',
            'variants.*.attributes.*.value' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // 1. Manejo de imágenes generales
            $artesaniaImages = $artesania->imagen_artesanias;

            if (!is_array($artesaniaImages)) {
                $artesaniaImages = [];
            }
            
            // Eliminar imágenes generales que el usuario marcó para eliminar
            if ($request->has('delete_general_images')) {
                foreach ($request->input('delete_general_images') as $urlToDelete) {
                    $path = str_replace(url('/'), 'public', $urlToDelete);
                    if (Storage::exists($path)) {
                        Storage::delete($path);
                    }
                    $artesaniaImages = array_values(array_filter($artesaniaImages, function($url) use ($urlToDelete) {
                        return $url !== $urlToDelete;
                    }));
                }
            }
            
           // Reemplazar o añadir nuevas imágenes generales
        if ($request->hasFile('imagenes_artesanias')) {
            foreach ($request->file('imagenes_artesanias') as $key => $imageFile) {
                // Guardamos la nueva imagen en storage/app/public/images/artesanias/general
                $path = $imageFile->store('images/artesanias/general', 'public');

                // Si ya existe una imagen en este índice, la eliminamos
                if (isset($artesaniaImages[$key])) {
                    // Convertimos la URL pública a ruta relativa en storage
                    $oldPath = str_replace('/storage/', '', $artesaniaImages[$key]);

                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }

                    // Reemplazamos por la nueva ruta
                    $artesaniaImages[$key] = Storage::url($path);
                } else {
                    // Si no existe, añadimos la nueva
                    $artesaniaImages[] = Storage::url($path);
                }
            }
        }

            
            // 2. Actualizar la artesanía principal
            $artesania->update([
                'nombre' => $validatedData['nombre'],
                'slug' => Str::slug($validatedData['nombre']),
                'descripcion' => $validatedData['descripcion'] ?? null,
                'historia_piezas_general' => $validatedData['historia_piezas_general'] ?? null,
                'precio' => $validatedData['precio'],
                'imagen_artesanias' => $artesaniaImages,
                'categoria_id' => $validatedData['categoria_id'],
                'ubicacion_id' => $validatedData['ubicacion_id'] ?? null,
            ]);

            // 3. Sincronizar variantes
            $submittedVariantIds = collect($validatedData['variants'] ?? [])->pluck('id')->filter()->all();
            $existingVariantIds = $artesania->variants->pluck('id')->all();
            $variantsToDelete = array_diff($existingVariantIds, $submittedVariantIds);
            
            // Eliminar variantes que ya no están en el formulario
            if (!empty($variantsToDelete)) {
                foreach (ArtesaniaVariant::whereIn('id', $variantsToDelete)->get() as $variantToDelete) {
                    $this->deleteVariantImages($variantToDelete);
                    $variantToDelete->delete();
                }
            }

            if (isset($validatedData['variants'])) {
                foreach ($validatedData['variants'] as $variantData) {
                    if (isset($variantData['id'])) {
                        // Actualizar variante existente
                        $variant = ArtesaniaVariant::find($variantData['id']);
                        if ($variant) {
                            $variantImages = $variant->imagen_variant;

                            if (!is_array($variantImages)) {
                                $variantImages = [];
                            }
                            
                            // Manejar eliminación de imágenes de la variante
                            if ($request->has("delete_variant_images.{$variant->id}")) {
                                foreach ($request->input("delete_variant_images.{$variant->id}") as $urlToDelete) {
                                    $path = str_replace(url('/'), 'public', $urlToDelete);
                                    if (Storage::exists($path)) {
                                        Storage::delete($path);
                                    }
                                    $variantImages = array_values(array_filter($variantImages, function ($url) use ($urlToDelete) {
                                        return $url !== $urlToDelete;
                                    }));
                                }
                            }

                            // Subir y reemplazar nuevas imágenes de la variante
                            if ($request->hasFile("variants.{$variantData['id']}.imagenes_variant")) {
                                foreach ($request->file("variants.{$variantData['id']}.imagenes_variant") as $key => $imageFile) {
                                    $path = $imageFile->store('images/artesanias/variants', 'public');
                                    // Si ya existe una imagen en este índice, la eliminamos primero
                                    if (isset($variantImages[$key])) {
                                        $oldPath = str_replace(url('/'), 'public', $variantImages[$key]);
                                        if (Storage::exists($oldPath)) {
                                            Storage::delete($oldPath);
                                        }
                                        $variantImages[$key] = Storage::url($path);
                                    } else {
                                        // Si no existe, la añadimos
                                        $variantImages[] = Storage::url($path);
                                    }
                                }
                            }
                            
                            $variant->update([
                                'sku' => $variantData['sku'] ?? null,
                                'variant_name' => $variantData['variant_name'],
                                'descripcion_variant' => $variantData['descripcion_variant'] ?? null,
                                'precio' => $variantData['precio'],
                                'stock' => $variantData['stock'],
                                'tipo_embalaje_id' => $variantData['tipo_embalaje_id'] ?? null,
                                'is_active' => $variantData['is_active'] ?? true,
                                'imagen_variant' => $variantImages,
                            ]);

                            // Sincronizar atributos
                            $this->syncAttributes($variant, $variantData['attributes'] ?? []);
                        }
                    } else {
                        // Crear nueva variante
                        $variant = new ArtesaniaVariant([
                            'artesania_id' => $artesania->id,
                            'sku' => $this->generateSku($artesania->id, $variantData),
                            'variant_name' => $variantData['variant_name'],
                            'descripcion_variant' => $variantData['descripcion_variant'] ?? null,
                            'precio' => $variantData['precio'],
                            'stock' => $variantData['stock'],
                            'tipo_embalaje_id' => $variantData['tipo_embalaje_id'] ?? null,
                            'is_active' => $variantData['is_active'] ?? true,
                        ]);
                        $imagenesVariantUrls = [];
                        if ($request->hasFile("variants.{$variantData['sku']}.imagenes_variant")) {
                            foreach ($request->file("variants.{$variantData['sku']}.imagenes_variant") as $imageFile) {
                                $path = $imageFile->store('images/artesanias/variants', 'public');
                                $imagenesVariantUrls[] = Storage::url($path);
                            }
                        }
                        $variant->imagen_variant = $imagenesVariantUrls;
                        $variant->save();

                        // Crear atributos para la nueva variante
                        if (isset($variantData['attributes'])) {
                            foreach ($variantData['attributes'] as $attributeData) {
                                $this->createOrUpdateAttribute($variant, $attributeData);
                            }
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.artesanias.index')->with('success', 'Artesanía y variantes actualizadas correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating artesanía: ' . $e->getMessage(), [
                'request' => $request->all(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->back()->withInput()->withErrors(['error' => 'Hubo un error al actualizar la artesanía y sus variantes. Por favor, verifica el log para más detalles.']);
        }
    }

    /**
     * Sincroniza los atributos de una variante.
     * @param ArtesaniaVariant $variant
     * @param array $submittedAttributes
     */
    protected function syncAttributes(ArtesaniaVariant $variant, array $submittedAttributes)
    {
        $submittedAttributeIds = collect($submittedAttributes)->pluck('id')->filter()->all();
        $existingAttributeIds = $variant->atributos->pluck('id')->all();
        $attributesToDelete = array_diff($existingAttributeIds, $submittedAttributeIds);

        if (!empty($attributesToDelete)) {
            AtributoArtesaniaVariant::whereIn('id', $attributesToDelete)->delete();
        }

        foreach ($submittedAttributes as $attributeData) {
            $this->createOrUpdateAttribute($variant, $attributeData);
        }
    }

    /**
     * Creates or updates an attribute for a variant.
     * @param ArtesaniaVariant $variant
     * @param array $attributeData
     */
    protected function createOrUpdateAttribute(ArtesaniaVariant $variant, array $attributeData)
    {
        if (isset($attributeData['id'])) {
            // Update existing attribute
            $attribute = AtributoArtesaniaVariant::find($attributeData['id']);
            if ($attribute) {
                $attribute->update(['valor' => $attributeData['value']]);
            }
        } else {
            // Create new attribute
            $newAttribute = Atributo::firstOrCreate(['nombre' => $attributeData['name']]);
            AtributoArtesaniaVariant::create([
                'artesania_variant_id' => $variant->id,
                'atributo_id' => $newAttribute->id,
                'valor' => $attributeData['value'],
            ]);
        }
    }

    /**
     * Deletes variant images from storage.
     * @param ArtesaniaVariant $variant
     */
    protected function deleteVariantImages(ArtesaniaVariant $variant)
    {
        if (is_array($variant->imagen_variant)) {
            foreach ($variant->imagen_variant as $imageUrl) {
                $path = str_replace(url('/'), 'public', $imageUrl);
                if (Storage::exists($path)) {
                    Storage::delete($path);
                }
            }
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
