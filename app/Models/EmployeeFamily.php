<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeFamily extends Model
{
    use HasFactory;

    protected $table = "employee_families";

    protected $fillable = [
        'nip',
        'biological_father_name',
        'biological_father_dob',
        'biological_father_pob',
        'biological_father_address',
        'biological_mother_name',
        'biological_mother_dob',
        'biological_mother_pob',
        'biological_mother_address',
        'spouse_children',
    ];

}
