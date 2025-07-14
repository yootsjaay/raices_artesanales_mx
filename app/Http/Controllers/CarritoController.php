<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artesania;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use App\Models\ArtesaniaVariant;
class CarritoController extends Controller
{

    public function checkoutForm()
{
    $cart = app(CarritoController::class)->getOrCreateCart();

    if (!$cart || $cart->items->isEmpty()) {
        return redirect()->route('carrito.mostrar')->with('error', 'Tu carrito está vacío.');
    }

    return view('checkout.form', compact('cart'));
}


    // CUIDADO: Hacemos este método público para que CheckoutController pueda usarlo
    // Si prefieres un Servicio, esa es la opción más limpia a largo plazo.
    public function getOrCreateCart()
    {
        // Si el usuario no está logueado, lo redirigimos
        if (!Auth::check()) {
            // Podrías lanzar una excepción o redirigir directamente,
            // pero para el contexto del carrito, es mejor manejarlo donde se llama
            // o usar middleware para proteger las rutas.
            return null; // Indicamos que no hay carrito si no está logueado
        }

        // Usuario logueado: buscar su carrito o crear uno
        return Cart::firstOrCreate(['user_id' => Auth::id()]);
    }

    // Ya no necesitamos syncGuestCart() si los invitados no tienen carrito persistente

  public function agregar(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Debes iniciar sesión para añadir productos al carrito.');
    }

    $request->validate([
        'artesania_id' => 'required|exists:artesanias,id',
        'cantidad' => 'required|integer|min:1',
        'variant_id' => 'nullable|exists:artesania_variants,id',
    ]);

    $artesania = Artesania::findOrFail($request->artesania_id);
    $quantity = $request->cantidad;
    $variantId = $request->variant_id;

    if ($variantId) {
        // 🧬 Con variante
        $variant = ArtesaniaVariant::where('id', $variantId)
            ->where('artesania_id', $artesania->id)
            ->firstOrFail();

        if ($variant->stock < $quantity) {
            return back()->with('error', 'No hay suficiente stock para esta variante.');
        }

        $cart = $this->getOrCreateCart();

        $cartItem = $cart->cart_items()
            ->where('artesania_id', $artesania->id)
            ->where('artesania_variant_id', $variantId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($variant->stock < $newQuantity) {
                return back()->with('error', 'No puedes añadir más, solo hay ' . $variant->stock . ' unidades disponibles.');
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            $cart->cart_items()->create([
                'artesania_id' => $artesania->id,
                'artesania_variant_id' => $variant->id,
                'quantity' => $quantity,
                'price' => $variant->precio ?? $artesania->precio,
            ])->save();
        
        }

    } else {
        // 🪴 Sin variante
        if ($artesania->stock < $quantity) {
            return back()->with('error', 'Solo hay ' . $artesania->stock . ' unidades disponibles.');
        }

        $cart = $this->getOrCreateCart();

        $cartItem = $cart->cart_items()
            ->where('artesania_id', $artesania->id)
            ->whereNull('artesania_variant_id')
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($artesania->stock < $newQuantity) {
                return back()->with('error', 'No puedes añadir más, solo hay ' . $artesania->stock . ' unidades disponibles.');
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
        $cart->cart_items()->create([
            'artesania_id' => $artesania->id,
            'artesania_variant_id' => $variant->id,
            'quantity' => $quantity,
            'price' => $variant->precio ?? $artesania->precio,
        ]);

        }
    }

    return redirect()->route('carrito.mostrar')->with('success', 'Producto añadido correctamente.');
}


    public function mostrar()
    {
        // **Nueva validación: Requerir login para ver el carrito**
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver tu carrito.');
        }

        $cart = $this->getOrCreateCart();
        $cartItems = $cart->cart_items()->with('artesania')->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return view('carrito.index', compact('cartItems', 'total'));
    }

    public function actualizar(Request $request)
    {
        // **Nueva validación: Requerir login para actualizar**
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para actualizar tu carrito.');
        }

        $request->validate([
            'id' => 'required|exists:cart_items,id',
            'cantidad' => 'required|integer|min:0',
        ]);

        $cart = $this->getOrCreateCart();
        $cartItem = $cart->cart_items()->where('id', $request->id)->first();

        if (!$cartItem) {
            return redirect()->back()->with('error', 'Ítem del carrito no encontrado o no pertenece a tu carrito.');
        }

        $artesania = $cartItem->artesania;

        if ($request->cantidad == 0) {
            $cartItem->delete();
            return redirect()->back()->with('success', 'Producto eliminado del carrito.');
        }

        // Validar stock al actualizar
        if ($artesania->stock < $request->cantidad) {
            return redirect()->back()->with('error', 'Lo sentimos, solo hay ' . $artesania->stock . ' unidades disponibles de esta artesanía.');
        }

        $cartItem->quantity = $request->cantidad;
        $cartItem->save();

        return redirect()->back()->with('success', 'Cantidad del producto actualizada.');
    }

    public function remover(Request $request)
    {
        // **Nueva validación: Requerir login para remover**
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para remover productos de tu carrito.');
        }

        $request->validate([
            'id' => 'required|exists:cart_items,id',
        ]);

        $cart = $this->getOrCreateCart();
        $cartItem = $cart->cart_items()->where('id', $request->id)->first();

        if ($cartItem) {
            $cartItem->delete();
            return redirect()->route('carrito.mostrar')->with('success', 'Producto eliminado del carrito.');
        }

        return redirect()->route('carrito.mostrar')->with('error', 'Producto no encontrado en el carrito.');
    }

    public function vaciar()
    {
        // **Nueva validación: Requerir login para vaciar**
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para vaciar tu carrito.');
        }

        $cart = $this->getOrCreateCart();
        $cart->cart_items()->delete();

        return redirect()->route('carrito.mostrar')->with('success', 'Carrito vaciado.');
    }

    
    private function calculateTotalPackage($items)
{
    $length = $width = $height = $weight = 0;

    foreach ($items as $item) {
        $art = $item->artesania;
        $length += $art->length;
        $width = max($width, $art->width);
        $height = max($height, $art->height);
        $weight += $art->weight * $item->quantity;
    }

    return [
        'length' => $length,
        'width' => $width,
        'height' => $height,
        'weight' => $weight,
    ];
}

private function calculateCartValue($cart)
{
    return $cart->items->sum(fn ($item) => $item->subtotal);
}

}