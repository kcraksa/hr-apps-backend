<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Relation extends Model
{
    use HasFactory;

    protected $fillable = [
        "lead_id", "employee_id"
    ];

    public function User(): HasOne
    {
        return $this->hasOne(User::class, "id", "lead_id");
    }
}
