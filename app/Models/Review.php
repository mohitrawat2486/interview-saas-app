<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    protected $fillable = ['submission_id','reviewer_id','overall_score','overall_comment'];

    public function submission(): BelongsTo { return $this->belongsTo(Submission::class); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewer_id'); }
    public function items(): HasMany { return $this->hasMany(ReviewItem::class); }
}
