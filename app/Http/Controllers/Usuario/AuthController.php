<?php

namespace App\Http\Controllers\Usuario;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;

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

        $response = $http->post('http://www.colegio.com/oauth/token', [
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

       /* if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Usuario o contraseña incorrectos'], 401);
        }

        $user = $request->user();

        $tokenResult = $user->createToken('token_colegio');

        $token = $tokenResult->token;

        $token->save();


        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'usuario' => $request->user(),
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at)
                    ->toDateTimeString(),
        ]);*/
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
        

        return response()->json(User::where('id',$id)->with('rol')->first());
    }
}
