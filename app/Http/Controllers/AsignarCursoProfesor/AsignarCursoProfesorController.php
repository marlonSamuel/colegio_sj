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
        $curso_niveles = AsignarCursoProfesor::all();
        return $this->showAll($curso_niveles);
    }
    public function getAll($idProfesor,$ciclo_id)
    {
        $curso_niveles = AsignarCursoProfesor::where([['empleado_id',$idProfesor],['ciclo_id',$ciclo_id]])
        ->with('curso_grado_nivel','curso_grado_nivel.grado_nivel_educativo.nivelEducativo','curso_grado_nivel.grado_nivel_educativo.grado','curso_grado_nivel.curso')
        ->get();
        $curso_niveles = $this->infoProfesor($curso_niveles);
    }
    /*public function getAll($idProfesor)
    {
        $curso_niveles = AsignarCursoProfesor::where('empleado_id',$idProfesor)
                                              ->with('curso_grado_nivel.curso',
                                                     'curso_grado_nivel.grado_nivel_educativo.grado',
                                                     'curso_grado_nivel.grado_nivel_educativo.nivelEducativo')->get();
        return $this->showAll($curso_niveles);
    }*/

    public function cursoGradoNivel(){
        $curso_niveles = CursoGradNivEd::with('grado_nivel_educativo','grado_nivel_educativo.nivelEducativo','grado_nivel_educativo.grado','curso')->get();

        $data = $this->prepareData($curso_niveles);
        return $this->showQuery($data);
    }

    public function infoProfesor($curso_niveles){
        $data = collect();
        foreach ($curso_niveles as $key => $value) {
            $info = collect([
                'id' => $value->id,
                'empleado_id'=> $value->empleado_id,
                'ciclo_id'=> $value->ciclo_id,
                'curso_grad_niv_edu_id' => $value->curso_grad_niv_edu_id,
                'nombre'=>$value->curso_grado_nivel->grado_nivel_educativo->nivelEducativo->nombre .'/'.$value->curso_grado_nivel->grado_nivel_educativo->grado->nombre.'/'.$value->curso_grado_nivel->curso->nombre
            ]);
            $data->push($info);
        }
        return $data;
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
        foreach ($request->secciones as $seccion) {
            $curso_prof_secc = AsignarCursoProfSec::create([
                'asignar_curso_profresor_id'=>$curso_nivel->id,
                'asignar_curso_profesor_id'=>$curso_nivel->id,
                'seccion_id'=>$seccion
            ]);
        }
        DB::commit();
        return $this->showOne($curso_nivel,201);
    }

    /* *
        obtener asignaciones por cursos y profesores
     */
    public function show(AsignarCursoProfesor $asignar_cursos_profesore)
    {
        $asignaciones = $asignar_cursos_profesore->asignaciones;
        return $this->showAll($asignaciones);
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