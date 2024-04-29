<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Helpers\ApiResponse;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $data = Department::all();

        return ApiResponse::success($data, "Get data department success", 200);
    }
}
