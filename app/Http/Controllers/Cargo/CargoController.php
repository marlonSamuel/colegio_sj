<?php

namespace App\Http\Controllers\Cargo;

use App\Cargo;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CargoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:empleado')->except(['index']);
    }

    public function index()
    {
        $cargos = Cargo::all();

        return $this->showAll($cargos);
    }
}
