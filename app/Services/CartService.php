<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem; // Asegúrate de importar CartItem
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartService
{
    protected function authenticated(Request $request, $user)
{
    $cartService = new CartService(); // Puedes inyectarlo si prefieres, o instanciarlo así.
    $cartService->syncGuestCart();

    return redirect()->intended($this->redirectPath());
}
    public function getOrCreateCart()
    {
        if (Auth::check()) {
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        } else {
            $guestToken = session()->get('guest_cart_token');

            if ($guestToken) {
                $cart = Cart::where('guest_token', $guestToken)->first();
                if ($cart) {
                    return $cart;
                }
            }

            $newGuestToken = Str::uuid()->toString();
            session()->put('guest_cart_token', $newGuestToken);
            return Cart::create(['guest_token' => $newGuestToken]);
        }
    }

    public function syncGuestCart()
    {
        if (Auth::check()) {
            $guestToken = session()->get('guest_cart_token');
            if ($guestToken) {
                $guestCart = Cart::where('guest_token', $guestToken)->first();
                $userCart = Cart::firstOrCreate(['user_id' => Auth::id()]);

                if ($guestCart && $userCart) {
                    foreach ($guestCart->items as $guestItem) {
                        $existingItem = $userCart->items()
                            ->where('artesania_id', $guestItem->artesania_id)
                            ->first();

                        if ($existingItem) {
                            $existingItem->quantity += $guestItem->quantity;
                            $existingItem->save();
                        } else {
                            $guestItem->cart_id = $userCart->id;
                            $guestItem->save();
                        }
                    }
                    $guestCart->delete();
                }
                session()->forget('guest_cart_token');
            }
        }
    }
}