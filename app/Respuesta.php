<?php

namespace App;

use App\Pregunta;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Respuesta extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'respuestas';

    protected $fillable = [
    	'pregunta_id',
        'respuesta',
        'correcta'
	];

	public function pregunta()
	{
		return $this->belongsTo(Pregunta::class);
	}
}
