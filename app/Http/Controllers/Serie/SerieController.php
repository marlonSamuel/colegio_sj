<?php

namespace App\Http\Controllers\Serie;

use App\Serie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SerieController extends ApiController
{
   public function __construct()
    {
        parent::__construct();
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
            'nota'=> 'required|integer'
        ];
        
        $this->validate($request, $reglas);
        $data = $request->all();
        $serie = Serie::create($data);

        return $this->showOne($serie,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function show(serie $serie)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\serie  $serie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, serie $serie)
    {
        $reglas = [
            'descripcion'=> 'required|string',
            'tipo_serie'=> 'required|string',
            'nota'=> 'required|integer'
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
    public function destroy(serie $serie)
    {
        $serie->delete();
        return $this->showOne($serie);
    }
}
