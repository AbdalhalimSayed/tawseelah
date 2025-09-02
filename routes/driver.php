<?php

use App\Http\Controllers\Api\Driver\AuthDriverController;
use App\Http\Controllers\Api\Driver\UpdateController;
use App\Http\Controllers\Api\Driver\ForgetPasswordController;
use Illuminate\Support\Facades\Route;


Route::prefix("auth")->middleware(["api"])->group(function () {

    Route::post("register",                     [AuthDriverController::class, "register"]);
    Route::post("login",                        [AuthDriverController::class, "login"]);
    Route::post("logout",                       [AuthDriverController::class, "logout"]);
    Route::post("me",                           [AuthDriverController::class, "me"]);
    Route::post("verify-email",                 [AuthDriverController::class, "verify_email"]);
    Route::post("verify-email/re-send-code",    [AuthDriverController::class, "resent_code"]);
});


Route::prefix("update")->middleware(["api", "auth:driver"])->group(function () {

    Route::post("change-password",                [UpdateController::class, "change_password"]);
    Route::post("car-information",                [UpdateController::class, "car_information"]);
    Route::post("update-profile",                 [UpdateController::class, "update_profile"] );
});


Route::prefix("forget-password")->middleware(["api"])->group(function () {

    Route::post("check-email",              [ForgetPasswordController::class, "check_email"]);
    Route::post("check-otp",               [ForgetPasswordController::class, "check_otp"]);
    Route::post("reset-password",           [ForgetPasswordController::class, "reset_password"]);

});
