<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Helpers\ApiResponse;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth()->user()->id;
        $page = $request->query('page', 1);

        // Fetch divisions with pagination and search
        $permission = Permission::where("user_id", "=", $user_id)
                        ->paginate(10, ['*'], 'page', $page);

        return ApiResponse::success($permission, "success get data permission", 200);
    }
}
