<?php


namespace App\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller
{

    /**
     * ADD THE MIDDLEWARE
     * AuthController constructor.
     */
    public function __construct()
    {
       $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * LOGIN
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request){
        $this->validate($request,[
           'mail' => 'required|string',
           'password' => 'required|string'
        ]);

        $credentials = $request->only(['mail', 'password']);

        if(! $token  = Auth::attempt($credentials) ){
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        $user = Auth::user();

        return $this->respondWithToken($token, $user);
    }

    /**
     * LOGOUT
     * @return JsonResponse
     */
    public function logout(){
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * REFRESH TOKEN
     * @return JsonResponse
     */
    public function refresh(){
        return $this->respondWithToken(Auth::refresh(), Auth::user());
    }

    /**
     * GET USER INFOS
     * @return JsonResponse
     */
    public function me(){
        return response()->json(Auth::user());
    }


    /**
     * RETURN TOKEN
     * @param $token
     * @return JsonResponse
     */
    protected function respondWithToken($token, $user){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL()*60,
            'user' => $user
        ]);
    }

}
