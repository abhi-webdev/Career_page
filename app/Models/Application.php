<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    protected $fillable = [
        'user_id',
        'job_id',
        'resume_id',
        'status',
        'cover_letter',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(
            Job::class,
            'job_id'
        );
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(
            Resume::class,
            'resume_id'
        );
    }

    public function interview(): HasOne
{
    return $this->hasOne(
        Interview::class,
        'application_id'
    );
}

public function offer(): HasOne
{
    return $this->hasOne(
        Offer::class,
        'application_id'
    );
}
}