<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    protected $fillable = [
        'submission_id','question_id','video_path','duration_seconds','retake_number','recorded_at'
    ];

    protected $casts = ['recorded_at'=>'datetime'];

    public function submission(): BelongsTo { return $this->belongsTo(Submission::class); }
    public function question(): BelongsTo { return $this->belongsTo(Question::class); }
}
