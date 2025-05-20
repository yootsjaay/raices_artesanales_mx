<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Ubicacione
 * 
 * @property int $id
 * @property string $nombre
 * @property string $tipo
 * @property string $descripcion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Artesania[] $artesanias
 *
 * @package App\Models
 */
class Ubicacione extends Model
{
	protected $table = 'ubicaciones';

	protected $fillable = [
		'nombre',
		'tipo',
		'descripcion'
	];

	public function artesanias()
	{
		return $this->hasMany(Artesania::class, 'ubucacion_id');
	}
}
