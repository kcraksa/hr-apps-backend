<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Province;
use App\Helpers\ApiResponse;

class ProvinceController extends Controller
{
    public function index(Request $request)
    {
        $data = Province::all();

        return ApiResponse::success($data, "Get data province success", 200);
    }
}
