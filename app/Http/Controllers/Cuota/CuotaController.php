<?php

namespace App\Http\Controllers\Cuota;

use App\ConceptoPago;
use App\Cuota;
use App\Ciclo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
class CuotaController extends ApiController
{
    public function __construct()
    {
       parent::__construct();
       $this->middleware('scope:asignarcuota')->except(['index']);
    }

    public function index(ConceptoPago $concepto_pago){
        $cuota = $concepto_pago->cuotas()->with('concepto_pago','grado_nivel_educativo','grado_nivel_educativo.grado','grado_nivel_educativo.nivelEducativo')->get();
        return $this->showAll($cuota);
    }


    public function store(Request $request)
    {
        $reglas = [
            'forms' => 'required'
        ];

        $this->validate($request, $reglas);

        foreach ($request->forms as $key => $form) {
            $cuota = Cuota::create($form); 
        }
              
        return $this->showOne($cuota,201);
    }

//iniciar ciclo nuevo con cuotas de ciclo anterior
    public function startCuotaCiclo(Request $request)
    {
        $reglas = [
            'ciclo_id' => 'required'
        ];
        
        $this->validate($request, $reglas);
        
        $ciclo_actual = Ciclo::where('id',$request->ciclo_id)->firstOrFail();
        $ciclo_anterior = Ciclo::where('ciclo', $ciclo_actual->ciclo - 1)->firstOrFail();
        if ($ciclo_anterior == null) {           
            return $this->errorResponse('No existe un ciclo anterior', 422);
        }

        $cuotas = Cuota::where('ciclo_id',$ciclo_anterior->id)->get();
        $cuotas_actual = Cuota::where('ciclo_id',$request->ciclo_id)->get();
        if (count($cuotas) > 0 && count($cuotas_actual) == 0) {
            foreach ($cuotas as $key => $value) {
                $cuota = Cuota::create([
                    'cuota'=> $value->cuota,
                    'grado_nivel_educativo_id'=>$value->grado_nivel_educativo_id,
                    'concepto_pago_id'=>$value->concepto_pago_id,
                    'ciclo_id'=> $request->ciclo_id
                ]); 
            } 
            return $this->showOne($cuota,201);         
        }elseif(count($cuotas_actual) > 0){
            return $this->errorResponse('Ciclo ya tiene cuotas configuradas', 422);

        }else{
            return $this->errorResponse('No existe cuotas en ciclo anterior', 422);
         }         
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
    public function update(Request $request, Cuota $cuota)
    {
        $reglas = [
            'cuota' => 'required',
        ];

        $this->validate($request, $reglas);

        $cuota->cuota = $request->cuota;

        if (!$cuota->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $cuota->save();
        
        return $this->showOne($cuota);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
