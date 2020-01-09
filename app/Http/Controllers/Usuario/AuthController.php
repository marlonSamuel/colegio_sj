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
            'email'       => 'required|string|email',
            'password'    => 'required|string',
        ]);

        $credentials = request(['email', 'password']);

        $http = new Client();

        $response = $http->post('http://www.san_pablo.com/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '*',
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
        return response()->json([
            'user' => $user,
            'institucion' => $institucion
        ]);
    }
}
