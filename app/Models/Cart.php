<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guest_token',
    ];

    // Un carrito pertenece a un usuario (si user_id no es nulo)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un carrito tiene muchos ítems
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}