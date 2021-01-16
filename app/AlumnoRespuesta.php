<?php

namespace App;
use App\AlumnoPregunta;
use App\Respuesta;
use Illuminate\Database\Eloquent\Model;

class AlumnoRespuesta extends Model
{
    protected $table = 'alumno_respuestas';

    protected $fillable= [
    	'alumno_pregunta_id',
    	'respuesta_id',
    	'nota',
    	'correcto',
    	'respuesta'
    ];

    public function pregunta(){
    	return $this->belongsTo(AlumnoPregunta::class);
    }

    public function respuesta(){
    	return $this->belongsTo(Respuesta::class);
    }
}