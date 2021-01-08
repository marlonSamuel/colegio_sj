<?php

namespace App;

use App\PeriodoAcademico;
use Illuminate\Database\Eloquent\Model;

class TipoPeriodo extends Model
{
    protected $table = 'tipo_periodos';
    
    protected $fillable = [
        'nombre',
	];

	public function periodos_academicos()
	{
		return $this->hasMany(PeriodoAcademico::class);
	}
}
