<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionSick extends Model
{
    use HasFactory;
    protected $table = "permission_sicks";
    protected $fillable = [
        "permission_id",
        "hospital_name",
        "diagnosis"
    ];
}
