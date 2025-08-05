<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Releations\HasMany;

class TipoEmbalaje extends Model
{
    use HasFactory;

    protected $table = 'tipos_embalaje';

    protected $fillable = [
        'nombre',
        'descripcion',
        'weight',
        'length',
        'width',
        'height',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the artesania variants that use this packaging type.
     */
    public function artesaniaVariants(): HasMany
    {
        return $this->hasMany(ArtesaniaVariant::class, 'tipo_embalaje_id');
    }
}