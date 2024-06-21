<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionDocument extends Model
{
    use HasFactory;

    protected $table = "permission_documents";
    protected $fillable = [
        "permission_id",
        "type_file",
        "file",
        "description"
    ];
}
