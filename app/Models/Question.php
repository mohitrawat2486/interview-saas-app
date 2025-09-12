<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'interview_id','prompt','order','time_limit_seconds','thinking_time_seconds','allow_retake'
    ];

    public function interview(): BelongsTo { return $this->belongsTo(Interview::class); }
    public function answers(): HasMany { return $this->hasMany(Answer::class); }
}
