<?php

namespace App\Http\Controllers\Asignacion;

use App\Asignacion;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class AsignacionAlumnoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**

     */
    public function index(Asignacion $asignacione)
    {
        $alumnos = $asignacione->alumnos()->with('inscripcion.alumno')->get();
        return $this->showAll($alumnos);
    }

}
