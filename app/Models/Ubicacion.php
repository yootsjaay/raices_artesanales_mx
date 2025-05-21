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
class Ubicacion extends Model
{
	protected $table = 'ubicaciones';

	protected $fillable = [
		'nombre',
		'tipo',
		'descripcion'
	];

	 public function artesanias()
    {
        // Una ubicación tiene muchas artesanías (directamente, a través de ubicacion_id en la tabla artesanias)
        return $this->hasMany(Artesania::class, 'ubicacion_id');
    }

    // <--- ¡AÑADIR ESTA NUEVA FUNCIÓN PARA LA RELACIÓN ARTESANOS! --->
    public function artesanos()
    {
        // Una ubicación tiene muchos artesanos (a través de ubicacion_id en la tabla artesanos)
        return $this->hasMany(Artesano::class, 'ubicacion_id');
    }
}
