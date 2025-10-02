<?php

use App\Http\Controllers\Api\User\ForgetPasswordController;
use App\Http\Controllers\Api\User\UpdateController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\User\ContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\AuthUserController;
use App\Http\Controllers\Api\User\OrderController;

/**
 * First Route Group For Authentication In App
 */
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth/'

], function () {

    Route::post('register',                     [AuthUserController::class, 'register']);
    Route::post('login',                        [AuthUserController::class, 'login']);
    Route::post('logout',                       [AuthUserController::class, 'logout']);
    Route::post('me',                           [AuthUserController::class, 'me']);
    /**
     * Route Group To Email Verification.
     */
    Route::prefix("verify-email")->group(function () {
        Route::post("/",                 [AuthUserController::class, "verify_email"]);
        Route::post("/re-send-code",    [AuthUserController::class, "resent_code"]);
    });
});

/**
 * Second Route Group For Change-Password & Add User Image Information
 */

Route::prefix('update')->middleware(["auth", "verified", "api"])->group(function () {

    Route::post('password',     [UpdateController::class, "password"]);
    Route::post("image",        [UpdateController::class, "image"]);
});

/**
 * Third Route Group For Forget Password
 */
Route::prefix('forget-password')->group(function () {

    Route::post("check-email",      [ForgetPasswordController::class, "check_email"]);
    Route::post("check-otp",        [ForgetPasswordController::class, "check_otp"]);
    Route::post("reset-password",   [ForgetPasswordController::class, "reset_password"]);
});

/**
 * User Contact With Us .
 */

Route::post("contact-us", [ContactController::class, "contact_us"])
    ->middleware(["auth:api", "verified"]);

//Users Orders
Route::middleware('auth:api')->prefix('orders')->group(function () {
    Route::post('/',              [OrderController::class, 'store']);
    Route::get('/',               [OrderController::class, 'index']);
    Route::get('/{id}',          [OrderController::class, 'show']);
    Route::put('/{id}/cancel',   [OrderController::class, 'cancel']);
});
