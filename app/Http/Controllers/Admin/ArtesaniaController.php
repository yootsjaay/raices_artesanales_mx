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

        // 🔥 Campos de dimensiones obligatorios
        'weight' => 'required|numeric|min:0.01|max:9999.99',
        'length' => 'required|numeric|min:0.1|max:9999.99',
        'width'  => 'required|numeric|min:0.1|max:9999.99',
        'height' => 'required|numeric|min:0.1|max:9999.99',

        // Variantes
        'variants' => 'required|array|min:1',
        'variants.*.variant_name' => 'nullable|string|max:255',
        'variants.*.color' => 'nullable|string|max:255',
        'variants.*.size' => 'nullable|string|max:255',
        'variants.*.material_variant' => 'nullable|string|max:255',
        'variants.*.price_adjustment' => 'required|numeric|min:0.01',
        'variants.*.stock' => 'required|integer|min:0',
        'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        'variants.*.additional_images_urls.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        'variants.*.is_main' => 'nullable|boolean',
        'variants.*.is_active' => 'nullable|boolean',
    ]);

    // Guardar imagen principal
    if ($request->hasFile('imagen_principal')) {
        $validatedData['imagen_principal'] = $request->file('imagen_principal')->store('images/artesanias', 'public');
    }

    // Guardar imágenes adicionales
    $additionalImagesPaths = [];
    if ($request->hasFile('imagen_adicionales')) {
        foreach ($request->file('imagen_adicionales') as $file) {
            $additionalImagesPaths[] = $file->store('images/artesanias/additional', 'public');
        }
    }
    $validatedData['imagen_adicionales'] = $additionalImagesPaths;

    // Crear la artesanía con dimensiones y peso
    $artesania = Artesania::create([
        'nombre'             => $validatedData['nombre'],
        'descripcion'        => $validatedData['descripcion'] ?? null,
        'precio'             => $validatedData['precio'],
        'stock'              => $validatedData['stock'],
        'materiales'         => $validatedData['materiales'] ?? null,
        'historia_piezas'    => $validatedData['historia_piezas'] ?? null,
        'categoria_id'       => $validatedData['categoria_id'],
        'ubicacion_id'       => $validatedData['ubicacion_id'] ?? null,
        'imagen_principal'   => $validatedData['imagen_principal'] ?? null,
        'imagen_adicionales' => $validatedData['imagen_adicionales'],
        'weight'             => $validatedData['weight'],
        'length'             => $validatedData['length'],
        'width'              => $validatedData['width'],
        'height'             => $validatedData['height'],
    ]);

    // Crear las variantes
    foreach ($request->variants as $index => $variant) {
        $variantImagePath = null;
        if ($request->hasFile("variants.$index.image")) {
            $variantImagePath = $request->file("variants.$index.image")->store('images/artesanias/variants', 'public');
        }

        $variantAdditionalImages = [];
        if ($request->hasFile("variants.$index.additional_images_urls")) {
            foreach ($request->file("variants.$index.additional_images_urls") as $file) {
                $variantAdditionalImages[] = $file->store('images/artesanias/variants/additional', 'public');
            }
        }

        $artesania->artesania_variants()->create([
            'sku'                  => $variant['sku'] ?? $this->generateSku($artesania->id, $variant),
            'variant_name'         => $variant['variant_name'] ?? null,
            'size'                 => $variant['size'] ?? null,
            'color'                => $variant['color'] ?? null,
            'material_variant'     => $variant['material_variant'] ?? null,
            'price_adjustment'     => $variant['price_adjustment'],
            'stock'                => $variant['stock'],
            'image'                => $variantImagePath,
            'additional_images_urls' => $variantAdditionalImages,
            'is_main'              => isset($variant['is_main']) ? (bool)$variant['is_main'] : false,
            'is_active'            => isset($variant['is_active']) ? (bool)$variant['is_active'] : true,
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
    private function generateSku($artesaniaId, $variant)
{
    $color = strtoupper(substr($variant['color'], 0, 3));
    $size = isset($variant['size']) && $variant['size'] ? strtoupper(substr($variant['size'], 0, 3)) : 'STD';
    return "ART-{$artesaniaId}-{$color}-{$size}";
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

        // 🔥 Dimensiones y peso
        'weight' => 'required|numeric|min:0.01|max:9999.99',
        'length' => 'required|numeric|min:0.1|max:9999.99',
        'width'  => 'required|numeric|min:0.1|max:9999.99',
        'height' => 'required|numeric|min:0.1|max:9999.99',

        'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        'imagen_adicionales.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',

        // Variantes
        'variants' => 'required|array|min:1',
        'variants.*.variant_name' => 'nullable|string|max:255',
        'variants.*.color' => 'nullable|string|max:255',
        'variants.*.size' => 'nullable|string|max:255',
        'variants.*.material_variant' => 'nullable|string|max:255',
        'variants.*.price_adjustment' => 'required|numeric|min:0.01',
        'variants.*.stock' => 'required|integer|min:0',
        'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        'variants.*.additional_images_urls.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        'variants.*.is_main' => 'nullable|boolean',
        'variants.*.is_active' => 'nullable|boolean',
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
    $additionalImagesPaths = [];
    if ($request->hasFile('imagen_adicionales')) {
        if ($artesania->imagen_adicionales) {
            foreach ($artesania->imagen_adicionales as $img) {
                if (Storage::disk('public')->exists($img)) {
                    Storage::disk('public')->delete($img);
                }
            }
        }
        foreach ($request->file('imagen_adicionales') as $file) {
            $additionalImagesPaths[] = $file->store('images/artesanias/additional', 'public');
        }
    } else {
        $additionalImagesPaths = $artesania->imagen_adicionales ?? [];
    }
    $validatedData['imagen_adicionales'] = $additionalImagesPaths;

    // 🔧 Actualizar artesanía (incluye dimensiones y peso)
    $artesania->update([
        'nombre'             => $validatedData['nombre'],
        'descripcion'        => $validatedData['descripcion'] ?? null,
        'precio'             => $validatedData['precio'],
        'stock'              => $validatedData['stock'],
        'materiales'         => $validatedData['materiales'] ?? null,
        'historia_piezas'    => $validatedData['historia_piezas'] ?? null,
        'categoria_id'       => $validatedData['categoria_id'],
        'ubicacion_id'       => $validatedData['ubicacion_id'] ?? null,
        'imagen_principal'   => $validatedData['imagen_principal'],
        'imagen_adicionales' => $validatedData['imagen_adicionales'],
        'weight'             => $validatedData['weight'],
        'length'             => $validatedData['length'],
        'width'              => $validatedData['width'],
        'height'             => $validatedData['height'],
    ]);

    // ========== Actualizar o crear variantes ==========
    $idsSolicitados = [];

    foreach ($request->variants as $index => $variant) {
        $variantData = [
            'variant_name'         => $variant['variant_name'] ?? null,
            'size'                 => $variant['size'] ?? null,
            'color'                => $variant['color'] ?? null,
            'material_variant'     => $variant['material_variant'] ?? null,
            'price_adjustment'     => $variant['price_adjustment'],
            'stock'                => $variant['stock'],
            'is_main'              => isset($variant['is_main']) ? (bool)$variant['is_main'] : false,
            'is_active'            => isset($variant['is_active']) ? (bool)$variant['is_active'] : true,
        ];

        // Imagen variante
        $variantImagePath = null;
        if ($request->hasFile("variants.$index.image")) {
            if (isset($variant['id'])) {
                $varModel = $artesania->artesania_variants()->find($variant['id']);
                if ($varModel && $varModel->image && Storage::disk('public')->exists($varModel->image)) {
                    Storage::disk('public')->delete($varModel->image);
                }
            }
            $variantImagePath = $request->file("variants.$index.image")->store('images/artesanias/variants', 'public');
        } else {
            if (isset($variant['id'])) {
                $varModel = $artesania->artesania_variants()->find($variant['id']);
                $variantImagePath = $varModel ? $varModel->image : null;
            }
        }
        $variantData['image'] = $variantImagePath;

        // Imágenes adicionales
        $variantAdditionalImages = [];
        if ($request->hasFile("variants.$index.additional_images_urls")) {
            if (isset($variant['id'])) {
                $varModel = $artesania->artesania_variants()->find($variant['id']);
                if ($varModel && is_array($varModel->additional_images_urls)) {
                    foreach ($varModel->additional_images_urls as $img) {
                        if (Storage::disk('public')->exists($img)) {
                            Storage::disk('public')->delete($img);
                        }
                    }
                }
            }
            foreach ($request->file("variants.$index.additional_images_urls") as $file) {
                $variantAdditionalImages[] = $file->store('images/artesanias/variants/additional', 'public');
            }
        } else {
            if (isset($variant['id'])) {
                $varModel = $artesania->artesania_variants()->find($variant['id']);
                $variantAdditionalImages = $varModel ? $varModel->additional_images_urls : [];
            }
        }
        $variantData['additional_images_urls'] = $variantAdditionalImages;

        if (isset($variant['id'])) {
            $varModel = $artesania->artesania_variants()->find($variant['id']);
            if ($varModel) {
                $idsSolicitados[] = $variant['id'];
                $varModel->update($variantData);
            }
        } else {
            $variantData['sku'] = $this->generateSku($artesania->id, $variant);
            $newVariant = $artesania->artesania_variants()->create($variantData);
            $idsSolicitados[] = $newVariant->id;
        }
    }

    // Eliminar variantes no incluidas
    $artesania->artesania_variants()
        ->whereNotIn('id', $idsSolicitados)
        ->get()
        ->each(function ($variant) {
            if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                Storage::disk('public')->delete($variant->image);
            }
            if (is_array($variant->additional_images_urls)) {
                foreach ($variant->additional_images_urls as $img) {
                    if (Storage::disk('public')->exists($img)) {
                        Storage::disk('public')->delete($img);
                    }
                }
            }
            $variant->delete();
        });

    return redirect()->route('admin.artesanias.index')->with('success', 'Artesanía actualizada correctamente.');
}

public function destroy(Artesania $artesania)
{
    // 1. Eliminar la imagen principal asociada
    if ($artesania->imagen_principal && Storage::disk('public')->exists($artesania->imagen_principal)) {
        Storage::disk('public')->delete($artesania->imagen_principal);
    }

    // 2. Eliminar las imágenes adicionales asociadas
    if ($artesania->imagen_adicionales) {
        foreach ($artesania->imagen_adicionales as $imagePath) {
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }
    }

    // 3. Eliminar variantes y sus imágenes
    foreach ($artesania->artesania_variants as $variant) {
        // Imagen principal de la variante
        if ($variant->image && Storage::disk('public')->exists($variant->image)) {
            Storage::disk('public')->delete($variant->image);
        }
        // Imágenes adicionales de la variante
        if (is_array($variant->additional_images_urls)) {
            foreach ($variant->additional_images_urls as $img) {
                if (Storage::disk('public')->exists($img)) {
                    Storage::disk('public')->delete($img);
                }
            }
        }
        $variant->delete();
    }

    // 4. Eliminar la artesanía de la base de datos
    $artesania->delete();

    // 5. Redireccionar al listado con un mensaje de éxito
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
