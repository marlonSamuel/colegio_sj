<?php

namespace App;
use App\Empleado;

use Illuminate\Database\Eloquent\Model;


class Cargo extends Model
{
    protected $table = 'cargos';
    protected $fillable= [
    	'nombre'
    ];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}
