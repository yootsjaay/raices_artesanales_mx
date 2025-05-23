<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtesaniaController;
use App\Http\Controllers\ArtesanoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController; // Necesario si vas a modificar el redireccionamiento post-login de Breeze

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
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
