<?php

namespace App;
use App\Alumno;
use App\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UsuarioAlumno extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'usuario_alumnos';
    protected $fillable= [
    	'alumno_id',
    	'user_id'
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
