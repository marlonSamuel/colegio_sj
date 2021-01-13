<?php

namespace App;

use App\Asignacion;
use App\Pregunta;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model
{
    protected $table = 'series';

    protected $fillable= [
    	'asignacion_id',
    	'descripcion',
    	'tipo_serie',
    	'nota'
    ];

    public function asignacion(){
    	return $this->belongsTo(Asignacion::class);
    }

    public function preguntas(){
    	return $this->hasMany(Pregunta::class,'serie_id');
    }
}
