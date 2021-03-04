<?php

namespace App\Http\Controllers\Usuario;

use App\User;
use App\UsuarioEmpleado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:usuario')->except(['changePassword']);
    }

    public function index()
    {
        $usuarios = User::where('activo',true)->with('rol','empleado.empleado','alumno.alumno','representante.apoderado')->get();
        
        return $this->showAll($usuarios);
    }

    public function store(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|integer',
            'codigo'     => 'required|string',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'rol_id' => 'required'
        ]);

        DB::beginTransaction();
        $empleado = Empleado::find($request->empleado_id);

        $user = new User([
            'codigo'     => $empleado->codigo,
            'email'    => $empleado->email,
            'rol_id' => $request->rol_id,
            'password' => bcrypt($request->password),
        ]);


        $user->save();

        $usuario_empleado = new UsuarioEmpleado([
                'empleado_id' => $request->empleado_id,
                'user_id' => $user->id
            ]);

        $usuario_empleado->save();

        DB::commit();
        
        return $this->showOne($user);
    }

    /**
     */
    public function show(User $usuario)
    {
        return $this->showOne($usuario);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $usuario)
    {
        $reglas = [
            'rol_id' => 'required|integer',
            'empleado_id' => 'required|integer'
        ];

        $this->validate($request, $reglas);

        DB::beginTransaction();
        $usuario->rol_id = $request->rol_id;
        $usuario->save();

        $usuario_empleado = UsuarioEmpleado::find($usuario->id);
        $usuario_empleado->empleado_id = $request->empleado_id;

        $usuario_empleado->save();
        DB::commit();

        return $this->showOne($usuario);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $usuario)
    {
        $usuario->delete();
        return $this->showOne($usuario,201);
    }

    public function changePassword(Request $request)
    {
        $user = User::find($request->user_id);

        if (Hash::check($request->new_password, $user->password)) {
            return $this->errorResponse('la contraseña actual no puede ser igual a la nueva contraseña',422);
        }

        if (Hash::check($request->old_password, $user->password)) { 
            $user->password = bcrypt($request->new_password);
            $user->save();
        } else {
            return $this->errorResponse('la contraseña anterior es incorrecta',422);
        }


        return $this->showOne($user,'201','update');
    }

    public function resetPassword(Request $request){
        $reglas = [
            'id' => 'required|integer',
            'password' => 'required'
        ];
        $this->validate($request, $reglas);
        $user = User::find($request->id);
        $user->password = bcrypt($request->password);
        $user->save();
        return $this->showOne($user,'201','update');
    }
}
