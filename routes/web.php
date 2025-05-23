<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtesaniaController;
use App\Http\Controllers\ArtesanoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UbicacionController;

use App\Http\Controllers\Auth\AuthenticatedSessionController; // Necesario si vas a modificar el redireccionamiento post-login de Breeze
use App\Http\Controllers\Admin\ArtesaniaController as AdminArtesaniaController; // <-- ¡Importa el nuevo controlador!
use App\Http\Controllers\Admin\UbicacionController as AdminUbicacionController;
use App\Http\Controllers\Admin\CategoriaController as AdminCategoriaController; // <-- ¡Añade esta línea!
// ... (otras importaciones)
// ... (otras importaciones)
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('admin')->name('admin.')->group(function(){
     Route::get('artesanias/import', [AdminArtesaniaController::class, 'importForm'])->name('artesanias.import.form');
    Route::post('artesanias/import', [AdminArtesaniaController::class, 'import'])->name('artesanias.import');

    Route::resource('artesanias', AdminArtesaniaController::class);
    Route::resource('ubicacion', AdminUbicacionController::class);
    Route::resource('categorias', AdminCategoriaController::class);
   

});


// Rutas para el Catálogo de Artesanías
Route::get('/artesanias', [ArtesaniaController::class, 'index'])->name('artesanias.index');
Route::get('/artesanias/{artesania}', [ArtesaniaController::class, 'show'])->name('artesanias.show');


// Rutas para el Listado y Detalles de Categorías
Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
Route::get('/categorias/{categoria}', [CategoriaController::class, 'show'])->name('categorias.show');

// Rutas para el Listado y Detalles de Ubicaciones
Route::get('/ubicaciones', [UbicacionController::class, 'index'])->name('ubicaciones.index');
Route::get('/ubicaciones/{ubicacion}', [UbicacionController::class, 'show'])->name('ubicaciones.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
