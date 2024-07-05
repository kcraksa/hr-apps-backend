<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date', 'type', 'category', 'amount', 'description',
        'is_approve_supervisor', 'approve_supervisor_by', 'approve_supervisor_at',
        'is_approve_personalia', 'approve_personalia_by', 'approve_personalia_at',
        'is_approve_fa', 'approve_fa_by', 'approve_fa_at', 'user_receiver_id'
    ];

    // create function for relation to User model, the relations is one to one and paired with user_id
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // create function for relation to Claim attachment model, the relations is one to many and paired with claim_id
    public function attachments()
    {
        return $this->hasOne(ClaimAttachment::class);
    }
}