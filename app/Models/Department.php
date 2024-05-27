<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'division_id'
    ];

    public function Division(): HasOne
    {
        return $this->hasOne(Division::class, "id", "division_id");
    }
}
