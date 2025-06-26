<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentMethod
 * 
 * @property int $id
 * @property int $user_id
 * @property string|null $customer_id_mp
 * @property string|null $card_id_mp
 * @property string|null $card_brand
 * @property string|null $last_four_digits
 * @property string|null $expiration_month
 * @property string|null $expiration_year
 * @property bool $is_default
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class PaymentMethod extends Model
{
	protected $table = 'payment_methods';

	protected $casts = [
		'user_id' => 'int',
		'is_default' => 'bool'
	];

	protected $fillable = [
		'user_id',
		'customer_id_mp',
		'card_id_mp',
		'card_brand',
		'last_four_digits',
		'expiration_month',
		'expiration_year',
		'is_default'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
