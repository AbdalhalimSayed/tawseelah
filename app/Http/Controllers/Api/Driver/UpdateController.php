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
use function Pest\Laravel\delete;

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
                    Storage::delete($driver->car->image);
                    Storage::delete($driver->car->license);
                    $uploads = [
                        'image' => [ 'path' => 'drivers/cars/images', 'suffix' => '_image' ],
                        'license' => [ 'path' => 'drivers/cars/licenses', 'suffix' => '_license' ],
                    ];

                    foreach ($uploads as $key => $details) {
                        if ($request->hasFile($key)) {
                            $file = $request->file($key);
                            $fileName = "driver_" . $driver->id . "_" . time() . $details['suffix'] . "." . $file->extension();
                            $path = $file->storeAs($details['path'], $fileName);
                            $data[$key] = $path;
                        }
                    }
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

                /**
                 * Upload Update Images For Driver
                 */
                Storage::delete($driver->image);
                Storage::delete($driver->driver_profile->license);
                Storage::delete($driver->driver_profile->id_card);
                $uploads = [
                    'image' => [ 'path' => 'drivers/profile', 'suffix' => '_image' ],
                    'license' => [ 'path' => 'drivers/licenses', 'suffix' => '_license' ],
                    'id_card' => [ 'path' => 'drivers/id_cards', 'suffix' => '_id_card' ],
                ];

                foreach ($uploads as $key => $details) {
                    if ($request->hasFile($key)) {
                        $file = $request->file($key);
                        $fileName = "driver_" . $driver->id . "_" . time() . $details['suffix'] . "." . $file->extension();
                        $path = $file->storeAs($details['path'], $fileName);
                        $data[$key] = $path;
                    }
                }
                $imagePath = $data['image'];
                unset($data['image']);


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
