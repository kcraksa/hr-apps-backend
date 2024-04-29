<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaritalStatus;
use App\Helpers\ApiResponse;

class MaritalStatusController extends Controller
{
    public function index()
    {
        $data = MaritalStatus::all();

        return ApiResponse::success($data, "Get data marital status success", 200);
    }
}
