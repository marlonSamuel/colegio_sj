<?php

namespace App\Http\Controllers\Nota;

use App\Asignacion;
use App\Nota;
use App\CicloPeriodoAcademico;
use App\GradoNivelEducativo;
use App\AsignarCursoProfesor;
use App\Inscripcion;
use App\Ciclo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class NotaController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:nota')->only(['store','update','destroy']);
    }

    public function index()
    {
        $nota = Nota::all();
        return $this->showAll($nota);
    }

    public function getAll($idAsignacionCurso, $idBimestre){

        $asignaciones = Asignacion::where([['asignar_curso_profesor_id',$idAsignacionCurso],['ciclo_periodo_academico_id',$idBimestre]])
            ->with('alumnos')->get();
 
        $alumnos = $this->getAlumnos($idAsignacionCurso);
        $data = $this->prepareData($alumnos,$asignaciones,$idAsignacionCurso,$idBimestre);

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
                if($inscripcion->seccion != null && $s->seccion_id == $inscripcion->seccion->seccion_id){
                    return $inscripcion;
                }
            }
        });

        return $inscripciones_filter;
    }

    public function prepareData($alumnos,$asignaciones,$asignar_curso_profesor_id,$ciclo_periodo_academico_id){
        $data = collect();
        
            foreach ($alumnos as $key => $value) {
            $total_cuestionario = 0;
            $total_tareas = 0;
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

      //boletas
    public function transormedData(Inscripcion $inscripcion)
    {
        $cursos = $inscripcion->grado_nivel_educativo()->with('cursos.curso')->get()->pluck('cursos')->collapse();

        $notas = Nota::where('inscripcion_id',$inscripcion->id)
                            ->with('periodo_academico.periodo_academico','asignar_curso_profesor.curso_grado_nivel.curso')
                            ->get();

        $group_by_bimestre = $notas->groupBy('periodo_academico.periodo_academico.nombre');

        //guardar todas las calificaciones
        $data = collect();

        $primer_bimestre_prom = null;
        $segundo_bimestre_prom = null;
        $tercer_bimestre_prom = null;
        $cuarto_bimestre_prom = null;
        $c_p = 0;
        $c_s = 0;
        $c_t = 0;
        $c_c = 0;

        $no = 1;
        foreach ($cursos as $curso) {
            $div_prom = 0;

            $cal = ([
                'no' => $no,
                'curso' => $curso->curso->nombre,
                'primer_bimestre' => null,
                'segundo_bimestre' => null,
                'tercer_bimestre' => null,
                'cuarto_bimestre' => null,
                'promedio' => null
            ]);

            foreach ($group_by_bimestre as $cal_key => $bim) {
                $bim_low = str_replace(' ','_',strtolower($cal_key));

                $nota_curso = $bim->where('asignar_curso_profesor.curso_grado_nivel.curso.nombre',$cal['curso'])->first();

                if(!is_null($nota_curso)){
                    $cal[$bim_low] = $nota_curso->nota;

                    if($bim_low == "primer_bimestre"){
                        $primer_bimestre_prom += $nota_curso->nota;
                        $c_p++;
                    }else if($bim_low == "segundo_bimestre"){
                        $segundo_bimestre_prom += $nota_curso->nota;
                        $c_s++;
                    }else if($bim_low == "tercer_bimestre"){
                        $tercer_bimestre_prom += $nota_curso->nota;
                        $c_t++;
                    }else{
                        $cuarto_bimestre_prom += $nota_curso->nota;
                        $c_c++;
                    }

                    $div_prom++;
                }
            }

            if ($div_prom > 0) {
                $cal['promedio'] = ($cal['primer_bimestre'] + $cal['segundo_bimestre'] + $cal['tercer_bimestre'] +$cal['cuarto_bimestre']) / $div_prom;
            }

            $data->push($cal);
            $no++;
        }

        if($c_p > 0)$primer_bimestre_prom = round($primer_bimestre_prom/$c_p,2);
        if($c_s > 0)$segundo_bimestre_prom = round($segundo_bimestre_prom/$c_s,2);
        if($c_t > 0)$tercer_bimestre_prom = round($tercer_bimestre_prom/$c_t,2);
        if($c_c > 0)$cuarto_bimestre_prom = round($cuarto_bimestre_prom/$c_c,2);

        $alumno = $inscripcion->alumno;
        $alumno->inscripcion_id = $inscripcion->id;
        $alumno->notas = $data;
        $alumno->primer_bimestre =  $primer_bimestre_prom;
        $alumno->segundo_bimestre = $segundo_bimestre_prom;
        $alumno->tercer_bimestre = $tercer_bimestre_prom;
        $alumno->cuarto_bimestre = $cuarto_bimestre_prom;

        return $alumno;
    }


    //obtener para enviar

    public function getOneNota($inscripcion_id)
    {
        $inscripcion = Inscripcion::find($inscripcion_id);
        $data = $this->transormedData($inscripcion);

        return $this->showQuery($data);
    }

    public function getAllNotas($ciclo_id, $grado_nivel_educativo_id)
    {
        $inscripciones = Inscripcion::where([['grado_nivel_educativo_id',$grado_nivel_educativo_id],['ciclo_id',$ciclo_id]])->get();
        $all_data = collect();

        foreach ($inscripciones as $inscripcion) {
            $data = $this->transormedData($inscripcion);
            $all_data->push($data);
        } 

        return $this->showQuery($all_data);
    }


    //obtener boleta de calificación
    public function boleta($inscripcion_id)
    {
        $inscripcion = Inscripcion::find($inscripcion_id);
        $data = $this->transormedData($inscripcion);
        $ciclo = Ciclo::find($inscripcion->ciclo_id);
        $grado = GradoNivelEducativo::where('id',$inscripcion->grado_nivel_educativo_id)->with('grado','nivelEducativo')->first();

        $all_data = collect([$data]);

        $pdf = \PDF::loadView('pdfs.boleta',['ciclo'=>$ciclo,'grado'=>$grado,'data'=>$all_data]);

        //$pdf->setPaper('legal', 'portrait');

        return $pdf->download('boleta.pdf');


        return $this->showQuery($data);
    }

        //obtener boleta de calificación
    public function boletas($ciclo_id, $grado_nivel_educativo_id)
    {
        $ciclo = Ciclo::find($ciclo_id);
        $grado = GradoNivelEducativo::where('id',$grado_nivel_educativo_id)->with('grado','nivelEducativo')->first();

        $inscripciones = Inscripcion::where([['grado_nivel_educativo_id',$grado_nivel_educativo_id],['ciclo_id',$ciclo_id]])->get();

        $all_data = collect();
        foreach ($inscripciones as $inscripcion) {
            $data = $this->transormedData($inscripcion);
            $all_data->push($data);
        }   

        //return $this->showQuery($all_data);

        $pdf = \PDF::loadView('pdfs.boleta',['ciclo'=>$ciclo,'grado'=>$grado,'data'=>$all_data]);

        $pdf->setPaper('legal', 'portrait');

        return $pdf->download('boleta.pdf');


        return $this->showQuery($data);
    }
}