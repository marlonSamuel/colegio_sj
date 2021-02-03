<?php

namespace App\Http\Controllers\Ciclo;

use App\Ciclo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class CicloPeriodoAcademicoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
   
    public function index(Ciclo $ciclo)
    {
        $periodos = $ciclo->periodos_academicos()->with('periodo_academico.tipo_periodo')->get();

        return $this->showAll($periodos);
    }
}
