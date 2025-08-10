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
 * @property string|null $sku
 * @property string|null $variant_name
 * @property string|null $description_variant
 * @property string|null $size
 * @property string|null $color
 * @property string|null $material_variant
 * @property float $precio
 * @property int $stock
 * @property string|null $imagen_variant
 * @property int|null $tipo_embalaje_id
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
        'size',
        'color',
        'material_variant',
        'precio',
        'stock',
        'imagen_variant',
        'tipo_embalaje_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'precio' => 'float',
        'stock' => 'integer',
        'imagen_variant' => 'array',
    ];

    /**
     * Get the artesania that owns the variant.
     */
    public function artesania(): BelongsTo
    {
        return $this->belongsTo(Artesania::class);
    }

    /**
     * Get the packaging type for the variant.
     */
    public function tipoEmbalaje(): BelongsTo
    {
        return $this->belongsTo(TipoEmbalaje::class);
    }

    /**
     * Get the custom attributes for the variant.
     */
    public function atributos(): HasMany
    {
        return $this->hasMany(AtributoArtesaniaVariant::class);
    }

    /**
     * Accessor to get the first image from the imagen_variant array.
     */
    public function getImagenPrincipalAttribute()
{
    return $this->imagen_variant[0] ?? null;
}


}