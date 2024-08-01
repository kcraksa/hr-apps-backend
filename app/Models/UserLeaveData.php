<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserLeaveData extends Model
{
    use HasFactory;

    protected $table = "user_leave_datas";

    protected $fillable = [
        "user_id",
        "year",
        "leave_balance",
        "expired_balance",
        "total_balance",
        "status"
    ];

    public static function handleLeaveRequest($userId, $leaveDays)
    {
        $currentYear = Carbon::now()->year;
        $currentDate = Carbon::now();
        $userLeaveData = self::where('user_id', $userId)->where('year', $currentYear)->first();
        $lastYearLeaveData = self::where('user_id', $userId)->where('year', $currentYear - 1)->first();

        // Reset last year's leave if today is after March 31
        if ($lastYearLeaveData && $currentDate->isAfter(Carbon::create($currentYear, 3, 31))) {
            $lastYearLeaveData->expired_balance = 0;
            $lastYearLeaveData->save();
        }

        // Calculate available leave
        $availableLeave = $userLeaveData->leave_balance;
        if ($lastYearLeaveData && $currentDate->isBefore(Carbon::create($currentYear, 3, 31))) {
            $availableLeave += $lastYearLeaveData->expired_balance;
        }

        // Check if the leave request exceeds the available leave
        if ($leaveDays > $availableLeave) {
            return ['error' => 'Insufficient leave quota'];
        }

        // Deduct the leave days from the available leave
        if ($lastYearLeaveData && $currentDate->isBefore(Carbon::create($currentYear, 3, 31))) {
            if ($leaveDays <= $lastYearLeaveData->expired_balance) {
            $lastYearLeaveData->expired_balance -= $leaveDays;
            } else {
            $remainingDays = $leaveDays - $lastYearLeaveData->expired_balance;
            $lastYearLeaveData->expired_balance = 0;
            $userLeaveData->leave_balance -= $remainingDays;
            }
            $lastYearLeaveData->save();
        } else {
            $userLeaveData->leave_balance -= $leaveDays;
        }

        $userLeaveData->total_balance = $userLeaveData->leave_balance - $userLeaveData->expired_balance;
        $userLeaveData->save();

        return ['success' => 'Leave request processed successfully'];
    }
}
