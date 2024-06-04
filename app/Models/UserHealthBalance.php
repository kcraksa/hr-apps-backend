<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHealthBalance extends Model
{
    use HasFactory;

    protected $table = "user_health_balances";

    protected $fillable = [
        "user_id",
        "year",
        "health_balance",
        "total_balance",
        "balance_update",
        "status"
    ];
}
