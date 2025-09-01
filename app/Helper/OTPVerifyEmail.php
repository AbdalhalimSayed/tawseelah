<?php

namespace App\Helper;

use App\Models\Otp;

trait OTPVerifyEmail
{
    protected function otp($email){
//        Delete Email If Exists
        if(Otp::where('email',$email)->exists()){
            Otp::where('email',$email)->delete();
        }
        $code=random_int(10000,99999);

//        Delete Code Is Exists And Expire
        if (Otp::where("code",$code)->where("expired_at", "<", now())->exists()) {
            Otp::where("code",$code)->where("expired_at", "<", now())->delete();
        }

        while (Otp::where("code",$code)->where("expired_at", ">", now())->exists()){
            $code=random_int(10000,99999);
        }

       $otp= Otp::create([
           "code"=>$code,
           "email"=>$email,
           "expired_at"=>now()->addMinutes(10),
       ]);
        return $otp;
    }
}
