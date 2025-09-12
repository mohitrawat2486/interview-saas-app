<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    protected $fillable = ['interview_id','candidate_id','started_at','submitted_at','status'];
    protected $casts = ['started_at'=>'datetime','submitted_at'=>'datetime'];

    public function interview(): BelongsTo { return $this->belongsTo(Interview::class); }
    public function candidate(): BelongsTo { return $this->belongsTo(User::class, 'candidate_id'); }
    public function answers(): HasMany { return $this->hasMany(Answer::class); }
    public function reviews(): HasMany { return $this->hasMany(Review::class); }
}
