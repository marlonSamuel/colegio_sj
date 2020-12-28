<?php

namespace App;
use App\Cuota;
use Illuminate\Database\Eloquent\Model;

class ConceptoPago extends Model
{
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
