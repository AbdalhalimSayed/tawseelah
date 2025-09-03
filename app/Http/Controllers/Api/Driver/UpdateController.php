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
use Illuminate\Support\Facades\Storage;

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
     * Update Car Information For Drivers
     */
    public function car_information(CarInformationRequest $request,  $carId)
    {

        $car = Car::find($carId);

        $driver = auth()->guard("driver")->user();

        if(!$car){
            return $this->validationError("Car Not Found");
        }else{
            if($driver->id == $car->driver_id){
                $data = $request->validated();
                $car->fill($data);

                if($car->isDirty()){
                    $imageFile      = $request->file('image');
                    $licenseFile    = $request->file("license");

                    $imageExtension = $imageFile->getClientOriginalExtension();
                    $imagePath= $imageFile->storeAs("drivers/cars/images", "car_" . $driver->id . "_" . time() . "_image." . $imageExtension, "public");
                    $data["image"] = $imagePath;

                    $licenseExtension= $licenseFile->getClientOriginalExtension();
                    $licensePath = $licenseFile->storeAs("drivers/cars/licenses", "car_" . $driver->id . "_" . time() . "_license." .$licenseExtension, "public");
                    $data["license"] =$licensePath;
                    $car->fill($data);
                    $car->save();
                    return $this->success(null, __("auth.update", ["update" => __("auth.info_car")]));
                }

            }else{
                return $this->validationError("Car Is Not Special for this Driver");
            }
        }




    }

    /**
     * Update Driver Information
     */
    public function update_profile(DriverInformationRequest $request, $profileId){

        $driver = auth()->guard("driver")->user();

        $driverProfile=DriverProfile::find($profileId);
        if(!$driverProfile){
            return $this->validationError("Driver Profile Not Found");
        }

        if($driver->id == $driverProfile->driver_id){
            $data = $request->validated();
            $driverProfile->fill($data);
            if($driverProfile->isDirty()){

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

                $driverProfile->fill($data);
                $driverProfile->save();
                $driver->image=$imagePath;
                $driver->save();
                return $this->success(null, __("auth.update", ["update" => __("auth.info_driver")]));

            }
        }

        return $this->validationError("Driver Profile No Changed");

    }

}
