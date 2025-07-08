<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Ubicacione
 * 
 * @property int $id
 * @property string $nombre
 * @property string $tipo
 * @property string $descripcion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Artesania[] $artesanias
 *
 * @package App\Models
 */
class Ubicacion extends Model
{
	protected $table = 'ubicaciones';

	protected $fillable = [
		'nombre',
		'tipo',
		'descripcion'
	];

	public function artesanias()
	{
		return $this->hasMany(Artesania::class, 'ubicacion_id');
	}
	
public function getRouteKeyName()
{
    return 'slug';
}

protected static function booted()
{
    static::creating(function ($ubicacion) {
        if (empty($ubicacion->slug)) {
            $baseSlug = Str::slug($ubicacion->nombre);
            $slug = $baseSlug;
            $count = 1;

            while (static::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }

            $ubicacion->slug = $slug;
        }
    });
}
}
