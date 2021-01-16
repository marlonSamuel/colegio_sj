<?php

namespace App\Http\Controllers\AsignacionAlumno;

use App\Alumno;
use App\Inscripcion;
use App\Asignacion;
use App\AsignacionAlumno;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AsignacionAlumnoController extends ApiController
{
    public function __construct()
    {
        //parent::__construct();
        //$this->middleware('scope:niveleducativo')->except(['index']);
    }

    public function index()
    {
        $curso_niveles = AsignacionAlumno::all();
        return $this->showAll($curso_niveles);
    }
    //tareas o examenes pendientes de resolver
    public function getAsignaciones($idAlumno,$ciclo_id)
    {
        $asignaciones = AsignacionAlumno::where([['inscripcion_id', $idAlumno]])
                        ->with('asignacion',
                        'asignacion.asignar_curso_profesor.curso_grado_nivel.curso',
                        'inscripcion')->get();
        $asignaciones = $asignaciones->where('inscripcion.ciclo_id',$ciclo_id);

        return $this->showAll($asignaciones);
    }
    public function getCursos($idAlumno,$ciclo_id)
    {
        $curso_niveles = Inscripcion::where([['id', $idAlumno],['ciclo_id',$ciclo_id]])
                        ->with('grado_nivel_educativo',
                        'grado_nivel_educativo.cursos',
                        'grado_nivel_educativo.cursos.curso',
                        'ciclo')->get();
        $curso_niveles = $this->prepareData($curso_niveles);
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
    public function cursoGradoNivel()
    {
        $curso_niveles = CursoGradNivEd::with('grado_nivel_educativo', 'grado_nivel_educativo.nivelEducativo', 'grado_nivel_educativo.grado', 'curso')->get();

        $data = $this->prepareData($curso_niveles);
        return $this->showQuery($data);
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
            $info = collect();
            foreach ($value->grado_nivel_educativo->cursos as $key2 => $value2) {
                $curso = collect([
                'ciclo' => $value->ciclo->ciclo,
                'nombre' => $value2->curso->nombre 
            ]);
                $info->push($curso);
            }
            
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
            'curso_grad_niv_edu_id' => 'required|integer'
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
    public function show($id)
    {
        //dd("llego".$id);
        $asignacion_alumno = AsignacionAlumno::where('id',$id)->firstOrFail();
        return $this->showOne($asignacion_alumno);
    }

    /**
     */
    public function update(Request $request, AsignarCursoProfesor $asignarCursoProfesor)
    {
        
    }
  /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AsignacionAlumno  $asignacion
     * @return \Illuminate\Http\Response
     */
    public function updateData(Request $request,$id)
    {
        $asignacion = AsignacionAlumno::find($id);

        $reglas = [
            'asignacion_id' => 'required',
            'fecha_entrega' => 'required',
            'entrega_tarde' => 'required',
            'entregado' => 'required'
        ];

        $this->validate($request, $reglas);

        DB::beginTransaction();
        $asignacion->fecha_entrega = $request->fecha_entrega;
        $asignacion->entrega_tarde = $request->entrega_tarde;
        $asignacion->entregado = $request->entregado;
        if ($request->adjunto == "" || $request->adjunto =="null" || $request->adjunto == null) {
           if(!is_null($request->file) && $request->file !== "" && $request->file !== "null"){
            $folder = 'entrega_asignacion_'.$request->asignacion_id;
            $name = $asignacion->id.'-'.$request->file->getClientOriginalName();

            $asignacion->adjunto = $request->file->storeAs($folder, $name);
            $asignacion->save();
        }
        }else{
          if(!is_null($request->file) && $request->file !== "" && $request->file !== null){

            $folder = 'entrega_asignacion_'.$asignacion->asignacion_id;
            $name = $asignacion->id.'-'.$request->file->getClientOriginalName();

            if($request->file_name != $name){
                Storage::delete($asignacion->adjunto);
                $asignacion->adjunto = $request->file->storeAs($folder, $name);
            }
        }  
        }

        $asignacion->save();
        DB::commit();

        return $this->showOne($asignacion,201);
    }


    //asignar nota
     /**
     */
    public function asignarNota(Request $request, $id)
    {
        $asignar_nota = AsignacionAlumno::find($id);
         $reglas = [
            'id' => 'required|integer',
            'nota' => 'required|numeric'
        ];

        $this->validate($request, $reglas);

        $asignar_nota->nota = $request->nota;
        $asignar_nota->observaciones = $request->observaciones;
        $asignar_nota->calificado = true;

        if (!$asignar_nota->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $asignar_nota->save();

        return $this->showOne($asignar_nota,201);
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