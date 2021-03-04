<?php

namespace App;
use App\Cuota;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ConceptoPago extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'concepto_pagos';
    protected $fillable = [
        'nombre',
        'obligatorio',
        'is_parcial',
        'forma_pago',
        'mora'
    ];
    
    public function cuotas()
    {
    	return $this->hasMany(Cuota::class,'concepto_pago_id');
    }
}
