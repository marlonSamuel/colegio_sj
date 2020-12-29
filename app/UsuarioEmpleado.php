<?php

namespace App;
use App\Empleado;
use App\User;
use Illuminate\Database\Eloquent\Model;

class UsuarioEmpleado extends Model
{
    protected $table = 'usuario_empleados';
    protected $fillable= [
    	'empleado_id',
    	'user_id'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
     
}
