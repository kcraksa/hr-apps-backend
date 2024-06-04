<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
