<?php

namespace App\Http\Controllers\Inscripcion;

use App\AsignacionSeccion;
use App\GradSecNivEd;
use App\Inscripcion;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class AsignacionSeccionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:asignacionseccionindex');
    }
   
    public function index()
    {
        $asignaciones = AsignacionSeccion::all();
        return $this->showAll($asignaciones);
    }

    public function getAll($ciclo_id,$grado_nivel_educativo_id)
    {

        $inscripciones = AsignacionSeccion::with('inscripcion.alumno','seccion')
                                                ->get()
                                                ->where('inscripcion.ciclo_id',$ciclo_id)
                                                ->where('inscripcion.grado_nivel_educativo_id',$grado_nivel_educativo_id);


        return $this->showAll($inscripciones);                                        

    }


    //obtener inscripciones de alumnos sin asignación de sección
    public function getWithoutSection($ciclo_id,$grado_nivel_educativo_id)
    {

        $inscripciones = Inscripcion::with('seccion.seccion','alumno')
                                            ->get()
                                            ->where('ciclo_id',$ciclo_id)
                                            ->where('grado_nivel_educativo_id',$grado_nivel_educativo_id)
                                            ->where('seccion','=',null)->values();


        return $this->showAll($inscripciones);                                        

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
            'seccion_id' => 'required|integer',
            'inscripcion_id' => 'required|integer'
        ];

        $this->validate($request, $reglas);

        $data = $request->all();

        $asignacion_seccione = AsignacionSeccion::create($data);
        
        return $this->showOne($asignacion_seccione);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AsignacionSeccion $asignacion_seccione)
    {
        $reglas = [
            'seccion_id' => 'required|integer'
        ];

        $this->validate($request, $reglas);

        $asignacion_seccione->seccion_id = $request->seccion_id;

         if (!$asignacion_seccione->isDirty()) {
            return $this->errorResponse('Se debe cambiar a siguiente una sección diferente', 422);
        }

        $asignacion_seccione->save();
        return $this->showOne($asignacion_seccione);
    }

    public function destroy(){
        
    }

}
