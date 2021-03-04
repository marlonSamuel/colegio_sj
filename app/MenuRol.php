<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MenuRol extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'menu_rols';
    protected $fillable = ['rol_id','menu_id'];
}
