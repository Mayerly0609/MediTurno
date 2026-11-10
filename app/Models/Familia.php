<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Familia
 * 
 * @property int $id
 * @property string $nombre
 * @property string $abreviatura
 * @property int|null $idFamiliaPadre
 * @property string $esPDM
 * @property string $reconstruir
 * @property string $mrp
 * @property string $estado
 * @property int|null $idUsuarioCreacion
 * @property Carbon $fechaCreacion
 * @property int|null $idUsuarioActualizacion
 * @property Carbon|null $fechaActualizacion
 * 
 * @property Familia|null $familia
 * @property Collection|Familia[] $familia
 *
 * @package App\Models
 */
class Familia extends Model
{
	protected $table = 'familia';
	public $timestamps = false;

	protected $casts = [
		'idFamiliaPadre' => 'int',
		'idUsuarioCreacion' => 'int',
		'fechaCreacion' => 'datetime',
		'idUsuarioActualizacion' => 'int',
		'fechaActualizacion' => 'datetime'
	];

	protected $fillable = [
		'nombre',
		'abreviatura',
		'idFamiliaPadre',
		'esPDM',
		'reconstruir',
		'mrp',
		'estado',
		'idUsuarioCreacion',
		'fechaCreacion',
		'idUsuarioActualizacion',
		'fechaActualizacion'
	];

	public function parent()
	{
		return $this->belongsTo(Familia::class, 'idFamiliaPadre');
	}

	public function children()
	{
		return $this->hasMany(Familia::class, 'idFamiliaPadre');
	}
}
