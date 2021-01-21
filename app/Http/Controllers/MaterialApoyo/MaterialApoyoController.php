<?php

namespace App\Http\Controllers\MaterialApoyo;
use App\MaterialApoyo;
use App\AsignarCursoProfesor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class MaterialApoyoController extends ApiController
{
    public function __construct()
    {
        //parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $materiales = MaterialApoyo::all();
        return $this->showAll($materiales);
    }

    public function getAll($id){
        $materiales = MaterialApoyo::where('asignar_curso_profesor_id',$id)->get();
        return $this->showAll($materiales);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $reglas = [
            'asignar_curso_profesor_id' => 'required|integer',
            'descripcion' => 'required|string',
            'link' => 'required',
        ];

        $this->validate($request, $reglas);

        DB::beginTransaction();
        $data = $request->all();

        $material = MaterialApoyo::create($data);
        if (!$request->link) {
            if(!is_null($request->file) && $request->file !== "" && $request->file !== "null"){
                $folder = 'material_apoyo_'.$request->asignar_curso_profesor_id;
                $name = $material->id.'-'.$request->file->getClientOriginalName();              
                $material->adjunto = $request->file->storeAs($folder, $name);
                $material->save();
            }
        }else{
            $material->url = $request->url;
            $material->save();
        }
        
        DB::commit();

        return $this->showOne($material,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MaterialApoyo $material
     * @return \Illuminate\Http\Response
     */
    public function show(MaterialApoyo $material)
    {
        return $this->showOne($material);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MaterialApoyo  $material
     * @return \Illuminate\Http\Response
     */
    public function updateData(Request $request, $id)
    {
        $material = MaterialApoyo::find($id);

        $reglas = [
            'asignar_curso_profesor_id' => 'required|integer',
            'descripcion' => 'required|string',
            'link' => 'required',
        ];

        $this->validate($request, $reglas);

        DB::beginTransaction();

        $material->descripcion = $request->descripcion;
        $material->link = $request->link;
        $material->url = $request->url;
        if(!is_null($request->file) && $request->file !== "" && $request->file !== "null"){
            $folder = 'material_apoyo_'.$material->asignar_curso_profesor_id;
            $name = $material->id.'-'.$request->file->getClientOriginalName();
            
            if($request->file_name != $name){
                Storage::delete($material->adjunto);
                $material->adjunto = $request->file->storeAs($folder, $name);
            }
        }

        $material->save();

        DB::commit();

        return $this->showOne($material,201);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MaterialApoyo $material
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $material = MaterialApoyo::where('id',$id)->first();
        if (!$material->link) {
            Storage::delete($material->adjunto);
        }
        $material->delete();
        return $this->showOne($material,201);
    }
}