<?php

namespace App;

use App\TipoPeriodo;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PeriodoAcademico extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
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
