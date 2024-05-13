<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDriverLicense extends Model
{
    use HasFactory;

    protected $table = "employee_driver_licenses";

    protected $fillable = [
        'nip',
        'driver_license_type',
        'driver_license_number',
        'driver_license_photo',
    ];

}
