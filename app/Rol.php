<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Menu;
use OwenIt\Auditing\Contracts\Auditable;

class Rol extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'rols';
    protected $fillable= [
    	'rol'
    ];

    public function menus()
    {
        return $this->belongsToMany(Menu::class,'menu_rols', 'rol_id', 'menu_id');
    }
}
