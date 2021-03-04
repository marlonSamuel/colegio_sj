<?php

namespace App;

use App\Pago;
use App\Ciclo;
use App\Alumno;
use App\GradoNivelEducativo;
use App\AsignacionSeccion;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Inscripcion extends Model implements Auditable
{
    
    use \OwenIt\Auditing\Auditable;
    protected $table = 'inscripciones';

    protected $fillable= [
        'numero',
        'documento',
    	'alumno_id',
    	'ciclo_id',
    	'grado_nivel_educativo_id',
    	'fecha',
        'jornada'
    ];

    public function alumno()
    {
    	return $this->belongsTo(Alumno::class,'alumno_id');
    }

    public function ciclo()
    {
    	return $this->belongsTo(Ciclo::class,'ciclo_id');
    }

    public function grado_nivel_educativo()
    {
    	return $this->belongsTo(GradoNivelEducativo::class,'grado_nivel_educativo_id');
    }

    public function pagos()
    {
    	return $this->hasMany(Pago::class,'inscripcion_id');
    }

    public function seccion()
    {
        return $this->hasOne(AsignacionSeccion::class,'inscripcion_id');
    }
}
