<?php

namespace App;

use App\PeriodoAcademico;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TipoPeriodo extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'tipo_periodos';
    
    protected $fillable = [
        'nombre',
	];

	public function periodos_academicos()
	{
		return $this->hasMany(PeriodoAcademico::class);
	}
}
