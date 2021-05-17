<?php

namespace App\Http\Controllers\Empleado;

use App\Empleado;
use App\Cargo;
use App\User;
use App\Rol;
use App\UsuarioEmpleado;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Carbon\Carbon;

class EmpleadoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:empleado')->except(['index']);
    }

    public function index()
    {
        $empleados = Empleado::with('cargo')->get();
        return $this->showAll($empleados);
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
            'cui' => 'required|:unique',
            'primer_nombre' => 'required|string',
            'primer_apellido' => 'required|string',
            'telefono' => 'required',
            'direccion' => 'required',
            'cargo_id' => 'required|integer'
        ];
        
        $this->validate($request, $reglas);

        DB::beginTransaction();

        $data = $request->all();
        $data['codigo'] = $this->getCorrelativo();
        $empleado = Empleado::create($data);

        $cargo = Cargo::find($empleado->cargo_id);

        if($cargo->nombre == 'profesor'){
            $rol = Rol::where('rol','profesor')->first();
            $user = new User;

            $user->codigo = $empleado->codigo;
            $user->email = $empleado->email;
            $user->password = bcrypt($empleado->codigo);
            $user->rol_id = $rol->id;
            $user->save();

            $usuario_empleado = new UsuarioEmpleado;
            $usuario_empleado->empleado_id = $empleado->id;
            $usuario_empleado->user_id = $user->id;
            $usuario_empleado->save();
        }
        DB::commit();

        return $this->showOne($empleado,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function show(Empleado $empleado)
    {
        return $this->showOne($empleado,201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Empleado $empleado)
    {
        $reglas = [
            'cui' => 'required|string|unique:empleados,cui,'.$request->id,
            'primer_nombre' => 'required|string',
            'primer_apellido' => 'required|string',
            'telefono' => 'required',
            'direccion' => 'required',
            'cargo_id' => 'required|integer'
        ];

        $this->validate($request, $reglas);

        $empleado->cui = $request->cui;
        $empleado->primer_nombre = $request->primer_nombre;
        $empleado->segundo_nombre = $request->segundo_nombre;
        $empleado->primer_apellido = $request->primer_apellido;
        $empleado->segundo_apellido = $request->segundo_apellido;
        $empleado->direccion = $request->direccion;
        $empleado->cargo_id = $request->cargo_id;
        $empleado->fecha_nac = $request->fecha_nac;

         if (!$empleado->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $empleado->save();
        return $this->showOne($empleado,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empleado $empleado)
    {
        DB::beginTransaction();
        $empleado->delete();
        $user = User::where('codigo',$empleado->codigo)->orWhere('email',$empleado->email)->first();

        if(!is_null($user)){
            $user->activo = false;
            $user->save();
        }
        DB::commit();
        return $this->showOne($empleado,201);
    }

    //obtener grados y cursos de profesor
    public function getCursos(Request $request)
    {
        
    }


    function getCorrelativo(){
        $year = Carbon::now()->year;
        $empleado = Empleado::latest()->first();
        $codigo = '1-'.$year.'-'.($empleado->id +1);
        return $codigo;
    }
}
