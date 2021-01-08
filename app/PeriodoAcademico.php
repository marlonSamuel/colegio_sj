<?php

namespace App;

use App\TipoPeriodo;
use Illuminate\Database\Eloquent\Model;

class PeriodoAcademico extends Model
{
    protected $table = 'periodos_academicos';
    
    protected $fillable = [
        'nombre',
        'tipo_periodo_id'
	];

	public function tipo_periodo()
	{
		return $this->belongsTo(TipoPeriodo::class,'tipo_periodo_id');
	}
}
