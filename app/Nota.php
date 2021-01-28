<?php

namespace App;
use App\Inscripcion;
use App\CicloPeriodoAcademico;
use App\AsignarCursoProfesor;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table = 'notas';

    protected $fillable= [
    	'inscripcion_id',
        'ciclo_periodo_academico_id',
        'asignar_curso_profesor_id',
    	'nota'
    ];

    public function inscripcion()
    {
    	return $this->belongsTo(Inscripcion::class,'inscripcion_id');
    }

    public function periodo_academico(){
        return $this->belongsTo(CicloPeriodoAcademico::class,'ciclo_periodo_academico_id');
    }
    
    public function asignar_curso_profesor(){
        return $this->belongsTo(AsignarCursoProfesor::class,'asignar_curso_profesor_id');
    }
}
