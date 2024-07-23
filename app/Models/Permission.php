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

    // relation to PermissionDocument
    public function PermissionDocument(): HasOne
    {
        return $this->hasOne(PermissionDocument::class, "permission_id", "id");
    }

    // create relation to user by supervisor_approval_by column
    public function SupervisorApproval(): HasOne
    {
        return $this->hasOne(User::class, "id", "supervisor_approval_by");
    }

    public function PersonaliaApproval(): HasOne
    {
        return $this->hasOne(User::class, "id", "personalia_approval_by");
    }

    public function FaApproval(): HasOne
    {
        return $this->hasOne(User::class, "id", "fa_approval_by");
    }

    // relation to user
    public function user(): HasOne
    {
        return $this->hasOne(User::class, "id", "user_id");
    }
}
