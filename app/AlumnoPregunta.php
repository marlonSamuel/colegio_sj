<?php

namespace App;
use App\AlumnoSerie;
use App\Pregunta;
use App\AlumnoRespuesta;
use Illuminate\Database\Eloquent\Model;

class AlumnoPregunta extends Model
{
    protected $table = 'alumno_preguntas';

    protected $fillable= [
    	'alumno_serie_id',
    	'pregunta_id',
    	'nota'
    ];

    public function serie(){
    	return $this->belongsTo(AlumnoSerie::class);
    }

    public function pregunta(){
    	return $this->belongsTo(Pregunta::class);
    }

    public function respuestas(){
    	return $this->hasMany(AlumnoRespuesta::class,'alumno_pregunta_id');
    }

}
