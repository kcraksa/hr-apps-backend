<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'create_id',
        'type',
        'subtype',
        'category',
        'reason',
        'status'
    ];

    public function PermissionDate(): HasOne
    {
        return $this->hasOne(PermissionDate::class, "permission_id", "id");
    }
}
