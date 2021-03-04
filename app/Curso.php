<?php

namespace App;

use Illuminate\Database\Eloquent\Model; //import model
use OwenIt\Auditing\Contracts\Auditable;

class Curso extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'cursos';
    protected $fillable= [
    	'nombre'
    ];

}
