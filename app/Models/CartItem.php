<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'artesania_id',
        'quantity',
        'price',
    ];

    // Un ítem del carrito pertenece a un carrito
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Un ítem del carrito está relacionado con una artesanía
    public function artesania()
    {
        return $this->belongsTo(Artesania::class);
    }

    // Atributo accesor para calcular el subtotal de este ítem
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }
}