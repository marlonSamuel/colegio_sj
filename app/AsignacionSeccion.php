<?php

namespace App;

use App\Seccion;
use App\Inscripcion;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsignacionSeccion extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
	protected $table = 'asignacion_seccions';

    protected $fillable = [
		'inscripcion_id',
        'seccion_id'
	];

	public function inscripcion()
	{
		return $this->belongsTo(Inscripcion::class);
	}

	public function seccion()
	{
		return $this->belongsTo(Seccion::class);
	}
}
