<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingService extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_carrier_id',
        'nombre',
        'descripcion',
        'costo_fijo',
    ];

    public function carrier()
    {
        return $this->belongsTo(ShippingCarrier::class, 'shipping_carrier_id');
    }

     public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
