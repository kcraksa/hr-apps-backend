<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\OfficePlaceController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\SubdistrictController;
use App\Http\Controllers\Api\BloodTypeController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ReligionController;
use App\Http\Controllers\Api\MaritalStatusController;
use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\DriverLicenseTypeController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\MasterdataController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\DirectorateController;
use App\Http\Controllers\Api\GroupAbsentController;

Route::prefix("v1")->group(function() {
    Route::post("/register", [AuthController::class, "register"]);
    Route::post("/login", [AuthController::class, "login"]);

    Route::middleware(['auth:sanctum', 'abilities:apps'])->group(function() {
        Route::get("/office-places", [OfficePlaceController::class, "index"]);
        Route::get("/levels", [LevelController::class, "index"]);
        Route::get("/provinces", [ProvinceController::class, "index"]);
        Route::get("/cities", [CityController::class, "index"]);
        Route::get("/districts", [DistrictController::class, "index"]);
        Route::get("/subdistricts", [SubdistrictController::class, "index"]);
        Route::get("/blood-types", [BloodTypeController::class, "index"]);
        Route::get("/religions", [ReligionController::class, "index"]);
        Route::get("/marital-statuses", [MaritalStatusController::class, "index"]);
        Route::get("/banks", [BankController::class, "index"]);
        Route::get("/driver-license-types", [DriverLicenseTypeController::class, "index"]);

        // Role
        Route::get("/modules", [RoleController::class, "getModule"]);
        Route::get("/functions/{module_id}", [RoleController::class, "getFunction"]);
        Route::get("/user/role", [RoleController::class, "getUserRoles"]);
        Route::post("/role", [RoleController::class, "store"]);
        Route::get("/role/search/employee", [RoleController::class, "searchEmployee"]);
        Route::post("/role/function/create", [RoleController::class, "createFunction"]);

        // Employee
        Route::get("/employees", [EmployeeController::class, "index"]);
        Route::get("/employee/{nip}", [EmployeeController::class, "employeeByNip"]);
        Route::post("/employee/create", [EmployeeController::class, "create"]);
        Route::put("/employee/{nip}", [EmployeeController::class, "update"]);

        // MasterData
        Route::get("/master/scopes", [MasterdataController::class, "getScopes"]);

        // Company
        Route::get("/company", [CompanyController::class, "index"]);
        Route::post("/company", [CompanyController::class, "create"]);
        Route::patch("/update-status/{code}", [CompanyController::class, "updateStatus"]);
        Route::delete("/company/{code}", [CompanyController::class, "delete"]);

        // Division
        Route::get("/division", [DivisionController::class, "index"]);
        Route::get("/division/{id}", [DivisionController::class, "findById"]);
        Route::post("/division", [DivisionController::class, "store"]);
        Route::put("/division/{id}", [DivisionController::class, "update"]);
        Route::delete("/division/{id}", [DivisionController::class, "delete"]);

        // Teams
        Route::get("/teams", [TeamController::class, "index"]);
        Route::get("/team/{id}", [TeamController::class, "findById"]);
        Route::post("/team", [TeamController::class, "store"]);
        Route::put("/team/{id}", [TeamController::class, "update"]);
        Route::delete("/team/{id}", [TeamController::class, "delete"]);

        // Directorate
        Route::get("/directorates", [DirectorateController::class, "index"]);
        Route::get("/directorate/{id}", [DirectorateController::class, "findById"]);
        Route::post("/directorate", [DirectorateController::class, "store"]);
        Route::put("/directorate/{id}", [DirectorateController::class, "update"]);
        Route::delete("/directorate/{id}", [DirectorateController::class, "delete"]);

        // Department
        Route::get("/departments", [DepartmentController::class, "index"]);
        Route::get("/department/{id}", [DepartmentController::class, "findById"]);
        Route::post("/department", [DepartmentController::class, "store"]);
        Route::put("/department/{id}", [DepartmentController::class, "update"]);
        Route::delete("/department/{id}", [DepartmentController::class, "delete"]);

        // Section
        Route::get("/sections", [SectionController::class, "index"]);
        Route::get("/section/{id}", [SectionController::class, "findById"]);
        Route::post("/section", [SectionController::class, "store"]);
        Route::put("/section/{id}", [SectionController::class, "update"]);
        Route::delete("/section/{id}", [SectionController::class, "delete"]);

        // Position
        Route::get("/positions", [PositionController::class, "index"]);
        Route::get("/position/{id}", [PositionController::class, "findById"]);
        Route::post("/position", [PositionController::class, "store"]);
        Route::put("/position/{id}", [PositionController::class, "update"]);
        Route::delete("/position/{id}", [PositionController::class, "delete"]);
        Route::get("/position/employee/dropdown", [MasterdataController::class, "getPositionEmployee"]);

        // Position
        Route::get("/levels", [LevelController::class, "index"]);
        Route::get("/level/{id}", [LevelController::class, "findById"]);
        Route::post("/level", [LevelController::class, "store"]);
        Route::put("/level/{id}", [LevelController::class, "update"]);
        Route::delete("/level/{id}", [LevelController::class, "delete"]);

        // Position
        Route::get("/group-absents", [GroupAbsentController::class, "index"]);
        Route::get("/group-absent/{id}", [GroupAbsentController::class, "findById"]);
        Route::post("/group-absent", [GroupAbsentController::class, "store"]);
        Route::put("/group-absent/{id}", [GroupAbsentController::class, "update"]);
        Route::delete("/group-absent/{id}", [GroupAbsentController::class, "delete"]);
    });

    Route::middleware(['auth:sanctum', 'abilities:email-verification'])->group(function() {  
        Route::post("/verify-email", [AuthController::class, "emailVerification"]);
        Route::post("/verify-otp-email", [AuthController::class, "emailOtpVerification"]);
    });
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
