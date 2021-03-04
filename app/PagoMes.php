<?php

namespace App;

use App\Mes;
use App\Pago;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PagoMes extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'pago_meses';

    protected $fillable=[
        'pago_id',
        'mes_id'
    ];

    public function pago()
    {
        return $this->belongsTo(Pago::class);
    }

    public function mes()
    {
        return $this->belongsTo(Mes::class,'mes_id');
    }
}
