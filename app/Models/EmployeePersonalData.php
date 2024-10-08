<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePersonalData extends Model
{
    use HasFactory;

    protected $table = "employee_personal_datas";

    protected $fillable = [
        'nip',
        'gender',
        'weight',
        'height',
        'bloodtype',
        'placeofbirth',
        'dateofbirth',
        'religion',
        'nationality',
        'self_photo',
    ];

}
