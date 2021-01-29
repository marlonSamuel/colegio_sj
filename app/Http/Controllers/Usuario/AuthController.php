<?php

namespace App\Http\Controllers\Usuario;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Institucion;

class AuthController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:api')->except(['login']);
    }

	//iniciar session
    public function login(Request $request)
    {
        $request->validate([
            'email'       => 'required|string',
            'password'    => 'required|string',
        ]);

        $credentials = request(['email', 'password']);

        $user = User::where('email',$request->email)->orWhere('codigo',$request->email)->first();

        if(!is_null($user)){
            $scopes = $this->getAllScopes($user);
        }else{
            return response()->json(['error' => "usuario incorrecto", 'code' => 401], 401);
        }

        $http = new Client(
            [
                'verify' => false
            ]
        );

        $response = $http->post(config('services.passport.url').'oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => $scopes,
            ],
        ]);

        return json_decode((string) $response->getBody(), true);

    }

    //cerrar session
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json(['message' => 
            'saliendo...']);
    }

    //obtener usuario logueado
    public function user(Request $request)
    {
        $id = $request->user()->id;
        $user = User::where('id',$id)->with('rol')->first();
        $institucion = Institucion::with('municipio.departamento')->first();

        if(!is_null($user->empleado)){
            $user->user_info = $user->empleado->empleado;
        }

        if(!is_null($user->alumno)){
            $user->user_info = $user->alumno->alumno;
        }

        if(!is_null($user->representante)){
            $user->user_info = $user->representante->apoderado;
        }

        return response()->json([
            'user' => $user,
            'institucion' => $institucion
        ]);
    }

    public function getAllScopes(User $user)
    {
        $scopes = [];
        $menus = $user->rol()->with('menus')->get()->pluck('menus')->collapse();

        foreach ($menus as $menu) {
            $name = strtolower($menu->name);
            array_push($scopes, $name);
        }

        return $scopes;
    }
}
