<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 * 
 * @property int $id
 * @property int $user_id
 * @property int $artesania_id
 * @property string $content
 * @property int|null $rating
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Artesania $artesania
 * @property User $user
 *
 * @package App\Models
 */
class Comment extends Model
{
	protected $table = 'comments';

	protected $casts = [
		'user_id' => 'int',
		'artesania_id' => 'int',
		'rating' => 'int'
	];

	protected $fillable = [
		'user_id',
		'artesania_id',
		'content',
		'rating'
	];

	public function artesania()
	{
		return $this->belongsTo(Artesania::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
