<?php

namespace App;

use App\Pregunta;

use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
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
