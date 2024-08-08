<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'department_id'];

    public function Department(): HasOne
    {
        return $this->hasOne(Department::class, "id", "department_id");
    }

    public function Team(): HasMany
    {
        return $this->hasMany(Team::class, "section_id", "id");
    }
}
