<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id', 'attachment',
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}