<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewItem extends Model
{
    protected $fillable = ['review_id','question_id','score','comment'];

    public function review(): BelongsTo { return $this->belongsTo(Review::class); }
    public function question(): BelongsTo { return $this->belongsTo(Question::class); }
}
