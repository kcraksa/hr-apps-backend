<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Position;
use App\Helpers\ApiResponse;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $data = Position::all();

        return ApiResponse::success($data, "Get data position success", 200);
    }
}
