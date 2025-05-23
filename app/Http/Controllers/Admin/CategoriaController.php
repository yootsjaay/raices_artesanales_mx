<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria; // <-- ¡Asegúrate de importar Categoria!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // ¡Asegúrate de que esta línea esté presente!
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::all(); // Obtiene todas las categorías
        return view('admin.categorias.index', compact('categorias'));
    }

    // ... (otros métodos create, store, show, edit, update, destroy vacíos por ahora)


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validación de los datos del formulario
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias,nombre',
            'descripcion' => 'nullable|string', // <-- NUEVA REGLA para descripción
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:51200', // <-- NUEVA REGLA para imagen (50MB)
        ]);

        // 2. Manejo de la imagen de categoría
        $imagePath = null;
        if ($request->hasFile('imagen')) {
            // Guarda la imagen en 'public/storage/images/categorias'
            $imagePath = $request->file('imagen')->store('images/categorias', 'public');
        }
        $validatedData['imagen'] = $imagePath; // Asigna la ruta de la imagen validada o null

        // 3. Crear la nueva Categoría en la base de datos
        Categoria::create($validatedData);

        // 4. Redireccionar al listado de categorías con un mensaje de éxito
        return redirect()->route('admin.categorias.index')
                         ->with('success', 'Categoría creada exitosamente.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
     public function edit(Categoria $categoria)
    {
        // Pasa la instancia de Categoria a la vista
        return view('admin.categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $validatedData = $request->validate([
            // 'nombre' debe ser único, pero ignorar el nombre de la propia categoría que se está editando
            'nombre' => ['required', 'string', 'max:255', Rule::unique('categorias')->ignore($categoria->id)],
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:51200', // 'nullable' permite no subir una nueva imagen
        ]);

        // Manejo de la imagen:
        // Si se ha subido una nueva imagen
        if ($request->hasFile('imagen')) {
            // 1. Eliminar la imagen antigua si existe
            if ($categoria->imagen && Storage::disk('public')->exists($categoria->imagen)) {
                Storage::disk('public')->delete($categoria->imagen);
            }
            // 2. Guardar la nueva imagen
            $validatedData['imagen'] = $request->file('imagen')->store('images/categorias', 'public');
        } else {
            // Si no se subió una nueva imagen, mantener la existente (no la actualizamos en $validatedData)
            unset($validatedData['imagen']); // Asegurarse de que no se intente sobrescribir con null si no hay nuevo archivo
        }

        // Actualiza la categoría con los datos validados
        $categoria->update($validatedData);

        // Redirecciona al listado con un mensaje de éxito
        return redirect()->route('admin.categorias.index')
                         ->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        // Eliminar la imagen asociada si existe
        if ($categoria->imagen && Storage::disk('public')->exists($categoria->imagen)) {
            Storage::disk('public')->delete($categoria->imagen);
        }

        // Eliminar la categoría de la base de datos
        $categoria->delete();

        // Redireccionar al listado con un mensaje de éxito
        return redirect()->route('admin.categorias.index')
                         ->with('success', 'Categoría eliminada exitosamente.');
    }
}
