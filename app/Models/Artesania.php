<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * Class Artesania
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 * @property string|null $imagen_artesanias
 * @property string|null $historia_piezas_general
 * @property int $categoria_id
 * @property int|null $ubicacion_id
 * @property float $precio
 * @property float $weight
 * @property float $length
 * @property float $width
 * @property float $height
 * @property string $slug
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Categoria $categoria
 * @property Ubicacion|null $ubicacion
 * @property Collection|ArtesaniaVariant[] $variants
 */
class Artesania extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'artesanias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen_artesanias',
        'historia_piezas_general',
        'categoria_id',
        'slug',
        'ubicacion_id',
        'precio',
        'weight',
        'length',
        'width',
        'height',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        
        'imagen_artesanias' => 'array',
        'precio' => 'float',
        'weight' => 'float',
        'length' => 'float',
        'width' => 'float',
        'height' => 'float',
    ];

    /**
     * Get the category that owns the Artesania.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Get the location that owns the Artesania.
     */
    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class);
    }

    /**
     * Get the variants for the Artesania.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ArtesaniaVariant::class, 'artesania_id');
    }
    public function getRouteKeyName()
{
    return 'slug';
}

public function comments()
{
    return $this->hasMany(Comment::class)->where('content', 'rating');
}


    
    /**
     * Automatically generate a slug from the name.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($artesania) {
            $artesania->slug = Str::slug($artesania->nombre);
        });

        static::updating(function ($artesania) {
            if ($artesania->isDirty('nombre')) {
                $artesania->slug = Str::slug($artesania->nombre);
            }
        });
    }
}

