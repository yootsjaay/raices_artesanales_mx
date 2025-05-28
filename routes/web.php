<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtesaniaController;
use App\Http\Controllers\ArtesanoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\CommentController; // Asegúrate de importar el controlador de comentarios

use App\Http\Controllers\CarritoController; // Asegúrate de importar el controlador de comentarios
use App\Http\Middleware\FakeAuth;

use App\Http\Controllers\Auth\AuthenticatedSessionController; // Necesario si vas a modificar el redireccionamiento post-login de Breeze
use App\Http\Controllers\Admin\ArtesaniaController as AdminArtesaniaController; // <-- ¡Importa el nuevo controlador!
use App\Http\Controllers\Admin\UbicacionController as AdminUbicacionController;
use App\Http\Controllers\Admin\CategoriaController as AdminCategoriaController; // <-- ¡Añade esta línea!
use App\Http\Controllers\Admin\CommentController as AdminCommentController; // Importar el controlador de admin

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
    
    Route::resource('comments', AdminCommentController::class)->except(['create', 'edit', 'store']);

    Route::resource('artesanias', AdminArtesaniaController::class);
    Route::resource('ubicacion', AdminUbicacionController::class);
    Route::resource('categorias', AdminCategoriaController::class);
   

});
Route::get('artesanias/{artesania}', [ArtesaniaController::class, 'show'])->name('artesanias.show');
//MERCADO PAGO
Route::get('/pagar-artesania/{id}', [PagoController::class, 'pagarArtesania'])->name('pagar.artesania');

Route::middleware([FakeAuth::class])->group(function () {
    Route::get('/carrito', [CarritoController::class, 'index']);
    Route::get('/carrito', [CarritoController::class, 'mostrar'])->name('carrito.mostrar');
Route::post('/carrito/remover', [CarritoController::class, 'remover'])->name('carrito.remover');
Route::post('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');

    // otras rutas protegidas por FakeAuth
});

// Ruta para enviar comentarios (DEBE ESTAR FUERA DEL GRUPO 'admin')
Route::post('artesanias/{artesania}/comments', [CommentController::class, 'store'])
    ->middleware(['auth'])->name('artesanias.comments.store');


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
