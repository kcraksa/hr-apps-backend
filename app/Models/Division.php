<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function Directorate(): HasOne
    {
        return $this->hasOne(Directorate::class, "id", "directorate_id");
    }
}
