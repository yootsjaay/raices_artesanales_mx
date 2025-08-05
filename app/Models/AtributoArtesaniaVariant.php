<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AtributoArtesaniaVariant extends Model
{
    use HasFactory;

    protected $table = 'atributos_artesania_variant';

    protected $fillable = [
        'artesania_variant_id',
        'atributo_id',
        'valor',
    ];

    /**
     * Get the artesania variant that owns the attribute.
     */
    public function artesaniaVariant(): BelongsTo
    {
        return $this->belongsTo(ArtesaniaVariant::class, 'artesania_variant_id');
    }

    /**
     * Get the attribute associated with this variant.
     */
    public function atributo(): BelongsTo
    {
        return $this->belongsTo(Atributo::class, 'atributo_id');
    }

    public function getTotalShippingWeightAttribute(): float
    {
        $baseWeight = $this->artesaniaVariant ? $this->artesaniaVariant->tipoEmbalaje->peso_base_kg : 0.00;
        return $this->artesaniaVariant->peso_item_kg + $baseWeight;
    }
}
