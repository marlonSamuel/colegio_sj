<?php

namespace App;

use App\Asignacion;
use App\Inscripcion;
use App\AlumnoSerie;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsignacionAlumno extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
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
        'hora_inicio_cuestionario'
    ];

    public function asignacion(){
    	return $this->belongsTo(Asignacion::class,'asignacion_id');
    }

    public function inscripcion(){
        return $this->belongsTo(Inscripcion::class,'inscripcion_id');
    }

    public function series()
    {
        return $this->hasMany(AlumnoSerie::class,'asignacion_alumno_id');
    }
}
