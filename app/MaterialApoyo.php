<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AsignarCursoProfesor;
use App\CicloPeriodoAcademico;
use OwenIt\Auditing\Contracts\Auditable;

class MaterialApoyo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = "material_apoyos";

    protected $fillable = [
    	'asignar_curso_profesor_id',
    	'descripcion',
    	'link',
    	'url',
    	'adjunto',
    	'ciclo_periodo_academico_id'
    ];

    public function asignar_curso_profesor(){
    	return $this->belongsTo(AsignarCursoProfesor::class,'asignar_curso_profesor_id');
    }
    public function ciclo_periodo_academico(){
    	return $this->belongsTo(CicloPeriodoAcademico::class,'ciclo_periodo_academico_id');
    }
}
