<?php

use App\Http\Controllers\Api\User\ForgetPasswordController;
use App\Http\Controllers\Api\User\UpdateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\AuthUserController;

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth/'

], function () {

    Route::post('register',                     [AuthUserController::class,'register']);
    Route::post('login',                        [AuthUserController::class,'login']);
    Route::post('logout',                       [AuthUserController::class,'logout']);
    Route::post('me',                           [AuthUserController::class,'me']);
    Route::post("verify-email",                 [AuthUserController::class, "verify_email"]);
    Route::post("verify-email/re-send-code",    [AuthUserController::class, "resent_code"]);

});

// Update Information And Password

Route::prefix('update')->middleware(["auth", "verified", "api"])->group(function () {

    Route::post('password',     [UpdateController::class, "password"]);
    Route::post("image",        [UpdateController::class, "image"]);
});

//Forget Password

Route::prefix('forget-password')->group(function () {

    Route::post("check-email",      [ForgetPasswordController::class, "check_email"]);
    Route::post("check-otp",        [ForgetPasswordController::class, "check_otp"]);
    Route::post("reset-password",   [ForgetPasswordController::class, "reset_password"]);

});
