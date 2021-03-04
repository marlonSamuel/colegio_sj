<?php

namespace App;
use App\AlumnoPregunta;
use App\Respuesta;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AlumnoRespuesta extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'alumno_respuestas';

    protected $fillable= [
    	'alumno_pregunta_id',
    	'respuesta_id',
    	'nota',
    	'correcto',
        'correcto_alumno', //respuesta que el alumno considera correcta
    	'respuesta'
    ];

    public function pregunta(){
    	return $this->belongsTo(AlumnoPregunta::class);
    }

    public function respuesta_a(){
    	return $this->belongsTo(Respuesta::class,'respuesta_id');
    }
}
