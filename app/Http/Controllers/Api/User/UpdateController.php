<?php

namespace App\Http\Controllers\Api\User;

use App\Helper\APIResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\ImageRequest;
use App\Http\Resources\User\UserResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UpdateController extends Controller
{
    use APIResponse;
    public function password(ChangePasswordRequest $request){
        $user=auth()->user();

        $oldPassword=$request->old_password;

        if(!Hash::check($oldPassword,  $user->password)){
            return $this->validationError(__("auth.data"), ["old_password" => __("auth.incorrect")]);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return $this->success(null, __("auth.update", ["update"=>__("auth.newPassword")]));
    }

    public function image(ImageRequest $request){

        if($request->hasFile("image" ) && $request->file("image")->isValid() ){

            $file=$request->file("image");

            $path=$file->store("user/profile");

            $user=auth()->user();

            $user->image=$path;

            $user->save();

            return $this->success(new UserResponse($user), __("auth.update", ["update"=> __("Image")]));
        }

    }
}
