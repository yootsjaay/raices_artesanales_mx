<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'ciudad',
        'estado',
        'codigo_postal',
        'telefono',
        'email',
    ];

    public function artesanias()
    {
        return $this->hasMany(Artesania::class, 'ubicacion_id');
    }
}
