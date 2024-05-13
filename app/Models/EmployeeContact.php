<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeContact extends Model
{
    use HasFactory;

    protected $table = "employee_contacts";

    protected $fillable = [
        'nip',
        'contact_personal_email',
        'contact_emergency_number',
        'contact_relationship',
        'contact_name_of_person',
    ];

}
