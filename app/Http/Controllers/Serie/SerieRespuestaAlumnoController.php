<?php

namespace App\Http\Controllers\Serie;

use App\AlumnoSerie;
use App\AlumnoPregunta;
use App\AlumnoRespuesta;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class SerieRespuestaAlumnoController extends ApiController
{
   public function __construct()
    {
        parent::__construct();
         $this->middleware('scope:cuestionario');
    }

    public function index()
    {
        $alumno_serie = AlumnoSerie::with('serie','preguntas.pregunta','pregunta.respuestas.respuesta')->get();
        $this->showAll($alumno_serie);
    }


    /**
     */
    public function store(Request $request)
    {
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AlumnoSerie $serie_respuesta_alumno)
    {

        $reglas = [
            'id' => 'required|integer',
            'nota' => 'required'
        ];

        $this->validate($request, $reglas);

        DB::beginTransaction();

        $serie_respuesta_alumno->nota = $request->nota;
        $serie_respuesta_alumno->respondida = true;
        $serie_respuesta_alumno->save();

        foreach ($request->preguntas as $p) {
            $alumno_pregunta = AlumnoPregunta::find($p['id']);
            $alumno_pregunta->nota = $p['nota'];
            $alumno_pregunta->save();

            foreach ($p['respuestas'] as $r) {
                $alumno_respuesta = AlumnoRespuesta::find($r['id']);
                $alumno_respuesta->nota = $r['nota'];
                $alumno_respuesta->correcto = $r['correcto'];
                $alumno_respuesta->respuesta = $r['respuesta'];
                $alumno_respuesta->correcto_alumno = $r['correcto_alumno'];

                $alumno_respuesta->save();
            }
        }

        DB::commit();
        return $this->showOne($serie_respuesta_alumno,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
