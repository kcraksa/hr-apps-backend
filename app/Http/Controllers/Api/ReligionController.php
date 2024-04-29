<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Religion;
use App\Helpers\ApiResponse;

class ReligionController extends Controller
{
    public function index()
    {
        $data = Religion::all();

        return ApiResponse::success($data, "Get data religion success", 200);
    }
}
