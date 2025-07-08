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
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CompradorController;

use App\Http\Controllers\Admin\ArtesaniaController as AdminArtesaniaController;
use App\Http\Controllers\Admin\UbicacionController as AdminUbicacionController;
use App\Http\Controllers\Admin\CategoriaController as AdminCategoriaController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DashboardController;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\PagoController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]); // Esto habilita las rutas para la verificación de correo


// =================== ADMIN - SOLO VENDEDOR ===================
Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
Route::get('artesanias', [AdminArtesaniaController::class, 'index'])->name('artesanias.index');
Route::resource('artesanias', AdminArtesaniaController::class);
    Route::get('categorias', [AdminCategoriaController::class, 'index'])->name('categorias.index');
    Route::get('ubicacion', [AdminUbicacionController::class, 'index'])->name('ubicacion.index');

    Route::get('artesanias/import', [AdminArtesaniaController::class, 'importForm'])->name('artesanias.import.form');
    Route::post('artesanias/import', [AdminArtesaniaController::class, 'import'])->name('artesanias.import');

    Route::resource('comments', AdminCommentController::class)->except(['create', 'edit', 'store']);
    Route::resource('artesanias', AdminArtesaniaController::class);
    Route::resource('ubicacion', AdminUbicacionController::class);
    Route::resource('categorias', AdminCategoriaController::class);
    Route::resource('usuarios',AdminUserController::class)->only(['index', 'edit', 'update']);
    
    Route::get('/checkout/shipping', [CheckoutController::class, 'showShippingForm'])->name('checkout.shipping');
    Route::post('/checkout/shipping', [CheckoutController::class, 'processShipping'])->name('checkout.process_shipping');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

});



// =================== COMPRADOR - CARRITO, CHECKOUT, COMENTARIOS ===================
Route::middleware(['auth', 'role:comprador'])->group(function () {
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
    //slug
    

    // Comentarios
    Route::post('artesanias/{artesania}/comments', [CommentController::class, 'store'])->name('artesanias.comments.store');
     
        // Paso 1: Dirección de Envío
    // Muestra el formulario para ingresar/seleccionar la dirección de envío
    Route::get('/checkout/shipping', [CheckoutController::class, 'showShippingForm'])->name('checkout.shipping');
    // Procesa el envío del formulario de dirección y guarda la dirección
    Route::post('/checkout/shipping', [CheckoutController::class, 'processShipping'])->name('checkout.process_shipping');

    // Paso 2: Selección del Método de Envío (Aquí es donde se cotiza con Envia.com)
    // Muestra las opciones de envío disponibles
    Route::get('/checkout/shipping-method', [CheckoutController::class, 'showShippingMethodForm'])->name('checkout.shipping_method');
    // Procesa la selección del método de envío por parte del cliente
    Route::post('/checkout/shipping-method', [CheckoutController::class, 'processShippingMethod'])->name('checkout.process_shipping_method');
        Route::post('/checkout/shipping', [CheckoutController::class, 'storeShippingAddress'])->name('checkout.storeShippingAddress');

    Route::post('/checkout/shipping', [CheckoutController::class, 'processShipping'])->name('checkout.process_shipping');
    // Paso 3: Resumen del Pedido y Pago
    // Muestra el resumen final del pedido antes del pago
    Route::get('/checkout/payment', [CheckoutController::class, 'showPaymentForm'])->name('checkout.payment');
    // Procesa el pago y finaliza el pedido
    Route::post('/checkout/payment', [CheckoutController::class, 'processPayment'])->name('checkout.process_payment');
     Route::get('/checkout/review', function() {
        return view('checkout.review'); // Create this view next
    })->name('checkout.review');

    // Paso 4: Confirmación del Pedido
    // Muestra la página de confirmación después de un pedido exitoso
    Route::get('/order/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('order.confirmation');



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
Route::get('artesanias/{slug}', [ArtesaniaController::class, 'show'])->name('artesanias.show');


// =================== CATEGORÍAS Y UBICACIONES (PÚBLICO) ===================
Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
Route::get('categorias/{categoria}', [CategoriaController::class, 'show'])->name('categorias.show');


Route::get('/ubicaciones', [UbicacionController::class, 'index'])->name('ubicaciones.index');
Route::get('/ubicaciones/{ubicacion}', [UbicacionController::class, 'show'])->name('ubicaciones.show');


// =================== PERFIL DE USUARIO ===================
Route::middleware('auth', 'verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para direcciones dentro del perfil
    Route::prefix('profile/addresses')->name('profile.addresses.')->group(function () {
        Route::get('/', [AddressController::class, 'index'])->name('index');
        Route::get('/create', [AddressController::class, 'create'])->name('create');
        Route::post('/', [AddressController::class, 'store'])->name('store');
        Route::get('/{address}/edit', [AddressController::class, 'edit'])->name('edit');
        Route::put('/{address}', [AddressController::class, 'update'])->name('update');
        Route::delete('/{address}', [AddressController::class, 'destroy'])->name('destroy');
    });
   // =================== PANEL DE COMPRADOR ===================
Route::middleware(['auth', 'role:comprador'])->prefix('comprador')->name('comprador.')->group(function () {
    Route::get('/dashboard', [CompradorController::class, 'dashboard'])->name('dashboard');
    Route::get('/carrito', [CarritoController::class, 'mostrar'])->name('carrito.mostrar');

    // Direcciones del comprador (si quieres separarlas de /profile/addresses)
    Route::get('/direcciones', [AddressController::class, 'index'])->name('direcciones.index');
});

    });



require __DIR__.'/auth.php';
