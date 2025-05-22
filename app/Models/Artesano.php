<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Artesano
 * 
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Artesania[] $artesanias
 *
 * @package App\Models
 */
class Artesano extends Model
{
	protected $table = 'artesanos';

	public function artesanias()
	{
		return $this->hasMany(Artesania::class, 'artesano_id');
	}
	 public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id'); // Asegúrate que la FK sea 'ubicacion_id'
    }
}
