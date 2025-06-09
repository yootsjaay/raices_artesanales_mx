<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // Si ya lo tienes, déjalo
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Artesania
 *
 * @property int $id
 * @property string $nombre
 * @property string $descripcion
 * @property float $precio
 * @property int $stock
 * @property string|null $imagen_principal
 * @property string|null $imagen_adicionales
 * @property string|null $materiales
 * @property string|null $dimensiones
 * @property string|null $historia_piezas
 * @property int $artesano_id
 * @property int $categoria_id
 * @property int|null $ubicacion_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Artesano $artesano
 * @property Categoria $categoria
 * @property Ubicacion|null $ubicacion
 *
 * @package App\Models
 */
class Artesania extends Model
{
    use HasFactory;
    protected $table = 'artesanias';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen_principal',
        'imagen_adicionales',
        'materiales',
        'dimensiones',
        'historia_piezas',
        'artesano_id',    // Asegúrate de que coincida con la migración
        'categoria_id',   // Asegúrate de que coincida con la migración
        'ubicacion_id',    // Asegúrate de que coincida con la migración
        'weight',    // Añadir estos campos
        'length',
        'width',
        'height',
    ];

    
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id'); // Asegúrate que la FK sea 'categoria_id'
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id'); // Asegúrate que la FK sea 'ubicacion_id'
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}