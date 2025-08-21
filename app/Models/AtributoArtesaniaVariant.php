<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AtributoArtesaniaVariant extends Model
{
    use HasFactory;

    protected $table = 'atributo_artesania_variant';

    protected $fillable = [
        'artesania_variant_id',
        'atributo_id',
        'valor',
    ];

    /**
     * Relación con la variante de artesanía.
     */
    public function artesaniaVariant(): BelongsTo
    {
        return $this->belongsTo(ArtesaniaVariant::class, 'artesania_variant_id');
    }

    /**
     * Relación con el atributo. (Renombrada de "atributos" a "atributo")
     */
    public function atributo(): BelongsTo
    {
        return $this->belongsTo(Atributo::class, 'atributo_id');
    }
}