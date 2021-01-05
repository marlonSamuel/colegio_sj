<?php

namespace App;
use App\Alumno;
use App\User;
use Illuminate\Database\Eloquent\Model;

class UsuarioAlumno extends Model
{
    protected $table = 'usuario_alumnos';
    protected $fillable= [
    	'alumno_id',
    	'user_id'
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class,);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
