<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\SubdistrictController;
use App\Http\Controllers\Api\BloodTypeController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ReligionController;
use App\Http\Controllers\Api\MaritalStatusController;
use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\DriverLicenseTypeController;

Route::prefix("v1")->group(function() {
    Route::post("/register", [AuthController::class, "register"]);
    Route::post("/login", [AuthController::class, "login"]);

    Route::middleware(['auth:sanctum', 'abilities:apps'])->group(function() {   
        Route::get("/provinces", [ProvinceController::class, "index"]);
        Route::get("/cities", [CityController::class, "index"]);
        Route::get("/districts", [DistrictController::class, "index"]);
        Route::get("/subdistricts", [SubdistrictController::class, "index"]);
        Route::get("/blood-types", [BloodTypeController::class, "index"]);
        Route::get("/religions", [ReligionController::class, "index"]);
        Route::get("/marital-statuses", [MaritalStatusController::class, "index"]);
        Route::get("/banks", [BankController::class, "index"]);
        Route::get("/driver-license-types", [DriverLicenseTypeController::class, "index"]);

        // Employee
        Route::get("/employee/{nip}", [EmployeeController::class, "employeeByNip"]);
    });

    Route::middleware(['auth:sanctum', 'abilities:email-verification'])->group(function() {  
        Route::post("/verify-email", [AuthController::class, "emailVerification"]);
        Route::post("/verify-otp-email", [AuthController::class, "emailOtpVerification"]);
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
