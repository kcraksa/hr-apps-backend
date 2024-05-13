<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Level;
use App\Helpers\ApiResponse;

class LevelController extends Controller
{
    public function index(Request $request)
    {
        $data = Level::all();

        return ApiResponse::success($data, "Get data level success", 200);
    }
}
