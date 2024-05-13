<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlaceOffice;
use App\Helpers\ApiResponse;

class OfficePlaceController extends Controller
{
    public function index(Request $request)
    {
        $data = PlaceOffice::all();

        return ApiResponse::success($data, "Get data office place success", 200);
    }
}
