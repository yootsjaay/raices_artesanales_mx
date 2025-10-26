<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoEmbalaje extends Model
{
    use HasFactory;

    protected $table = 'tipos_embalaje';

    protected $fillable = [
        'package_envia_id', // 👈 importante
        'nombre',
        'descripcion',
        'weight',
        'length',
        'width',
        'height',
        'is_active',
    ];

    protected $casts = [
        'package_envia_id' => 'integer',
        'weight' => 'float',
        'length' => 'float',
        'width' => 'float',
        'height' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Relación con las variantes de artesanía que usan este tipo de embalaje.
     */
    public function artesaniaVariants(): HasMany
    {
        return $this->hasMany(ArtesaniaVariant::class, 'tipo_embalaje_id');
    }
}
