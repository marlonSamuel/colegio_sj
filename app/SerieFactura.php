<?php

namespace App;

use App\Pago;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SerieFactura extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'serie_facturas';
    protected $fillable = [
        'serie',
        'actual',
        'no_facturas',
        'no_inicial',
        'no_actual'
    ];

    public function pagos()
    {
    	return $this->hasMany(Pago::class);
    }
}
