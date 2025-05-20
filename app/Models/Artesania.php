<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Artesania
 * 
 * @property int $id
 * @property string $nombre
 * @property string $descripcion
 * @property float $precio
 * @property int $stock
 * @property string $imagen_principal
 * @property string $imagen_adicionales
 * @property string $materiales
 * @property string $dimensiones
 * @property string $historia_piezas
 * @property int $artesanos_id
 * @property int $categorias_id
 * @property int $ubucacion_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Artesano $artesano
 * @property Categoria $categoria
 * @property Ubicacione $ubicacione
 *
 * @package App\Models
 */
class Artesania extends Model
{
	protected $table = 'artesanias';

	protected $casts = [
		'precio' => 'float',
		'stock' => 'int',
		'artesanos_id' => 'int',
		'categorias_id' => 'int',
		'ubucacion_id' => 'int'
	];

	protected $fillable = [
		'nombre',
		'descripcion',
		'precio',
		'stock',
		'imagen_principal',
		'imagen_adicionales',
		'materiales',
		'dimensiones',
		'historia_piezas',
		'artesanos_id',
		'categorias_id',
		'ubucacion_id'
	];

	public function artesano()
	{
		return $this->belongsTo(Artesano::class, 'artesanos_id');
	}

	public function categoria()
	{
		return $this->belongsTo(Categoria::class, 'categorias_id');
	}

	public function ubicacione()
	{
		return $this->belongsTo(Ubicacione::class, 'ubucacion_id');
	}
}
