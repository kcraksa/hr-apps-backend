<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;
use App\Helpers\ApiResponse;
use App\Exceptions\DataNotFoundException;

class DistrictController extends Controller
{
    public function index(Request $request)
    {
        $data = District::where("city_id", $request->city_id)->get();

        return ApiResponse::success($data, "Get data district success", 200);
    }
}
