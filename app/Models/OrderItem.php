<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderItem
 * 
 * @property int $id
 * @property int $order_id
 * @property int|null $artesania_id
 * @property string $name
 * @property float $price
 * @property int $quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Artesania|null $artesania
 * @property Order $order
 *
 * @package App\Models
 */
class OrderItem extends Model
{
	protected $table = 'order_items';

	protected $casts = [
		'order_id' => 'int',
		'artesania_id' => 'int',
		'price' => 'float',
		'quantity' => 'int'
	];

	protected $fillable = [
		'order_id',
		'artesania_id',
		'name',
		'price',
		'quantity'
	];

	public function artesania()
	{
		return $this->belongsTo(Artesania::class);
	}

	public function order()
	{
		return $this->belongsTo(Order::class);
	}
}
