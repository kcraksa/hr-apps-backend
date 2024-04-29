<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DriverLicenseType;
use App\Helpers\ApiResponse;

class DriverLicenseTypeController extends Controller
{
    public function index()
    {
        $data = DriverLicenseType::all();

        return ApiResponse::success($data, "Get data driver license type success", 200);
    }
}
