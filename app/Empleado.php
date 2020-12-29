<?php

namespace App;
use App\Cargo;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';

    protected $fillable= [
    	'cui',
    	'codigo',
    	'primer_nombre',
    	'segundo_nombre',
    	'primer_apellido',
    	'segundo_apellido',
    	'fecha_nac',
    	'telefono',
    	'email',
    	'direccion',
    	'cargo_id'
    ];

    public function cargo(){
        return $this->belongsTo(Cargo::class);
    }
}
