<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\SubdistrictController;

Route::prefix("v1")->group(function() {
    Route::post("/register", [AuthController::class, "register"]);
    Route::post("/login", [AuthController::class, "login"]);

    Route::middleware('auth:sanctum')->group(function() {      
        
        // auth
        Route::post("/verify-email", [AuthController::class, "emailVerification"]);

        Route::get("/provinces", [ProvinceController::class, "index"]);
        Route::get("/cities", [CityController::class, "index"]);
        Route::get("/districts", [DistrictController::class, "index"]);
        Route::get("/subdistricts", [SubdistrictController::class, "index"]);
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
