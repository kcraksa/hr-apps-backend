<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Position extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'team_id'];

    public function Team(): HasOne
    {
        return $this->hasOne(Team::class, "id", "team_id");
    }
}
