<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    use HasFactory;

    protected $table = "employee_documents";

    protected $fillable = [
        'nip',
        'family_card_number',
        'family_card_photo',
        'tax_name',
        'tax_number',
        'npwp_photo',
        'bpjs_kesehatan_number',
        'bpjs_kesehatan_photo',
        'bpjs_ketenagakerjaan_number',
        'bpjs_ketenagakerjaan_photo',
        'education_certificate_photo',
        'transcript_photo',
        'last_education',
        'major',
        'institution',
    ];

}
