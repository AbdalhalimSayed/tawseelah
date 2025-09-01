<?php

namespace App\Http\Controllers\Api\User;

use App\Helper\APIResponse;
use App\Helper\OTPPasswordReset;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CheckMailRequest;
use App\Http\Requests\User\CheckOtpRequest;
use App\Http\Requests\User\PasswordResetRequest;
use App\Models\PasswordReset;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    use OTPPasswordReset, APIResponse;
    public function check_email(CheckMailRequest $request)
    {
        $email = $request->email;
        return $this->otpPassword($email);

    }

    public function check_otp(CheckOtpRequest $request){
        if (PasswordReset::where("email", $request->email)->where("code", $request->code)->exists()) {
            $passwordReset = PasswordReset::where("email", $request->email)->where("code", $request->code)->first();

            if($passwordReset->expired_at < now()){
                $this->otpPassword($request->email);
                return $this->error(__("auth.expired_code"));
            }

            return $this->success(null, __("auth.correct"));
        }

        return $this->error(__("auth.invalid_credentials"));
    }


    public function reset_password(PasswordResetRequest $request){
        if (PasswordReset::where("email", $request->email)->where("code", $request->code)->exists()) {
            $code=PasswordReset::where("email", $request->email)->where("code", $request->code)->first();
            $code->user->password=bcrypt($request->password);
            $code->user->save();
            return $this->success(null, __("passwords.reset"));
        }

        return $this->error(__("auth.data"),404);

    }
}
