<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBank extends Model
{
    use HasFactory;

    protected $table = "employee_banks";

    protected $fillable = [
        'nip',
        'bank',
        'bank_account',
    ];
}
