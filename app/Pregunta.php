<?php

namespace App;

use App\Serie;
use App\Respuesta;
use App\AlumnoPregunta;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $table = 'preguntas';

    protected $fillable = [
    	'serie_id',
        'pregunta',
        'adjunto',
        'nota'
	];

	public function serie()
	{
		return $this->belongsTo(Serie::class);
	}

	public function respuestas(){
		return $this->hasMany(Respuesta::class);
	}

	 public function alumno_preguntas(){
    	return $this->hasMany(AlumnoPregunta::class,'pregunta_id');
    }
}
