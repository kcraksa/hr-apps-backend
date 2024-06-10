<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\UserLeaveData;
use App\Models\UserHealthBalance;
use App\Helpers\ApiResponse;

class MasterLeaveController extends Controller
{
    public function index(Request $request)
    {
        // Get search query
        $search = $request->query('search', '');
        $page = $request->query('page', 1);

        $datas = Employee::with([
            "User",
            "Position",
            "Level",
            "Position.Team",
            "Position.Team.Section",
            "Position.Team.Section.Department"
        ])->paginate(10, ['*'], 'page', $page);
        
        $currentYear = date("Y");

        foreach ($datas as $data) {
            // leave data
            $leaveBalanceCurrentYear = UserLeaveData::where("user_id", "=", $data->user_id)
                                        ->where("year", ">=", $currentYear - 1)
                                        ->where("year", "<=", $currentYear)
                                        ->get();

            $data["leave_balance"] = $leaveBalanceCurrentYear;

            // health balance
            $healthBalanceCurrentYear = UserHealthBalance::where("user_id", "=", $data->user_id)
                                        ->where("year", ">=", $currentYear - 1)
                                        ->where("year", "<=", $currentYear)
                                        ->get();

            $data["health_balance"] = $healthBalanceCurrentYear;
        }

        return ApiResponse::success($datas, "success get data", 200);
    }
}
