<?php

namespace App;

use App\Alumno;
use App\Apoderado;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ApoderadoAlumno extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'apoderados_alumnos';
    
    protected $fillable = [
		'alumno_id',
        'apoderado_id',
        'responsable',
        'tipo_apoderado'
	];

	public function alumno()
	{
		return $this->belongsTo(Alumno::class);
	}

	public function apoderado()
	{
		return $this->belongsTo(Apoderado::class);
	}
}
