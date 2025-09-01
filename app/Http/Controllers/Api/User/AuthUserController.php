<?php

namespace App\Http\Controllers\Api\User;

use App\Events\VerifyEmailEvent;
use App\Helper\APIResponse;
use App\Helper\OTPVerifyEmail;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\VerifyEmailRequest;
use App\Http\Resources\User\UserResponse;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthUserController extends Controller
{
    use APIResponse, OTPVerifyEmail;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', "register"]]);
    }
//    Register New User
    public function register(RegisterRequest $request){


        $user=User::create($request->all());

        $token=auth()->login($user);

        $user->token=$token;
        event(new VerifyEmailEvent($this->otp($user->email)));
        return $this->success(new UserResponse($user), __("auth.verify"));
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return $this->error("Failed",401,__("http-statuses.401"));
        }

        // return $this->respondWithToken($token);
        $data=auth()->user();
        $data->token=$token;
        return $this->success(new UserResponse($data), __("auth.login"));
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user=auth()->user();
        $user->token=JWTAuth::getToken()->get();
        return $this->success(new UserResponse($user), "OK");
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return $this->success(null, __("auth.logout"));
    }

    /**
     * Verification Email
     */

    public function verify_email(VerifyEmailRequest $request){

        if (Otp::where("code", $request->code)->where("email", $request->email)->where("expired_at", ">", now())->exists()) {

            $otp=Otp::where("code", $request->code)
                ->where("email", $request->email)
                ->where("expired_at", ">", now())->first();
            $user= $otp->user;

            $user->email_verified_at=now();
            $user->save();

            $user->token= JWTAuth::getToken()->get();

            return $this->success(new UserResponse($user), __("auth.verifs"), 200);

        }else{

            event(new VerifyEmailEvent($this->otp($request->email)));

            return $this->error(__("auth.resent"), 400, __("auth.expire"));

        }

    }

    /**
     * Resent Verification Code
     */

    public function resent_code(){

        $user=auth()->user();
        $otp= $this->otp($user->email);
        event(new VerifyEmailEvent($otp));

        return $this->success(null, __("auth.resent"), 200);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}
