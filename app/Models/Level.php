<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'levelA',
        'levelN',
        'levelE',
        'group',
        'health_balance',
        'meal_allowance',
        'transportation_fee',
        'status'
    ];
}
