<?php

namespace App;

use App\Serie;
use App\AsignarCursoProfesor;
use App\AsignacionAlumno;
use App\CicloPeriodoAcademico;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Asignacion extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = "asignacions";

    protected $fillable = [
    	'asignar_curso_profesor_id',
    	'cuestionario',
    	'nota',
        'titulo',
    	'descripcion',
    	'fecha_entrega',
    	'fecha_habilitacion',
    	'tiempo',
    	'entrega_tarde',
    	'adjunto',
        'flag_tiempo',
        'ciclo_periodo_academico_id'
    ];

    public function asignar_curso_profesor(){
    	return $this->belongsTo(AsignarCursoProfesor::class,'asignar_curso_profesor_id');
    }

    public function series(){
        return $this->hasMany(Serie::class);
    }

    public function alumnos(){
        return $this->hasMany(AsignacionAlumno::class,'asignacion_id');
    }

    public function periodo(){
        return $this->belongsTo(CicloPeriodoAcademico::class,'ciclo_periodo_academico_id');
    }
}
