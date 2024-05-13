<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Helpers\ApiResponse;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $data = Section::all();

        return ApiResponse::success($data, "Get data section success", 200);
    }
}
