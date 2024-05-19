<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'function_id',
        'scope_id',
        'create',
        'read',
        'update',
        'delete'
    ];

    public function User(): HasOne
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function FunctionModule(): HasOne
    {
        return $this->hasOne(FunctionModel::class, "id", "function_id");
    }

    public function Scope(): HasOne
    {
        return $this->hasOne(Scope::class, "id", "scope_id");
    }
}
