<?php

namespace App;
use App\Asignacion;
use App\Serie;
use App\AlumnoPregunta;
use Illuminate\Database\Eloquent\Model;

class AlumnoSerie extends Model
{
    protected $table = 'alumno_series';

    protected $fillable= [
    	'asignacion_alumno_id',
    	'serie_id',
    	'respondida',
    	'nota'
    ];

    public function asignacion(){
    	return $this->belongsTo(Asignacion::class);
    }

    public function serie(){
    	return $this->belongsTo(Serie::class,'serie_id');
    }

    public function preguntas(){
    	return $this->hasMany(AlumnoPregunta::class,'alumno_serie_id');
    }
}
