<?php

namespace App\Http\Controllers\Asignacion;

use App\Asignacion;
use App\AsignacionAlumno;
use App\Inscripcion;
use App\AsignarCursoProfesor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class AsignacionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:asignacionindex');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $asignaciones = Asignacion::all();
        return $this->showAll($asignaciones);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $reglas = [
            'asignar_curso_profesor_id' => 'required|integer',
            'ciclo_periodo_academico_id' => 'required|integer',
            'cuestionario' => 'required',
            'descripcion' => 'required|string',
            'titulo' => 'required|string',
            'fecha_habilitacion' => 'required|date',
            'fecha_entrega' => 'required|date',
            'nota'=>'required',
            'entrega_tarde' => 'required'
        ];

        $this->validate($request, $reglas);

        DB::beginTransaction();
        $data = $request->all();

        $asignacion = Asignacion::create($data);

        if(!is_null($request->file) && $request->file !== "" && $request->file !== "null"){
            $folder = 'asignaciones_'.$request->asignar_curso_profesor_id;
            $name = $asignacion->id.'-'.$request->file->getClientOriginalName();

            $asignacion->adjunto = $request->file->storeAs($folder, $name);
            $asignacion->save();
        }

        $profesor_curso = AsignarCursoProfesor::where('id',$request->asignar_curso_profesor_id)->with('curso_grado_nivel')->first();
        $secciones = $profesor_curso->secciones;

        $inscripciones = Inscripcion::where([['grado_nivel_educativo_id',$profesor_curso->curso_grado_nivel->grado_nivel_educativo_id],
                                             ['ciclo_id',$profesor_curso->ciclo_id]])
                                            ->with('seccion')
                                            ->get();

        if($profesor_curso->jornada !== "A"){
            $inscripciones = $inscripciones->where('jornada',$profesor_curso->jornada);
        }

        $inscripciones_filter = $inscripciones->filter(function ($inscripcion) use($secciones) {
            foreach ($secciones as $s) {
                if($inscripcion->seccion != null && $s->seccion_id == $inscripcion->seccion->seccion_id){
                    return $inscripcion;
                }
            }
        });

        if(count($inscripciones_filter) == 0){
            return $this->errorResponse('no se puede agregar asignación, no existe ningun alumno inscrito a este curso y grado',422);
        }

        //asignar tareas a alumnos en el grado y secciones asignados
        foreach ($inscripciones_filter as $i) {
            AsignacionAlumno::create([
                'inscripcion_id'=>$i->id,
                'asignacion_id'=>$asignacion->id,
                'nota'=>0
            ]);
        }

        DB::commit();

        return $this->showOne($asignacion,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Asignacion  $asignacion
     * @return \Illuminate\Http\Response
     */
    public function show(Asignacion $asignacione)
    {
        $asignacione = Asignacion::where('id',$asignacione->id)->with('series.preguntas.respuestas')->first();
        return $this->showOne($asignacione);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Asignacion  $asignacion
     * @return \Illuminate\Http\Response
     */
    public function updateData(Request $request, $id)
    {
        $asignacione = Asignacion::find($id);

        $reglas = [
            'ciclo_periodo_academico_id' => 'required|integer',
            'cuestionario' => 'required',
            'descripcion' => 'required|string',
            'titulo' => 'required|string',
            'fecha_habilitacion' => 'required|date',
            'fecha_entrega' => 'required|date',
            'nota'=>'required',
            'entrega_tarde' => 'required'
        ];

        $this->validate($request, $reglas);

        DB::beginTransaction();

        $asignacione->cuestionario = $request->cuestionario;
        $asignacione->descripcion = $request->descripcion;
        $asignacione->titulo = $request->titulo;
        $asignacione->fecha_habilitacion = $request->fecha_habilitacion;
        $asignacione->fecha_entrega = $request->fecha_entrega;
        $asignacione->nota = $request->nota;
        $asignacione->entrega_tarde = $request->entrega_tarde;

        if(!is_null($request->file) && $request->file !== "" && $request->file !== "null"){
            $folder = 'asignaciones_'.$asignacione->asignar_curso_profesor_id;
            $name = $asignacione->id.'-'.$request->file->getClientOriginalName();
            
            if($request->file_name != $name){
                Storage::delete($asignacione->adjunto);
                $asignacione->adjunto = $request->file->storeAs($folder, $name);
            }
        }

        $asignacione->save();

        DB::commit();

        return $this->showOne($asignacione,201);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Asignacion  $asignacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Asignacion $asignacione)
    {
        Storage::delete($asignacione->adjunto);
        $asignacione->delete();
        return $this->showOne($asignacione,201);
    }
}
