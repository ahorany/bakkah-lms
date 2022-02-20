<?php

namespace App\Http\Controllers\Api;

use App\Traits\Api\ApiResponseTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponseTrait;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api.auth:api', ['except' => ['login','sign']]);
    }


    public function sign(Request $request){
        $validator = Validator::make($request->all(),[
            'email'    => "required|email|max:255|unique:users",
            'name'     => "required|string|max:255",
            'password' => "required|min:8",
        ]);

        if($validator->fails()){
            return $this->returnValidationErrorResponse($validator,401);
        }


        $credentials = $request->only('email', 'password');

         User::create([
             'name' => $request->name,
             'email' => $request->email,
             'password' => bcrypt($request->password),
         ]);

        if ($token = $this->guard()->attempt($credentials)) {
            $user = $this->guard()->user();
            $user->token_details = $this->respondWithToken($token);
            return $this->returnDataResponse($user,'data','Successful login!');
        }

        return $this->returnErrorResponse(401,['error' => 'Unauthorized']);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator =Validator::make($request->all(),[
            'email'    => "required|email|exists:users,email",
            'password' => "required",
        ]);

        if($validator->fails()){
            return $this->returnValidationErrorResponse($validator,401);
        }

        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            $user = $this->guard()->user();
            $user->token_details = $this->respondWithToken($token);
            return $this->returnDataResponse($user,'data','Successful login!');
        }

        return $this->returnErrorResponse(401,['error' => 'Unauthorized']);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->returnDataResponse($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();
        return $this->returnSuccessfulResponse('Successfully logged out');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token_details = $this->respondWithToken($this->guard()->refresh());
         return $this->returnDataResponse($token_details,'data','Successful refresh token!');
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     * @return array
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ];
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard('api');
    }
}
