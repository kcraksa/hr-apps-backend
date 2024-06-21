<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionDate extends Model
{
    use HasFactory;

    protected $table = "permission_dates";
    protected $fillable = [
        'permission_id',
        'fromdatetime',
        'tmpfromdate',
        'todatetime',
        'tmptodate',
        'datediff'
    ];
}
