<?php

use App\Http\Controllers\Api\Driver\AuthDriverController;
use App\Http\Controllers\Api\Driver\UpdateController;
use App\Http\Controllers\Api\Driver\ForgetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Driver\ContactController;

/**
 * First Route Group For Authentication In App
 */

Route::prefix("auth")->middleware(["api"])->group(function () {

    Route::post("register",                     [AuthDriverController::class, "register"]);
    Route::post("login",                        [AuthDriverController::class, "login"]);
    Route::post("logout",                       [AuthDriverController::class, "logout"]);
    Route::post("me",                           [AuthDriverController::class, "me"]);

    /**
     * Route Group To Email Verification.
     */
    Route::prefix("verify-email")->middleware(["auth:driver"])->group(function () {
        Route::post("/",                 [AuthDriverController::class, "verify_email"]);
        Route::post("/re-send-code",    [AuthDriverController::class, "resent_code"]);
    });

    /**
     * Route For Driver Information
     * Route For Car Information
     */
    Route::prefix("information")->middleware(["auth:driver"])->group(function () {

        Route::post("driver", [AuthDriverController::class, "driver"]);
        Route::post("car", [AuthDriverController::class, "car"]);

    });


});

/**
 * Second Route Group For Change-Password & Add Car Information & Add Driver Information
 */

Route::prefix("update")->middleware(["api", "auth:driver"])->group(function () {

    Route::post("change-password",                [UpdateController::class, "change_password"]);
    Route::post("car-information/{car}",                [UpdateController::class, "car_information"]);
    Route::post("update-profile/{profile}",                 [UpdateController::class, "update_profile"] );
});

/**
 * Third Route Group For Forget Password
 */

Route::prefix("forget-password")->middleware(["api"])->group(function () {

    Route::post("check-email",              [ForgetPasswordController::class, "check_email"]);
    Route::post("check-otp",               [ForgetPasswordController::class, "check_otp"]);
    Route::post("reset-password",           [ForgetPasswordController::class, "reset_password"]);

});

/**
 * Driver Contact With Us .
 */

Route::post("contact-us", [ContactController::class, "contact_us"])
    ->middleware(["auth:driver", "verified"]);
