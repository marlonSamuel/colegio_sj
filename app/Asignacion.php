<?php

namespace App;

use App\AsignarCursoProfesor;

use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    protected $table = "asignacions";

    protected $fillable = [
    	'asignar_curso_profresor_id',
    	'cuestionario',
    	'nota',
        'titulo',
    	'descripcion',
    	'fecha_entrega',
    	'fecha_habilitacion',
    	'tiempo',
    	'entrega_tarde',
    	'adjunto'
    ];

    public function asignar_curso_profesor(){
    	return $this->belongsTo(AsignarCursoProfesor::class,'asignar_curso_profesor_id');
    }
}
