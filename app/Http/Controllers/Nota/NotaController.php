<?php

namespace App\Http\Controllers\Nota;

use App\Asignacion;
use App\Nota;
use App\CicloPeriodoAcademico;
use App\AsignarCursoProfesor;
use App\Inscripcion;
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

        $asignaciones = Asignacion::where([['asignar_curso_profesor_id',$idAsignacionCurso],['ciclo_periodo_academico_id',$idBimestre]])
            ->with('alumnos')->get();
            $asignaciones->groupBy('alumnos.asignacion_alumnos.inscripcion_id');
        $alumnos = $this->getAlumnos($idAsignacionCurso);
        $data = $this->prepareData($alumnos,$asignaciones);
        //$notas = Nota::where([['asignar_curso_profesor_id',$idAsignacionCurso],['ciclo_periodo_academico_id',$idBimestre]])->get();
        return $this->showQuery($data);
    }
    public function getPeriodoAcademico($idCiclo){
        $periodos = CicloPeriodoAcademico::where('ciclo_id',$idCiclo)->with('periodo_academico')->get();
        return $this->showAll($periodos);
    }

    public function getAlumnos($id){
        $profesor_curso = AsignarCursoProfesor::where('id',$id)->with('curso_grado_nivel')->first();
        $secciones = $profesor_curso->secciones;

        $inscripciones = Inscripcion::where([['grado_nivel_educativo_id',$profesor_curso->curso_grado_nivel->grado_nivel_educativo_id],
                                             ['ciclo_id',$profesor_curso->ciclo_id]])
                                            ->with('seccion','alumno')
                                            ->get();

        $inscripciones_filter = $inscripciones->filter(function ($inscripcion) use($secciones) {
            foreach ($secciones as $s) {
                if($s->seccion_id == $inscripcion->seccion->seccion_id){
                    return $inscripcion;
                }
            }
        });

        return $inscripciones_filter;
    }

    public function prepareData($alumnos,$asignaciones){
        $data = collect();
        if (count($asignaciones) > 0) {
            foreach ($alumnos as $key => $value) {
            $total_cuestionario = 0;
            $total_tareas = 0;
            $asignar_curso_profesor_id = $asignaciones[0]->asignar_curso_profesor_id;
            $ciclo_periodo_academico_id = $asignaciones[0]->ciclo_periodo_academico_id;
            $cuestionarios = $asignaciones->where('cuestionario',true);
            $tareas = $asignaciones->where('cuestionario',false);
            foreach ($cuestionarios as $key2=>$value2) {
                $total_cuestionario += $value2->alumnos->where('inscripcion_id',$value->id)->sum('nota');
            }
            foreach ($tareas as $key3=>$value3) {
                $total_tareas += $value3->alumnos->where('inscripcion_id',$value->id)->sum('nota');
            }
            $nota = Nota::where([['asignar_curso_profesor_id',$asignar_curso_profesor_id],['ciclo_periodo_academico_id',$ciclo_periodo_academico_id],['inscripcion_id',$value->id]])->first();
            $info = collect([
                'inscripcion_id'=> $value->id,
                'nombre'=>$value->alumno->primer_nombre.' '.$value->alumno->segundo_nombre.' '.$value->alumno->tercer_nombre.' '.$value->alumno->primer_apellido.' '.$value->alumno->segundo_apellido,
                'total_cuestionario'=>$total_cuestionario,
                'total_tareas'=>$total_tareas,
                'sub_total'=>$total_tareas+$total_cuestionario,
                'asignar_curso_profesor_id'=>$asignar_curso_profesor_id,
                'ciclo_periodo_academico_id'=>$ciclo_periodo_academico_id,
                'id'=>$nota==null?null:$nota->id,
                'nota'=>$nota==null?null:$nota->nota
                ]
            );
            $data->push($info);          
        }
        }
            
        
        return $data;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reglas = [
            'inscripcion_id' => 'required|integer',
            'asignar_curso_profesor_id' => 'required|integer',
            'ciclo_periodo_academico_id' => 'required|integer',
            'nota'=>'required'
        ];

        $this->validate($request, $reglas);       
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
        $nota->nota = $request->nota;
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