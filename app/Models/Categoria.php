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
 * Class Categoria
 * 
 * @property int $id
 * @property string $nombre
 * @property string $descripcion
 * @property string $imagen
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Artesania[] $artesanias
 *
 * @package App\Models
 */
class Categoria extends Model
{
	protected $table = 'categorias';

	protected $fillable = [
		'nombre',
		'descripcion',
		'imagen'
	];

	public function artesanias()
	{
		return $this->hasMany(Artesania::class);
	}
		public function getRouteKeyName()
	{
		return 'slug';
	}
	 protected static function booted()
    {
        static::creating(function ($categoria) {
            if (empty($categoria->slug)) {
                $baseSlug = Str::slug($categoria->nombre);
                $slug = $baseSlug;
                $count = 1;

                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $count;
                    $count++;
                }

                $categoria->slug = $slug;
            }
        });
    }

	
}
