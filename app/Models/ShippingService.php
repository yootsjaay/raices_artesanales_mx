<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ShippingService
 * 
 * @property int $id
 * @property string $carrier_name
 * @property string $service_name
 * @property string $service_code
 * @property string $currency
 * @property float $total_price
 * @property string $delivery_estimate
 * @property string|null $tracking_link
 * @property array|null $raw_response_data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Cart[] $carts
 *
 * @package App\Models
 */
class ShippingService extends Model
{
	protected $table = 'shipping_services';

	protected $casts = [
		'total_price' => 'float',
		'raw_response_data' => 'json'
	];

	protected $fillable = [
		'carrier_name',
		'service_name',
		'service_code',
		'currency',
		'total_price',
		'delivery_estimate',
		'tracking_link',
		'raw_response_data'
	];

	public function carts()
	{
		return $this->hasMany(Cart::class);
	}
}
