<?php

namespace App\Http\Controllers\Serie;

use App\Pregunta;
use App\Respuesta;
use App\Serie;
use App\AlumnoPregunta;
use App\AlumnoRespuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ApiController;

class PreguntaController extends ApiController
{
   public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:pregunta');
    }

    public function index()
    {
        $preguntas = Pregunta::all();
        return $this->showAll($preguntas);
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
            'serie_id'=> 'required|integer',
            'pregunta'=> 'required|string',
            'nota'=> 'required|numeric',
            'respuestas' => 'required'
        ];
        
        $this->validate($request, $reglas);

        DB::beginTransaction();

        $data = $request->all();

        $pregunta = Pregunta::create($data);

        if(!is_null($request->file) && $request->file !== "" && $request->file !== "null"){
            $folder = 'asignaciones/preguntas_'.$request->serie_id;
            $name = $pregunta->id.'-'.$request->file->getClientOriginalName();

            $pregunta->adjunto = $request->file->storeAs($folder, $name);
            $pregunta->save();
        }

        $serie = Serie::find($request->serie_id);

        $request->respuestas = json_decode($request->respuestas);

        if(count($request->respuestas) == 0){
            return $this->errorResponse('pregunta debe tener al menos una respuesta',422);
        }

        if($serie->tipo_serie == "FV" && count($request->respuestas) > 2){
            return $this->errorResponse('serie de falso y verdadero no puede contener mas de dos posibles respuestas',422);
        }

        if($serie->tipo_serie == "PD" && count($request->respuestas) > 1){
            return $this->errorResponse('serie de preguntas directas no puede contener mas de una posible respuesta',422);
        }

        //registrando preguntas a alumnos
        $alumno_series = $serie->alumno_series;

        foreach ($alumno_series as $as) {
            AlumnoPregunta::create([
                'alumno_serie_id' => $as->id,
                'pregunta_id' => $pregunta->id
            ]);
        }

        //ingresando respuestas
        foreach ($request->respuestas as $res) {
            $respuesta = Respuesta::create([
                            "pregunta_id" => $pregunta->id,
                            "respuesta" => $res->respuesta,
                            "correcta" => $res->correcta
                        ]);

            $alumno_preguntas = $pregunta->alumno_preguntas;

            //registrando respuestas
            foreach ($alumno_preguntas as $ap) {
                $re = $serie->tipo_serie == "RM" ? $res->respuesta : null;
                AlumnoRespuesta::create([
                    'alumno_pregunta_id' => $ap->id,
                    'respuesta_id' => $respuesta->id,
                    'respuesta' => $re
                ]);
            }
        }

        DB::commit();

        return $this->showOne($pregunta,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\pregunta  $pregunta
     * @return \Illuminate\Http\Response
     */
    public function show(Pregunta $pregunta)
    {
        $pregunta = Pregunta::where('id',$pregunta->id)->with('respuestas')->first();
        return $this->showOne($pregunta);
    }

    //actualizar por medio del metodo post por el formdata del archivo
    public function updateData(Request $request, $id)
    {
        $pregunta = Pregunta::find($id);

        $reglas = [
            'serie_id'=> 'required|integer',
            'pregunta'=> 'required|string',
            'nota'=> 'required|numeric',
            'respuestas' => 'required'
        ];
        
        $this->validate($request, $reglas);

        DB::beginTransaction();

        $pregunta->pregunta = $request->pregunta;
        $pregunta->nota = $request->nota;

        if(!is_null($request->file) && $request->file !== "" && $request->file !== "null"){
            $folder = 'asignaciones/preguntas_'.$pregunta->serie_id;
            $name = $pregunta->id.'-'.$request->file->getClientOriginalName();
            
            if($request->file_name != $name){
                Storage::delete($pregunta->adjunto);
                $pregunta->adjunto = $request->file->storeAs($folder, $name);
            }
        }

        $pregunta->respuestas()->delete();

        $serie = Serie::find($request->serie_id);
        $request->respuestas = json_decode($request->respuestas);

        if(count($request->respuestas) == 0){
            return $this->errorResponse('pregunta debe tener al menos una respuesta',422);
        }

        if($serie->tipo_serie == "FV" && count($request->respuestas) > 2){
            return $this->errorResponse('serie de falso y verdadero no puede contener mas de dos posibles respuestas',422);
        }

        if($serie->tipo_serie == "PD" && count($request->respuestas) > 1){
            return $this->errorResponse('serie de preguntas directas no puede contener mas de una posible respuesta',422);
        }

        //ingrando respuestas
        foreach ($request->respuestas as $res) {
            $respuesta = Respuesta::create([
                            "pregunta_id" => $pregunta->id,
                            "respuesta" => $res->respuesta,
                            "correcta" => $res->correcta
                        ]);

            $alumno_preguntas = $pregunta->alumno_preguntas;

            //registrando respuestas
            foreach ($alumno_preguntas as $ap) {
                AlumnoRespuesta::create([
                    'alumno_pregunta_id' => $ap->id,
                    'respuesta_id' => $respuesta->id
                ]);
            }
        }

        $pregunta->save();

        DB::commit();
        return $this->showOne($pregunta);
    }

    /**
     */
    public function update(Request $request, Pregunta $pregunta)
    {
        $reglas = [
            'serie_id'=> 'required|integer',
            'pregunta'=> 'required|string',
            'nota'=> 'required|numeric',
            'respuestas' => 'required'
        ];
        
        $this->validate($request, $reglas);

        DB::beginTransaction();

        $pregunta->pregunta = $request->pregunta;
        $pregunta->nota = $request->nota;

        if(!is_null($request->file) && $request->file !== "" && $request->file !== "null"){
            $folder = 'asignaciones/preguntas_'.$pregunta->serie_id;
            $name = $pregunta->id.'-'.$request->file->getClientOriginalName();
            
            if($request->file_name != $name){
                Storage::delete($pregunta->adjunto);
                $pregunta->adjunto = $request->file->storeAs($folder, $name);
            }
        }

        $pregunta->respuestas()->delete();

        $serie = Serie::find($request->serie_id);

        if(count($request->respuestas) == 0){
            return $this->errorResponse('pregunta debe tener al menos una respuesta',422);
        }

        if($serie->tipo_serie == "FV" && count($request->respuestas) > 2){
            return $this->errorResponse('serie de falso y verdadero no puede contener mas de dos posibles respuestas',422);
        }

        if($serie->tipo_serie == "PD" && count($request->respuestas) > 1){
            return $this->errorResponse('serie de preguntas directas no puede contener mas de una posible respuesta',422);
        }

        //ingrando respuestas
        foreach ($request->respuestas as $res) {
            $respuesta = Respuesta::create([
                            "pregunta_id" => $pregunta->id,
                            "respuesta" => $res['respuesta'],
                            "correcta" => $res['correcta']
                        ]);

            $alumno_preguntas = $pregunta->alumno_preguntas;

            //registrando respuestas
            foreach ($alumno_preguntas as $ap) {
                AlumnoRespuesta::create([
                    'alumno_pregunta_id' => $ap->id,
                    'respuesta_id' => $respuesta->id
                ]);
            }
        }

        $pregunta->save();

        DB::commit();
        return $this->showOne($pregunta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\pregunta  $pregunta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pregunta $pregunta)
    {
        Storage::delete($pregunta->adjunto);
        $pregunta->delete();
        return $this->showOne($pregunta,201);
    }
}