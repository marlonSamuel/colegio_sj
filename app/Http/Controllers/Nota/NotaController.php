<?php

namespace App\Http\Controllers\Nota;

use App\Nota;
use App\CicloPeriodoAcademico;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class NotaController extends ApiController
{
    public function __construct()
    {
        //parent::__construct();
        
    }

    public function index()
    {
        $nota = Nota::all();
        return $this->showAll($nota);
    }

    public function getAll($idAsignacionCurso, $idBimestre){
        $notas = Nota::where([['asignar_curso_profesor_id',$idAsignacionCurso],['ciclo_periodo_academico_id',$idBimestre]])->get();
        return $this->showAll($notas);
    }
    public function getPeriodoAcademico($idCiclo){
        $periodos = CicloPeriodoAcademico::where('ciclo_id',$idCiclo)->with('periodo_academico')->get();
        return $this->showAll($periodos);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        
        $data = $request->all();
        $nota = Nota::create($data);

        return $this->showOne($nota,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Nota  $nota
     * @return \Illuminate\Http\Response
     */
    public function show(Nota $nota)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Nota  $nota
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nota $nota)
    {

        $nota->nombre = $request->nombre;

         if (!$nota->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $nota->save();
        return $this->showOne($nota);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Curso  $curso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nota $nota)
    {
        $nota->delete();

        return $this->showOne($nota);
    }
}