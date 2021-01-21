<?php

namespace App;
use App\Ciclo;
use App\PeriodoAcademico;
use Illuminate\Database\Eloquent\Model;

class CicloPeriodoAcademico extends Model
{
    protected $table = 'ciclo_periodo_academicos';
    protected $fillable = [
        'ciclo_id',
        'periodo_academico_id',
        'inicio',
        'fin',
        'actual',
        'nota'
    ];

    public function ciclo()
    {
    	return $this->belongsTo(Ciclo::class,'ciclo_id');
    }

    public function periodo_academico()
    {
        return $this->belongsTo(PeriodoAcademico::class,'periodo_academico_id');
    }
}