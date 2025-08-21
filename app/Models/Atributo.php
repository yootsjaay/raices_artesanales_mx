<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Atributo extends Model
{
    use HasFactory;

    protected $table = 'atributo';

    protected $fillable = [
        'nombre',
    ];

    /**
     * Relación con los valores de atributo por variante.
     */
    public function valores(): HasMany
    {
        // El framework infiere que la clave foránea es 'atributo_id'
        // por lo que no es necesario pasarla como segundo argumento.
        return $this->hasMany(AtributoArtesaniaVariant::class);
    }
}
