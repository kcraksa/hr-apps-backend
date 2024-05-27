<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Directorate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'company_id'
    ];

    public function Company(): HasOne
    {
        return $this->hasOne(Company::class, "id", "company_id");
    }
}
