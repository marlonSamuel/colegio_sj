<?php

namespace App;

use App\Seccion;
use App\AsignarCursoProfesor;

use Illuminate\Database\Eloquent\Model;

class AsignarCursoProfSec extends Model
{
	protected $table = 'asignar_curso_prof_sec';

    protected $fillable = [
		'asignar_curso_profesor_id',
        'seccion_id'
	];

    public function curso_profesor(){
        return $this->belongsTo(AsignarCursoProfesor::class);
    }
	public function seccion()
	{
		return $this->belongsTo(Seccion::class);
	}
}