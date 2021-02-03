<?php

namespace App\Http\Controllers\PeriodoAcademico;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\PeriodoAcademico;
use Illuminate\Http\Request;

class PeriodoAcademicoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $periodos = PeriodoAcademico::all();
        return $this->showAll($periodos);
    }
}
