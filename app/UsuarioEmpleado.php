<?php

namespace App;
use App\Empleado;
use App\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UsuarioEmpleado extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
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
