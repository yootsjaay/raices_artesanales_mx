<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArtesaniaController;
use App\Http\Controllers\ArtesanoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ArtesaniaController as AdminArtesaniaController;
use App\Http\Controllers\Admin\UbicacionController as AdminUbicacionController;
use App\Http\Controllers\Admin\CategoriaController as AdminCategoriaController;
use App\Http\Controllers\Admin\UserController as AdminUserController;



use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\PagoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// =================== ADMIN - SOLO VENDEDOR ===================
Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('artesanias', [AdminArtesaniaController::class, 'index'])->name('artesanias.index');
    Route::get('categorias', [AdminCategoriaController::class, 'index'])->name('categorias.index');
    Route::get('ubicacion', [AdminUbicacionController::class, 'index'])->name('ubicacion.index');

    Route::get('artesanias/import', [AdminArtesaniaController::class, 'importForm'])->name('artesanias.import.form');
    Route::post('artesanias/import', [AdminArtesaniaController::class, 'import'])->name('artesanias.import');

    Route::resource('comments', AdminCommentController::class)->except(['create', 'edit', 'store']);
    Route::resource('artesanias', AdminArtesaniaController::class);
    Route::resource('ubicacion', AdminUbicacionController::class);
    Route::resource('categorias', AdminCategoriaController::class);
    Route::resource('usuarios',AdminUserController::class)->only(['index', 'edit', 'update']);
});



// =================== COMPRADOR - CARRITO, CHECKOUT, COMENTARIOS ===================
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Carrito
    Route::get('/carrito', [CarritoController::class, 'mostrar'])->name('carrito.mostrar');
    Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::put('/carrito/actualizar', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::delete('/carrito/remover', [CarritoController::class, 'remover'])->name('carrito.remover');
    Route::post('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    // Comentarios
    Route::post('artesanias/{artesania}/comments', [CommentController::class, 'store'])->name('artesanias.comments.store');
});


// =================== PAGO / MERCADO PAGO ===================
/*Route::get('/pagar-artesania/{id}', [PagoController::class, 'pagarArtesania'])->name('pagar.artesania');
Route::post('/mercadopago/webhook', [CheckoutController::class, 'handleMercadoPagoWebhook'])->name('mercadopago.webhook');
*/

// =================== ENVIA.COM (cotizador) ===================
Route::get('/cotizar-envio', [ShippingController::class, 'showQuoteForm'])->name('envia.form');
Route::post('/cotizar-envio', [ShippingController::class, 'getQuote'])->name('envia.quote');


// =================== CATÁLOGO ARTESANÍAS (PÚBLICO) ===================
Route::get('/artesanias', [ArtesaniaController::class, 'index'])->name('artesanias.index');
Route::get('/artesanias/{artesania}', [ArtesaniaController::class, 'show'])->name('artesanias.show');


// =================== CATEGORÍAS Y UBICACIONES (PÚBLICO) ===================
Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
Route::get('/categorias/{categoria}', [CategoriaController::class, 'show'])->name('categorias.show');

Route::get('/ubicaciones', [UbicacionController::class, 'index'])->name('ubicaciones.index');
Route::get('/ubicaciones/{ubicacion}', [UbicacionController::class, 'show'])->name('ubicaciones.show');


// =================== PERFIL DE USUARIO ===================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
