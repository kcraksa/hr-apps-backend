<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\FunctionModel;
use App\Helpers\ApiResponse;

class RoleController extends Controller
{
    public function getModule(Request $request)
    {
        $data = Module::all();

        return ApiResponse::success($data, "Get data module success", 200);
    }

    public function getFunction(Request $request, string $module_id)
    {
        $data = FunctionModel::where("id_module", $module_id)->get();

        return ApiResponse::success($data, "Get data function success", 200);
    }
}
