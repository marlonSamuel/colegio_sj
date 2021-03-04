<?php

namespace App;

use App\Municipio;
use App\TelefonoApoderado;
use App\ApoderadoAlumno;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Apoderado extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'apoderados';
    
    protected $fillable = [
		'cui',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'fecha_nac',
        'estado_civil',
        'nacionalidad',
        'email',
        'direccion',
        'ocupacion',
        'municipio_id',
        'nacionalidad',
        'nit'
	];

    public function telefonos()
    {
        return $this->hasMany(TelefonoApoderado::class);
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class);
    }

    public function alumnos()
    {
        return $this->hasMany(ApoderadoAlumno::class,'apoderado_id');
    }
}
