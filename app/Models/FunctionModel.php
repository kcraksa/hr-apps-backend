<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FunctionModel extends Model
{
    use HasFactory;

    protected $table = "functions";

    protected $fillable = [
        'id_module', 'name', 'url'
    ];

    public function Role(): HasOne
    {
        return $this->hasOne(Role::class, "function_id", "id");
    }
}
