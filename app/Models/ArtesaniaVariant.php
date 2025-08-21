<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class ArtesaniaVariant
 *
 * @property int $id
 * @property int $artesania_id
 * @property string $sku
 * @property string|null $variant_name
 * @property string|null $description_variant
 * @property float $precio
 * @property int $stock
 * @property array|null $imagen_variant
 * @property int|null $tipo_embalaje_id
 * @property float $peso_item_kg
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property Artesania $artesania
 * @property TipoEmbalaje|null $tipoEmbalaje
 * @property \Illuminate\Database\Eloquent\Collection|AtributoArtesaniaVariant[] $atributos
 */
class ArtesaniaVariant extends Model
{
    use HasFactory;

    protected $table = 'artesania_variants';

    protected $fillable = [
        'artesania_id',
        'sku',
        'variant_name',
        'description_variant',
        'precio',
        'stock',
        'imagen_variant',
        'tipo_embalaje_id',
        'peso_item_kg',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'precio' => 'float',
        'stock' => 'integer',
        'imagen_variant' => 'array',
        'peso_item_kg' => 'float',
    ];

    /** Relaciones */
    public function artesania(): BelongsTo
    {
        return $this->belongsTo(Artesania::class);
    }

    public function tipoEmbalaje(): BelongsTo
    {
        return $this->belongsTo(TipoEmbalaje::class);
    }

    public function atributos(): HasMany
    {
        return $this->hasMany(AtributoArtesaniaVariant::class);
    }

    /** Accessor: primera imagen */
    public function getImagenPrincipalAttribute()
    {
        return $this->imagen_variant[0] ?? null;
    }
    public function nombreAtributo(): HasManyThrough{
        return $this->hasManyThrough(
            Atributo::class,
            AtributoArtesaniaVariant::class,
            'artesania_variant_id', // Foreign key on the AtributoArtesaniaVariant table
            'id',                   // Foreign key on the Atributo table
            'id',                   // Local key on the ArtesaniaVariant table
            'atributo_id'           // Local key on the AtributoArtesaniaVariant table
        );
    

    }
}
