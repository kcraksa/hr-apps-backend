<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'evaluation_type',
        'value',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
