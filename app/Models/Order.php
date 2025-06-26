<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * 
 * @property int $id
 * @property int|null $user_id
 * @property float $subtotal_amount
 * @property float $shipping_cost
 * @property float $total_amount
 * @property string $status
 * @property string $payment_status
 * @property string|null $payment_id_mp
 * @property string|null $preference_id_mp
 * @property array|null $shipping_address_snapshot
 * @property array|null $billing_address_snapshot
 * @property array|null $shipping_details_snapshot
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User|null $user
 * @property Collection|OrderItem[] $order_items
 *
 * @package App\Models
 */
class Order extends Model
{
	protected $table = 'orders';

	protected $casts = [
		'user_id' => 'int',
		'subtotal_amount' => 'float',
		'shipping_cost' => 'float',
		'total_amount' => 'float',
		'shipping_address_snapshot' => 'json',
		'billing_address_snapshot' => 'json',
		'shipping_details_snapshot' => 'json'
	];

	protected $fillable = [
		'user_id',
		'subtotal_amount',
		'shipping_cost',
		'total_amount',
		'status',
		'payment_status',
		'payment_id_mp',
		'preference_id_mp',
		'shipping_address_snapshot',
		'billing_address_snapshot',
		'shipping_details_snapshot'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class);
	}
}
