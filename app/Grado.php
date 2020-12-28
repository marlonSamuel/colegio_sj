<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{

    protected $table = 'grados';

    protected $fillable= [
    	'nombre'
    ];
}
