<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Helpers\ApiResponse;
use App\Exceptions\DataNotFoundException;

class CityController extends Controller
{
    public function index(Request $request)
    {
        if ($request->province_id) {
            $data = City::where("province_id", $request->province_id)->get();
        } else {
            $data = City::all();
        }

        return ApiResponse::success($data, "Get data city success", 200);
    }
}
