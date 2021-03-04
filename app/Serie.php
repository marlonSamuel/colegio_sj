<?php

namespace App;

use App\Asignacion;
use App\Pregunta;
use App\AlumnoSerie;
use OwenIt\Auditing\Contracts\Auditable;

use Illuminate\Database\Eloquent\Model;

class Serie extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
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

    public function alumno_series(){
        return $this->hasMany(AlumnoSerie::class,'serie_id');
    }
}
