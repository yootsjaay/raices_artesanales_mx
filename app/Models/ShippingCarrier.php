<?php

// app/Models/ShippingCarrier.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCarrier extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion'];

    public function servicios()
    {
        return $this->hasMany(ShippingService::class);
    }
}
