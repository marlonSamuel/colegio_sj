<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Rol;
use App\UsuarioAlumno;
use App\UsuarioEmpleado;
use App\UsuarioRepresentante;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, Notifiable, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'codigo', 'email', 'password','rol_id', 'activo'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function rol(){
        return $this->belongsTo(Rol::class);
    }

    public function empleado(){
        return $this->hasOne(UsuarioEmpleado::class,'user_id');
    }

    public function alumno(){
        return $this->hasOne(UsuarioAlumno::class,'user_id');
    }

    public function representante(){
        return $this->hasOne(UsuarioRepresentante::class,'user_id');
    }

     public function findForPassport($username) {
        return $user = $this->where('email', $username)->orWhere('codigo', $username)->first();
    }
}
