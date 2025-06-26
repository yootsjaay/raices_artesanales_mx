<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ArtesaniaVariant
 * 
 * @property int $id
 * @property int $artesania_id
 * @property string|null $sku
 * @property string|null $size
 * @property string|null $color
 * @property string|null $material_variant
 * @property float $price_adjustment
 * @property int $stock
 * @property string|null $image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Artesania $artesania
 * @property Collection|CartItem[] $cart_items
 *
 * @package App\Models
 */
class ArtesaniaVariant extends Model
{
	protected $table = 'artesania_variants';

	protected $casts = [
		'artesania_id' => 'int',
		'price_adjustment' => 'float',
		'stock' => 'int'
	];

	protected $fillable = [
		'artesania_id',
		'sku',
		'size',
		'color',
		'material_variant',
		'price_adjustment',
		'stock',
		'image'
	];

	public function artesania()
	{
		return $this->belongsTo(Artesania::class);
	}

	public function cart_items()
	{
		return $this->hasMany(CartItem::class);
	}
}
