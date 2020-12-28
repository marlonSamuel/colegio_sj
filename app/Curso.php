<?php

namespace App;

use Illuminate\Database\Eloquent\Model; //import model

class Curso extends Model
{

    protected $table = 'cursos';
    protected $fillable= [
    	'nombre'
    ];

}
