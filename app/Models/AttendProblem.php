<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendProblem extends Model
{
    use HasFactory;

    protected $table = "attend_problems";

    protected $fillable = [
        'user_id',
        'date',
        'category',
        'is_personalia_approved',
        'personalia_approved_date',
        'personalia_approved_by',
        'is_supervisor_approved',
        'supervisor_approved_date',
        'supervisor_approved_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function personaliaApprover()
    {
        return $this->belongsTo(User::class, 'personalia_approved_by');
    }

    public function supervisorApprover()
    {
        return $this->belongsTo(User::class, 'supervisor_approved_by');
    }
}
