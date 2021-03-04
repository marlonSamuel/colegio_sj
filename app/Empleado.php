<?php

namespace App;
use App\Cargo;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Empleado extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
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
