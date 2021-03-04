<?php

namespace App;
use App\Apoderado;
use App\User;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UsuarioRepresentante extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'usuario_representantes';
    protected $fillable= [
    	'apoderado_id',
    	'user_id'
    ];

    public function apoderado()
    {
        return $this->belongsTo(Apoderado::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
