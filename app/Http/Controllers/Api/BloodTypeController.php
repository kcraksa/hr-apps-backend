<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodType;
use App\Helpers\ApiResponse;
use App\Exceptions\DataNotFoundException;

class BloodTypeController extends Controller
{
    public function index(Request $request)
    {
        $data = BloodType::all();

        return ApiResponse::success($data, "Get data blood type success", 200);
    }
}
