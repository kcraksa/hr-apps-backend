<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'join_date',
        'contract_start_date',
        'contract_end_date',
        'fixed_date',
        'employment_status',
        'position_id',
        'group_absent_id',
        'level_id',
    ];

    public function User(): HasOne
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function Position(): HasOne
    {
        return $this->hasOne(Position::class, "id", "position_id");
    }

    public function Level(): HasOne
    {
        return $this->hasOne(Level::class, "id", "level_id");
    }

    public function Superior(): HasOne
    {
        return $this->hasOne(Employee::class, "nip", "superior_nip");
    }

    public function Address(): HasOne
    {
        return $this->hasOne(EmployeeAddress::class, "nip", "nip");
    }

    public function PersonalData(): HasOne
    {
        return $this->hasOne(EmployeePersonalData::class, "nip", "nip");
    }

    public function HealthBalance(): HasOne
    {
        return $this->hasOne(UserHealthBalance::class, "user_id", "user_id");
    }

    public function LeaveData(): HasOne
    {
        return $this->hasOne(UserLeaveData::class, "user_id", "user_id");
    }
}
