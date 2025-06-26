<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CartItem
 * 
 * @property int $id
 * @property int $cart_id
 * @property int $artesania_id
 * @property int $quantity
 * @property float $price
 * @property int|null $artesania_variant_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Artesania $artesania
 * @property ArtesaniaVariant|null $artesania_variant
 * @property Cart $cart
 *
 * @package App\Models
 */
class CartItem extends Model
{
	protected $table = 'cart_items';

	protected $casts = [
		'cart_id' => 'int',
		'artesania_id' => 'int',
		'quantity' => 'int',
		'price' => 'float',
		'artesania_variant_id' => 'int'
	];

	protected $fillable = [
		'cart_id',
		'artesania_id',
		'quantity',
		'price',
		'artesania_variant_id'
	];

	public function artesania()
	{
		return $this->belongsTo(Artesania::class);
	}

	public function artesania_variant()
	{
		return $this->belongsTo(ArtesaniaVariant::class);
	}

	public function cart()
	{
		return $this->belongsTo(Cart::class);
	}
}
