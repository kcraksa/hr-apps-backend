<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use App\Helpers\ApiResponse;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $data = Bank::all();

        return ApiResponse::success($data, "Get data bank success", 200);
    }
}
