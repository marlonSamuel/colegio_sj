<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\AsignarCursoProfesor;

class MaterialApoyo extends Model
{
    protected $table = "material_apoyos";

    protected $fillable = [
    	'asignar_curso_profesor_id',
    	'descripcion',
    	'link',
    	'url',
    	'adjunto',
    ];

    public function asignar_curso_profesor(){
    	return $this->belongsTo(AsignarCursoProfesor::class,'asignar_curso_profesor_id');
    }
}
