<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    use HasFactory;

    protected $primaryKey = "id";
    public $incrementing = false;

    protected $fillable = [
        "id",
        "name",
        "alamat",
        "district_id",
        "status"
    ];

    public function District(): HasOne
    {
        return $this->hasOne(District::class, "id", "district_id");
    }

    public function ImagesCompany(): HasOne
    {
        return $this->hasOne(ImagesCompany::class, "id", "company_id");
    }
}
