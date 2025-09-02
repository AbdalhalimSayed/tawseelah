<?php

namespace App\Http\Controllers\Api\Driver;

use App\Events\VerifyEmailEvent;
use App\Helper\APIResponse;
use App\Helper\OTPVerifyEmail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\RegisterRequest;
use App\Http\Requests\Driver\VerifyEmailRequest;
use App\Http\Resources\Driver\DriverResponse;
use App\Http\Resources\Driver\ProfileResponse;
use App\Models\Driver;
use App\Models\Otp;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthDriverController extends Controller
{
    use APIResponse, OTPVerifyEmail;
    public function __construct(){
        $this->middleware("auth:driver", ["except" => ["login", "register"]]);
    }

//    Register Driver
    public function Register(RegisterRequest $request){

        $driver=Driver::create($request->all());
        $token=auth()->guard("driver")->login($driver);
        $driver->token=$token;
        event(new VerifyEmailEvent($this->otp($driver->email)));
        return $this->success( new DriverResponse($driver), __("auth.verify"));
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->guard("driver")->attempt($credentials)) {
            return $this->error("Failed",401,__("http-statuses.401"));
        }

        // return $this->respondWithToken($token);
        $data=auth()->guard("driver")->user();
        $data->token=$token;
        return $this->success(new DriverResponse($data), __("auth.login"));
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $driver = auth()->guard()->user();
        $driver->token = JWTAuth::getToken()->get();
        return $this->success(new ProfileResponse($driver), "OK");
    }
    /**
     * Verification Email
     */

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->guard("driver")->logout();

        return $this->success(null, __("auth.logout"));
    }


    public function verify_email(VerifyEmailRequest $request){

        if (Otp::where("code", $request->code)->where("email", $request->email)->where("expired_at", ">", now())->exists()) {

            $otp=Otp::where("code", $request->code)
                ->where("email", $request->email)
                ->where("expired_at", ">", now())->first();

            $driver= $otp->driver;
            $driver->email_verified_at=now();
            $driver->save();

            $driver->token= JWTAuth::getToken()->get();

            return $this->success(new DriverResponse($driver), __("auth.verifs"), 200);

        }else{

            event(new VerifyEmailEvent($this->otp($request->email)));

            return $this->error(__("auth.resent"), 400, __("auth.expire"));

        }

    }

    /**
     * Resent Verification Code
     */

    public function resent_code(){
        $driver=auth()->user();
        $otp= $this->otp($driver->email);
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
