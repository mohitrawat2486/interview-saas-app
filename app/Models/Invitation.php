<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    protected $fillable = [
        'interview_id','candidate_email','token','expires_at','candidate_user_id','status'
    ];

    protected $casts = ['expires_at' => 'datetime'];

    public function interview(): BelongsTo { return $this->belongsTo(Interview::class); }
    public function candidate(): BelongsTo { return $this->belongsTo(User::class, 'candidate_user_id'); }
}
