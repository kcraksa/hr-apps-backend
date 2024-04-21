<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subdistrict;
use App\Helpers\ApiResponse;
use App\Exceptions\DataNotFoundException;

class SubdistrictController extends Controller
{
    public function index(Request $request)
    {
        $data = Subdistrict::where("district_id", $request->district)->get();

        return ApiResponse::success($data, "Get data subdistrict success", 200);
    }
}
