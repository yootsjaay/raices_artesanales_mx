<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtesaniaController;
use App\Http\Controllers\ArtesanoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UbicacionController; // Si lo creaste

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Ruta de inicio
Route::get('/', function () {
    return view('welcome'); // O puedes cambiarlo a una vista personalizada o a la lista de artesanías
});

// Rutas para Artesanías
Route::get('/artesanias', [ArtesaniaController::class, 'index'])->name('artesanias.index');
Route::get('/artesanias/{artesania}', [ArtesaniaController::class, 'show'])->name('artesanias.show');

// Rutas para Artesanos
Route::get('/artesanos', [ArtesanoController::class, 'index'])->name('artesanos.index');
Route::get('/artesanos/{artesano}', [ArtesanoController::class, 'show'])->name('artesanos.show');

// Rutas para Categorías
Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
Route::get('/categorias/{categoria}', [CategoriaController::class, 'show'])->name('categorias.show');

// Rutas para Ubicaciones (si lo necesitas)
Route::get('/ubicaciones', [UbicacionController::class, 'index'])->name('ubicaciones.index');
Route::get('/ubicaciones/{ubicacion}', [UbicacionController::class, 'show'])->name('ubicaciones.show');