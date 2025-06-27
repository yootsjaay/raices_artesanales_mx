<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ¡Añade esta línea si falta o está incorrecta!

/**
 * Class Cart
 * 
 * @property int $id
 * @property int|null $user_id
 * @property int|null $shipping_service_id
 * @property float $shipping_cost
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property ShippingService|null $shipping_service
 * @property User|null $user
 * @property Collection|CartItem[] $cart_items
 *
 * @package App\Models
 */
class Cart extends Model
{
	    use HasFactory; // Asegúrate de que esta línea exista también

	protected $table = 'carts';

	protected $casts = [
		'user_id' => 'int',
		'shipping_service_id' => 'int',
		'shipping_cost' => 'float'
	];

	protected $fillable = [
		'user_id',
		'shipping_service_id',
		'shipping_cost'
	];

	public function shipping_service()
	{
		return $this->belongsTo(ShippingService::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function cart_items()
	{
		return $this->hasMany(CartItem::class);
	}
}
