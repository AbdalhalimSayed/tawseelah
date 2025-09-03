<?php

namespace App\Http\Controllers\Api\Driver;

use App\Events\VerifyEmailEvent;
use App\Helper\APIResponse;
use App\Helper\OTPVerifyEmail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\CarInformationRequest;
use App\Http\Requests\Driver\DriverInformationRequest;
use App\Http\Requests\Driver\RegisterRequest;
use App\Http\Requests\Driver\VerifyEmailRequest;
use App\Http\Resources\Driver\DriverResponse;
use App\Http\Resources\Driver\ProfileResponse;
use App\Models\Car;
use App\Models\Driver;
use App\Models\DriverProfile;
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
     * Method For Driver Information
     */
    public function driver(DriverInformationRequest $request){

        $driver = auth()->guard("driver")->user();

        if(!DriverProfile::where("driver_id", $driver->id)->exists()){

            $data = $request->validated();
            $data['driver_id'] = $driver->id;

            if($request->hasFile('image') && $request->file('image')->isValid()
                && $request->hasFile("license") && $request->file("license")->isValid()
                && $request->hasFile("image")  && $request->file("image")->isValid()
            ){

                $imageFile              = $request->file('image');
                $imageExtension         = $imageFile->getClientOriginalExtension();
                $imagePath              = $imageFile->storeAs("drivers/profile", "driver_" . $driver->id. "_". time() . "_image." . $imageExtension, "public");
                $data["image"]          = $imagePath;

                $licenseFile            = $request->file('license');
                $licenseExtension       = $licenseFile->getClientOriginalExtension();
                $licensePath            = $licenseFile->storeAs("drivers/licenses", "driver_" . $driver->id. "_". time() . "_license." .$licenseExtension, "public");
                $data["license"]        = $licensePath;

                $idCardFile             = $request->file("id_card");
                $idCardExtension        = $idCardFile->getClientOriginalExtension();
                $idCardPath             = $idCardFile->storeAs("drivers/id_cards", "driver_" . $driver->id. "_". time() . "_id_card." . $idCardExtension, "public");
                $data["id_card"]        = $idCardPath;

                unset($data["image"]);

            }

            if(DriverProfile::create($data)){
                $driver->image = $imagePath;
                $driver->save();
                return $this->success(null, __("auth.info_driver"));
            }else{
                return $this->error(__("auth.failed"));
            }

        }else{

            return $this->validationError(__("auth.has_driver"));

        }

    }

    /**
     * Method For Car Information
     */
    public function car(CarInformationRequest $request){
        $driver = auth()->guard("driver")->user();
        if(!Car::where("driver_id", $driver->id)->exists()){
            $data = $request->validated();
            $data['driver_id'] = $driver->id;
            if($request->hasFile('image') && $request->file('image')->isValid()
                && $request->hasFile('license') && $request->file('license')->isValid()){

                $imageFile      = $request->file('image');
                $licenseFile    = $request->file("license");

                $imageExtension = $imageFile->getClientOriginalExtension();
                $imagePath= $imageFile->storeAs("drivers/cars/images", "car_" . $driver->id . "_" . time() . "_image." . $imageExtension, "public");
                $data['image'] = $imagePath;

                $licenseExtension= $licenseFile->getClientOriginalExtension();
                $licensePath = $licenseFile->storeAs("drivers/cars/licenses", "car_" . $driver->id . "_" . time() . "_license." .$licenseExtension, "public");
                $data["license"] =$licensePath;

            }

            if(Car::create($data)){
                return $this->success(null, __("auth.info_car"));
            }else{
                return $this->error(__("auth.data"));
            }
        }else{
            return $this->validationError(__("auth.has_car"));
        }
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
