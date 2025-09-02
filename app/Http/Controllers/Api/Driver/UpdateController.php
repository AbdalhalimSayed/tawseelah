<?php

namespace App\Http\Controllers\Api\Driver;

use App\Helper\APIResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\CarInformationRequest;
use App\Http\Requests\Driver\ChangePasswordRequest;
use App\Http\Requests\Driver\DriverInformationRequest;
use App\Models\Car;
use App\Models\DriverProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UpdateController extends Controller
{
    use APIResponse;
    public function __construct(){
        $this->middleware('auth:driver');
    }

    public function change_password(ChangePasswordRequest $request){

        $driver = auth()->guard("driver")->user();
        $oldPassword = $request->old_password;

        if(!Hash::check($oldPassword,  $driver->password)){
            return $this->validationError(__("auth.data"), ["old_password" => __("auth.incorrect")]);
        }

        $driver->password = bcrypt($request->password);
        $driver->save();

        return $this->success(null, __("auth.update", ["update"=>__("auth.newPassword")]));

    }

    /**
     * Car Information For Drivers
     */
    public function car_information(CarInformationRequest $request)
    {
        $driver = auth()->guard("driver")->user();

            $data = $request->validated();
            $data['driver_id'] = $driver->id;
            if($request->hasFile('image') && $request->file('image')->isValid()
                && $request->hasFile('license') && $request->file('license')->isValid()){

                $image      = $request->file('image');
                $license    = $request->file("license");

                $image= $image->storeAs("drivers/cars/images", "car_" . $driver->id . "_image" . ".jpg");
                $data['image'] = $image;

                $license = $license->storeAs("drivers/cars/licenses", "car_" . $driver->id . "_license" . ".jpg");
                $data["license"] =$license;

            }

            if(Car::create($data)){
                return $this->success(null, __("ok"));
            }else{
                return $this->error(__("auth.failed"));
            }

    }

    /**
     * Driver Information
     */
    public function update_profile(DriverInformationRequest $request){

        $driver = auth()->guard("driver")->user();

        $data = $request->validated();
        $data['driver_id'] = $driver->id;

        if($request->hasFile('image') && $request->file('image')->isValid()
            && $request->hasFile("license") && $request->file("license")->isValid()
            && $request->hasFile("image")  && $request->file("image")->isValid()
        ){

            $image              = $request->file('image');
            $image              = $image->storeAs("drivers/profile", "driver_" . $driver->id . "_image" . ".jpg");
            $data["image"]      = $image;

            $license            = $request->file('license');
            $license            = $license->storeAs("drivers/licenses", "driver_" . $driver->id . "_license" . ".jpg");
            $data["license"]    = $license;

            $idCard             = $request->file("id_card");
            $idCard             = $idCard->storeAs("drivers/id_cards", "driver_" . $driver->id . "_id_card" . ".jpg");
            $data["id_card"]     = $idCard;

            unset($data["image"]);

        }

        if(DriverProfile::create($data)){
            $driver->image = $image;
            $driver->save();
            return $this->success(null, __("ok"));
        }else{
            return $this->error(__("auth.failed"));
        }
    }

}
