<?php

namespace App;

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
}
