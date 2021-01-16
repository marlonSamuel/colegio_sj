<?php

namespace App;

use App\Asignacion;
use App\Inscripcion;

use Illuminate\Database\Eloquent\Model;

class AsignacionAlumno extends Model
{
    protected $table = "asignacion_alumnos";

    protected $fillable = [
    	'asignacion_id',
    	'inscripcion_id',
    	'nota',
    	'descripcion',
    	'fecha_entrega',
    	'entrega_tarde',
    	'adjunto',
    	'calificado',
    	'entregado',
        'observaciones',
        'hora_inicio_cuestionario',
        'hora_finalizo_cuestionario'
    ];

    public function asignacion(){
    	return $this->belongsTo(Asignacion::class,'asignacion_id');
    }

    public function inscripcion(){
        return $this->belongsTo(Inscripcion::class,'inscripcion_id');
    }
}
