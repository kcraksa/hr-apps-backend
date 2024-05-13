<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeVerklaring extends Model
{
    use HasFactory;

    protected $table = "employee_verklarings";

    protected $fillable = [
        'nip',
        'verklaring_photo',
    ];

}
