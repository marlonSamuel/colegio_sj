<?php
namespace App\Traits;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

trait AdminActions
{
	public function before($user,$ability)
	{
		if($user->rol->rol == 'admin')
		{
			return true;
		}
	}
}