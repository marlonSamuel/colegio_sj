<?php

namespace App;

use App\Ciclo;
use App\CursoGradNivEd;
use App\Empleado;
use Illuminate\Database\Eloquent\Model;

class AsignarCursoProfesor extends Model
{
	protected $table = 'asignar_curso_profesor';

    protected $fillable = [
		'empleado_id',
        'ciclo_id',
        'curso_grad_niv_edu_id'
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
		return $this->belongsTo(CursoGradNivEd::class);
	}
}