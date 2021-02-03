<?php

namespace App\Http\Controllers\Serie;

use App\Serie;
use App\Asignacion;
use App\AlumnoSerie;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class SerieController extends ApiController
{
   public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:serie');
    }

    public function index()
    {
        $series = Serie::all();
        return $this->showAll($series);
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
            'asignacion_id'=> 'required|integer',
            'descripcion'=> 'required|string',
            'tipo_serie'=> 'required|string',
            'nota'=> 'required|numeric'
        ];
        
        $this->validate($request, $reglas);

        DB::beginTransaction();

        $data = $request->all();
        $serie = Serie::create($data);

        $asignacion = Asignacion::where('id',$serie->asignacion_id)->with('alumnos')->first();

        if(!is_null($asignacion)){
            foreach ($asignacion->alumnos as $a) {
                AlumnoSerie::create([
                    'asignacion_alumno_id' => $a->id,
                    'serie_id' => $serie->id
                ]);
            }
        }

        DB::commit();
        return $this->showOne($serie,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $serie = Serie::find($id);
        return $this->showOne($serie);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $serie = Serie::find($id);
        $reglas = [
            'descripcion'=> 'required|string',
            'tipo_serie'=> 'required|string',
            'nota'=> 'required|numeric'
        ];

        $this->validate($request, $reglas);

        $serie->descripcion = $request->descripcion;
        $serie->tipo_serie = $request->tipo_serie;
        $serie->nota = $request->nota;

         if (!$serie->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $serie->save();
        return $this->showOne($serie);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $serie = Serie::find($id);
        $serie->delete();
        return $this->showOne($serie,201);
    }
}
