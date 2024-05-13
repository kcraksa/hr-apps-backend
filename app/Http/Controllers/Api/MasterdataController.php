<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterData;
use App\Helpers\ApiResponse;

class MasterdataController extends Controller
{
    public function getScopes()
    {
        $data = MasterData::getScopes();
        return ApiResponse::success($data, "Get data scopes success", 200);
    }
}
