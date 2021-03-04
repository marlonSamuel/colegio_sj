<?php

namespace App;
use App\Empleado;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class Cargo extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'cargos';
    protected $fillable= [
    	'nombre'
    ];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}
