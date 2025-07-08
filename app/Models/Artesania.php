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
 * Class Artesania
 * 
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 * @property float $precio
 * @property int $stock
 * @property string|null $imagen_principal
 * @property array|null $imagen_adicionales
 * @property string|null $materiales
 * @property string|null $historia_piezas
 * @property int $categoria_id
 * @property int|null $ubicacion_id
 * @property float $weight
 * @property float $length
 * @property float $width
 * @property float $height
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Categoria $categoria
 * @property Ubicacione|null $ubicacione
 * @property Collection|ArtesaniaVariant[] $artesania_variants
 * @property Collection|CartItem[] $cart_items
 * @property Collection|Comment[] $comments
 * @property Collection|OrderItem[] $order_items
 *
 * @package App\Models
 */
class Artesania extends Model
{
	protected $table = 'artesanias';

	protected $casts = [
		'precio' => 'float',
		'stock' => 'int',
    	'imagen_adicionales' => 'array', 
		'categoria_id' => 'int',
		'ubicacion_id' => 'int',
		'weight' => 'float',
		'length' => 'float',
		'width' => 'float',
		'height' => 'float'
	];

	protected $fillable = [
		'nombre',
		'descripcion',
		'precio',
		'stock',
		'imagen_principal',
		'imagen_adicionales',
		'materiales',
		'historia_piezas',
		'categoria_id',
		'ubicacion_id',
		'weight',
		'length',
		'width',
		'height'
	];

	public function categoria()
	{
		return $this->belongsTo(Categoria::class);
	}

	public function ubicacion()
	{
		return $this->belongsTo(Ubicacion::class, 'ubicacion_id');
	}

	public function artesania_variants()
	{
		return $this->hasMany(ArtesaniaVariant::class);
	}

	public function cart_items()
	{
		return $this->hasMany(CartItem::class);
	}

	public function comments()
	{
		return $this->hasMany(Comment::class);
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class);
	}
	public function getRouteKeyName()
	{
		return 'slug'; // esto le dice a Laravel que use el slug en vez del id
	}
	protected static function booted()
	{
		static::creating(function ($artesania) {
			if (empty($artesania->slug)) {
				$artesania->slug = Str::slug($artesania->nombre);
			}
		});
	}


}
