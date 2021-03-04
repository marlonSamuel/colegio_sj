<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Rol;
use OwenIt\Auditing\Contracts\Auditable;

class Menu extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'menus';
    protected $fillableee = ['text','path','icon','name','hide','father'];

    public function rols()
    {
        return $this->belongsToMany(Rol::class);        
    }
}
