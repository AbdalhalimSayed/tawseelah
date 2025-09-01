<?php

namespace App\Helper;

use App\Events\ForgetPasswordEvent;
use App\Models\PasswordReset;

trait OTPPasswordReset
{
    protected function otpPassword($email){
        //        Delete Email If Exists
        if(PasswordReset::where('email',$email)->exists()){
            PasswordReset::where('email',$email)->delete();
        }

        $code=random_int(10000,99999);

//        Delete Code Is Exists And Expire
        if (PasswordReset::where("code",$code)->where("expired_at", "<", now())->exists()) {
            PasswordReset::where("code",$code)->where("expired_at", "<", now())->delete();
        }

        while (PasswordReset::where("code",$code)->where("expired_at", ">", now())->exists()){
            $code=random_int(10000,99999);
        }


        $otpPassword= PasswordReset::create([
            "code"=>$code,
            "email"=>$email,
            "expired_at"=>now()->addMinutes(10),
        ]);

        event(new forgetPasswordEvent($otpPassword));

        return $this->success(null, __("passwords.sent"));
    }
}
