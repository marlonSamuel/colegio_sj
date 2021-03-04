<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Grado extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'grados';

    protected $fillable= [
    	'nombre'
    ];
}
