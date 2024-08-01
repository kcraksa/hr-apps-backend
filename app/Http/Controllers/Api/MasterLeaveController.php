<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\UserLeaveData;
use App\Models\UserHealthBalance;
use App\Helpers\ApiResponse;
use App\Exceptions\DataNotFoundException;

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

    public function updateLeaveBalance(Request $request, string $id) 
    {
        $request->validate([
            'adjust_type' => 'required|in:addition,subtraction',
            'adjust_balance' => 'required',
            'year' => 'required'
        ]);

        $leaveData = UserLeaveData::where([
            "id" => $id,
            "year" => $request->year
        ])->first();
        if (!$leaveData) {
            throw new DataNotFoundException;
        }
        
        if ($request->adjust_type == "addition") {
            $leaveData->leave_balance = $leaveData->leave_balance + $request->adjust_balance;
        } else {
            $leaveData->leave_balance = $leaveData->leave_balance - $request->adjust_balance;
        }

        if ($leaveData->leave_balance > 12) {
            return ApiResponse::error("Leave balance cannot greater than 12", 400);
        }

        $leaveData->total_balance = $leaveData->leave_balance - $leaveData->expired_balance;
        $leaveData->save();

        return ApiResponse::success($leaveData, "Update leave balance success", 200);
    }

    public function updateHealthBalance(Request $request, string $id) 
    {
        $request->validate([
            'adjust_type' => 'required|in:addition,subtraction',
            'adjust_balance' => 'required',
            'year' => 'required'
        ]);

        $healthData = UserHealthBalance::where([
            "id" => $id,
            "year" => $request->year
        ])->first();
        if (!$healthData) {
            throw new DataNotFoundException;
        }
        
        if ($request->adjust_type == "addition") {
            $healthData->health_balance = $healthData->health_balance + $request->adjust_balance;
        } else {
            $healthData->health_balance = $healthData->health_balance - $request->adjust_balance;
        }

        if ($healthData->health_balance > 9000000) {
            return ApiResponse::error("Leave balance cannot greater than plafond", 400);
        }

        $healthData->balance_update = now();
        $healthData->save();

        return ApiResponse::success($healthData, "Update health balance success", 200);
    }

    public function getLeaveandHealthBalanceByUserId(string $user_id)
    {
        try {
            $currentYear = date("Y");
            $leaveBalance = UserLeaveData::where("user_id", "=", $user_id)
                                ->where("year", "=", $currentYear)
                                ->orWhere("year", "=", $currentYear - 1)
                                ->get();

            $healthBalance = UserHealthBalance::where("user_id", "=", $user_id)
                                ->where("year", "=", $currentYear)
                                ->first();

            $data = [
                "leave_balance" => $leaveBalance,
                "health_balance" => $healthBalance
            ];

            return ApiResponse::success($data, "success get leave and health remaining", 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
