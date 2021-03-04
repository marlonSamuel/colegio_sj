<?php

namespace App\Http\Controllers\AsignarCursoProfesor;

use App\CursoGradNivEd;
use App\AsignarCursoProfesor;
use App\AsignarCursoProfSec;
use App\Inscripcion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class AsignarCursoProfesorController extends ApiController
{
    public function __construct()
    {
        /*parent::__construct();
        $this->middleware('scope:asignarprofesores')->except(['index','getInfoProfesor','getAlumnos','cursoGradoNivel','show','getOne','getAll']);*/
    }

    public function index()
    {
        $curso_niveles = AsignarCursoProfesor::all();
        return $this->showAll($curso_niveles);
    }
    public function getAll($idProfesor, $ciclo_id)
    {
        $curso_niveles = AsignarCursoProfesor::where([['empleado_id', $idProfesor], ['ciclo_id', $ciclo_id]])
                        ->with('curso_grado_nivel.curso',
                        'curso_grado_nivel.grado_nivel_educativo.grado',
                        'curso_grado_nivel.grado_nivel_educativo.nivelEducativo')->get();
                        
        return $this->showAll($curso_niveles);
    }
    public function getInfoProfesor($idProfesor, $ciclo_id)
    {
        $curso_niveles = AsignarCursoProfesor::where([['empleado_id', $idProfesor], ['ciclo_id', $ciclo_id]])
            ->with(
                'curso_grado_nivel',
                'curso_grado_nivel.grado_nivel_educativo.nivelEducativo',
                'curso_grado_nivel.grado_nivel_educativo.grado',
                'curso_grado_nivel.curso',
                'secciones',
                'secciones.seccion'
            )
            ->get();
        $curso_niveles = $this->infoProfesor($curso_niveles);
        return $this->showAll($curso_niveles);
    }
    //obtener alumnos
    public function getAlumnos($id){
        $profesor_curso = AsignarCursoProfesor::where('id',$id)->with('curso_grado_nivel')->first();
        $secciones = $profesor_curso->secciones;

        $inscripciones = Inscripcion::where([['grado_nivel_educativo_id',$profesor_curso->curso_grado_nivel->grado_nivel_educativo_id],
                                             ['ciclo_id',$profesor_curso->ciclo_id]])
                                            ->with('seccion','alumno')
                                            ->get();

        if($profesor_curso->jornada !== "A"){
            $inscripciones = $inscripciones->where('jornada',$profesor_curso->jornada)->values();
        }

        $inscripciones_filter = $inscripciones->filter(function ($inscripcion) use($secciones) {
            foreach ($secciones as $s) {
                if($inscripcion->seccion != null && $s->seccion_id == $inscripcion->seccion->seccion_id){
                    return $inscripcion;
                }
            }
        });

        return $this->showAll($inscripciones_filter);
    }

    public function cursoGradoNivel(){
        $curso_niveles = CursoGradNivEd::with('grado_nivel_educativo','grado_nivel_educativo.nivelEducativo','grado_nivel_educativo.grado','curso')->get();
        $data = $this->prepareData($curso_niveles);
        return $this->showAll($data);
    }

    public function infoProfesor($curso_niveles)
    {
        $data = collect();
        foreach ($curso_niveles as $key => $value) {

            $info = collect([
                'id' => $value->id,
                'empleado_id' => $value->empleado_id,
                'ciclo_id' => $value->ciclo_id,
                'curso_grad_niv_edu_id' => $value->curso_grad_niv_edu_id,
                'nombre' => $value->curso_grado_nivel->grado_nivel_educativo->nivelEducativo->nombre . '/' . $value->curso_grado_nivel->grado_nivel_educativo->grado->nombre . '/' . $value->curso_grado_nivel->curso->nombre
            ]);
            $secciones_col = collect();
            foreach ($value->secciones as $key2 => $value2) {

                $seccion = collect([
                    'id' => $value2->id,
                    'seccion' => $value2->seccion->seccion
                ]);

                $secciones_col->push($seccion);
            }
            $info['secciones'] = $secciones_col;

            $data->push($info);
        }
        return $data;
    }

    public function prepareData($curso_niveles)
    {
        $data = collect();
        foreach ($curso_niveles as $key => $value) {
            $info = collect([
                'id' => $value->id,
                'nombre' => $value->grado_nivel_educativo->nivelEducativo->nombre . '/' . $value->grado_nivel_educativo->grado->nombre . '/' . $value->curso->nombre,
                'grado_nivel_educativo_id'=>$value->grado_nivel_educativo->id
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
            'curso_grad_niv_edu_id' => 'required|integer',
            'jornada'=>'required'
        ];

        $this->validate($request, $reglas);
        DB::beginTransaction();
        $data = $request->all();
        $curso_nivel = AsignarCursoProfesor::create($data);
        foreach ($request->secciones as $seccion) {
            $curso_prof_secc = AsignarCursoProfSec::create([
                'asignar_curso_profresor_id' => $curso_nivel->id,
                'asignar_curso_profesor_id' => $curso_nivel->id,
                'seccion_id' => $seccion
            ]);
        }
        DB::commit();
        return $this->showOne($curso_nivel, 201);
    }

    /* *
        obtener asignaciones por cursos y profesores
     */
    public function show(AsignarCursoProfesor $asignar_cursos_profesore)
    {
        $asignaciones = $asignar_cursos_profesore->asignaciones()->with('periodo.periodo_academico','periodo.ciclo')->get();
        return $this->showAll($asignaciones);
    }

    //obtener uno
    public function getOne($id){
        $data = AsignarCursoProfesor::where('id',$id)
                                            ->with('curso_grado_nivel.grado_nivel_educativo.grado',
                                                'curso_grado_nivel.grado_nivel_educativo.nivelEducativo',
                                                'curso_grado_nivel.curso')->first();
        return $this->showOne($data);
    }

    /**
     */
    public function update(Request $request, AsignarCursoProfesor $asignarCursoProfesor)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AsignarCursoProfesor  $asignarCursoProfesor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {       
        $asignarCursoProfesor = AsignarCursoProfesor::where('id',$id)->firstOrFail();
        DB::beginTransaction();
            $secciones = AsignarCursoProfSec::where('asignar_curso_profesor_id',$asignarCursoProfesor->id)->get();
            foreach ($secciones as $key => $value) {
                $value->delete();
            }
            $asignarCursoProfesor->delete();
        DB::commit();
        return $this->showOne($asignarCursoProfesor);
    }
}
