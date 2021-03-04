<?php

namespace App;

use App\Ciclo;
use App\Asignacion;
use App\CursoGradNivEd;
use App\AsignarCursoProfSec;
use App\Empleado;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsignarCursoProfesor extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
	protected $table = 'asignar_curso_profesor';

    protected $fillable = [
		'empleado_id',
        'ciclo_id',
        'curso_grad_niv_edu_id',
        'jornada'
	];

	
    public function profesor(){
        return $this->belongsTo(Empleado::class);
    }
	public function ciclo()
	{
		return $this->belongsTo(Ciclo::class);
	}

	public function curso_grado_nivel()
	{
		return $this->belongsTo(CursoGradNivEd::class,'curso_grad_niv_edu_id');
	}

	public function asignaciones(){
		return $this->hasMany(Asignacion::class,'asignar_curso_profesor_id');
	}

	public function secciones(){
		return $this->hasMany(AsignarCursoProfSec::class,'asignar_curso_profesor_id');
	}
}