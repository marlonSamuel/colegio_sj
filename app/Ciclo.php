<?php

namespace App;
use App\Cuota;
use App\Inscripcion;
use App\CicloPeriodoAcademico;
use Illuminate\Database\Eloquent\Model;

class Ciclo extends Model
{
    protected $table = 'ciclos';
    protected $fillable = [
        'ciclo',
        'actual',
        'inicio',
        'fin'
    ];

    public function inscripciones()
    {
    	return $this->hasMany(Inscripcion::class);
    }

    public function cuotas()
    {
        return $this->hasMany(Cuota::class);
    }
    public function periodos_academicos()
    {
        return $this->hasMany(CicloPeriodoAcademico::class);
    }
}
