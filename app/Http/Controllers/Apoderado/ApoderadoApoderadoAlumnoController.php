<?php

namespace App\Http\Controllers\Apoderado;

use App\Apoderado;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ApoderadoApoderadoAlumnoController extends ApiController
{
    public function __construct()
    {
        //parent::__construct();
    }
   
    public function index(Apoderado $apoderado)
    {
        $alumnos = $apoderado->alumnos()->with('alumno')->get()->pluck('alumno')->values();
        return $this->showAll($alumnos);
    }
}
