<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Asegúrate de tener Rule importado para la validación unique

class UbicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      public function index()
    {
        $ubicaciones = Ubicacion::all(); // Obtiene todas las ubicaciones
        return view('admin.ubicacion.index', compact('ubicaciones'));
    
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ubicaciones= Ubicacion::all();
        return view('admin.ubicacion.create',compact('ubicaciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        // 1. Validación de los datos del formulario
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255|unique:ubicaciones,nombre',
            'tipo' => 'nullable|string|max:255', // <-- ¡NUEVA REGLA! Lo hacemos nullable por si no siempre se usa.
            'descripcion' => 'nullable|string', // <-- ¡NUEVA REGLA! Lo hacemos nullable.
        ]);

        // 2. Crear la nueva Ubicación en la base de datos
        Ubicacion::create($validatedData); // Ahora $validatedData incluirá 'tipo' y 'descripcion'

        // 3. Redireccionar al listado de ubicaciones con un mensaje de éxito
        return redirect()->route('admin.ubicacion.index')
                         ->with('success', 'Ubicación creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ubicacion $ubicacion)
    {
        // Para ubicaciones, este método rara vez se usa en el admin.
        // Podrías simplemente redirigir o mostrar un mensaje.
        // return view('admin.ubicaciones.show', compact('ubicacion')); // Si creas esta vista
        return redirect()->route('admin.ubicacion.index'); // O simplemente redirigir
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ubicacion $ubicacion)
    {
        return view('admin.ubicacion.edit', compact('ubicacion')); // CAMBIO AQUÍ
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ubicacion $ubicacion)
    {
        $validatedData = $request->validate([
            'nombre' => ['required', 'string', 'max:255', Rule::unique('ubicaciones')->ignore($ubicacion->id)],
            'tipo' => 'nullable|string|in:Municipio,Localidad,Región|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $ubicacion->update($validatedData);

        return redirect()->route('admin.ubicacion.index')
                         ->with('success', 'Ubicación actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destroy(Ubicacion $ubicacion)
    {
        $ubicacion->delete();

        return redirect()->route('admin.ubicacion.index')
                         ->with('success', 'Ubicación eliminada exitosamente.');
    }
}
