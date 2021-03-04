<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Seccion extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'secciones';

    protected $fillable= [
    	'seccion'
    ];
}
