<?php

namespace App\Http\Controllers\Ciclo;

use App\Ciclo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class CicloInscripcionController extends ApiController
{
   public function __construct()
    {
        parent::__construct();
    }

    public function index(Ciclo $ciclo)
    {
        $inscripciones = $ciclo->inscripciones()->with('ciclo','alumno','grado_nivel_educativo.grado','grado_nivel_educativo.nivelEducativo')->get();
        
        return $this->showAll($inscripciones);
    }
}
