<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterData;
use App\Models\Position;
use App\Helpers\ApiResponse;

class MasterdataController extends Controller
{
    public function getScopes()
    {
        $data = MasterData::getScopes();
        return ApiResponse::success($data, "Get data scopes success", 200);
    }

    public function getPositionEmployee(Request $request)
    {
        $data = Position::with(
            [
                "Team", 
                "Team.Section", 
                "Team.Section.Department", 
                "Team.Section.Department.Division",
                "Team.Section.Department.Division.Directorate",
                "Team.Section.Department.Division.Directorate.Company",
            ]
        )
        ->where("name", "like", "%".$request->query('search')."%")
        ->limit(10)
        ->get();
        return ApiResponse::success($data, "Get data position success", 200);
    }
}
