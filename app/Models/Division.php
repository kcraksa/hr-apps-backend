<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'directorate_id'
    ];

    public function Directorate(): HasOne
    {
        return $this->hasOne(Directorate::class, "id", "directorate_id");
    }

    public function Department(): HasMany
    {
        return $this->hasMany(Department::class, "division_id", "id");
    }
}
