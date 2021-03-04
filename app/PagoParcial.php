<?php

namespace App;

use App\Pago;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PagoParcial extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'pagos_parciales';
    protected $fillable= [
    	'pago_id',
    	'pago',
    	'fecha',
    	'anulado',
    	'motivo_anulado',
        'apoderado_id'
    ];

    public function pago_padre()
    {
        return $this->belongsTo(Pago::class,'pago_id');
    }
}
