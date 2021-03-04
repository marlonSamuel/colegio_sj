<?php

namespace App;

use App\GradoNivelEducativo;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class NivelEducativo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'niveles_educativos';

    protected $fillable= [
    	'nombre',
        'resolucion',
        'fecha',
    	'es_carrera'
    ];

    public function grados()
    {
    	return $this->hasMany(GradoNivelEducativo::class,'nivel_educativo_id');
    }
}
