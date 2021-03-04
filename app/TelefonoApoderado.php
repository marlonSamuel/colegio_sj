<?php

namespace App;

use App\Apoderado;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TelefonoApoderado extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'telefono_apoderados';
    
    protected $fillable = [
        'apoderado_id',
        'tipo_telefono',
        'telefono'
	];

	public function apoderado()
	{
		return $this->belongsTo(Apoderado::class);
	}
}
