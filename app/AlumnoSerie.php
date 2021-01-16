<?php

namespace App;
use Appp\Asignacion;
use Appp\Serie;
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
}
