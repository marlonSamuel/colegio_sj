<?php

namespace App\Http\Controllers\AsignarCursoProfesor;

use App\CursoGradNivEd;
use App\AsignarCursoProfesor;
use App\AsignarCursoProfSec;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class AsignarCursoProfesorController extends ApiController
{
    public function __construct()
    {
        //parent::__construct();
        //$this->middleware('scope:niveleducativo')->except(['index']);
    }

    public function index()
    {
        $curso_niveles = CursoGradNivEd::with('grado_nivel_educativo','grado_nivel_educativo.nivelEducativo','grado_nivel_educativo.grado','curso')->get();

        $data = $this->prepareData($curso_niveles);
        return $this->showQuery($data);
    }

    public function cursoGradoNivel(){
        $curso_niveles = CursoGradNivEd::with('grado_nivel_educativo','grado_nivel_educativo.nivelEducativo','grado_nivel_educativo.grado','curso')->get();

        $data = $this->prepareData($curso_niveles);
        return $this->showQuery($data);
    }

    public function prepareData($curso_niveles){
        $data = collect();
        foreach ($curso_niveles as $key => $value) {
            $info = collect([
                'id' => $value->id,
                'nombre'=>$value->grado_nivel_educativo->nivelEducativo->nombre .'/'.$value->grado_nivel_educativo->grado->nombre.'/'.$value->curso->nombre
            ]);
            $data->push($info);
        }
        return $data;
    }


    /**
     */
    public function store(Request $request)
    {
        $reglas = [
            'empleado_id' => 'required|integer',
            'ciclo_id' => 'required|integer',
            'curso_grad_niv_edu_id'=>'required|integer'
        ];
        
        $this->validate($request, $reglas);
        DB::beginTransaction();
        $data = $request->all();
        $curso_nivel = AsignarCursoProfesor::create($data);
        foreach ($request->seccion as $key => $value) {
            $curso_prof_secc = AsignarCursoProfSec::create([
                'asignar_curso_profersor_id'=>$curso_nivel->id,
                'seccion_id'=>$value['seccion_id']
            ]);
        }
        DB::commit();
        return $this->showOne($curso_nivel,201);
    }

    /**
     */
    public function show(Curso $curso)
    {
        
    }

    /**
     */
    public function update(Request $request, Curso $curso)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Curso  $curso
     * @return \Illuminate\Http\Response
     */
    public function destroy(AsignarCursoProfesor $asignarCursoProfesor)
    {
        $asignarCursoProfesor->delete();

        return $this->showOne($asignarCursoProfesor);
    }
}