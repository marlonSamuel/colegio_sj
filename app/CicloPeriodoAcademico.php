<?php

namespace App;
use App\Ciclo;
use App\PeriodoAcademico;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CicloPeriodoAcademico extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
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