<?php

namespace App;

use App\Seccion;
use App\AsignarCursoProfesor;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsignarCursoProfSec extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
	protected $table = 'asignar_curso_prof_sec';

    protected $fillable = [
		'asignar_curso_profesor_id',
        'seccion_id'
	];

    public function curso_profesor(){
        return $this->belongsTo(AsignarCursoProfesor::class,'asignar_curso_profesor_id');
    }
	public function seccion()
	{
		return $this->belongsTo(Seccion::class);
	}
}