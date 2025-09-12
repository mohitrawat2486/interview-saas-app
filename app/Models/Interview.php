<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Interview extends Model
{
    protected $fillable = ['title','description','settings','created_by'];
    protected $casts = ['settings' => 'array'];

    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function questions(): HasMany { return $this->hasMany(Question::class)->orderBy('order'); }
    public function invitations(): HasMany { return $this->hasMany(Invitation::class); }
    public function submissions(): HasMany { return $this->hasMany(Submission::class); }
}
