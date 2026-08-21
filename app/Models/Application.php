<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function interviews(): HasMany
    {
        return $this->hasMany(
            Interview::class,
            'application_id'
        );
    }

    public function hrInterview(): HasOne
    {
        return $this->hasOne(
            Interview::class,
            'application_id'
        )->where('type', 'hr');
    }

    public function technicalInterview(): HasOne
    {
        return $this->hasOne(
            Interview::class,
            'application_id'
        )->where('type', 'technical');
    }

    /**
     * Default / latest interview relationship for backwards compatibility.
     */
    public function interview(): HasOne
    {
        return $this->hasOne(
            Interview::class,
            'application_id'
        )->latestOfMany();
    }

    public function offer(): HasOne
    {
        return $this->hasOne(
            Offer::class,
            'application_id'
        );
    }

    public function employee(): HasOne
    {
        return $this->hasOne(
            Employee::class,
            'application_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Recruitment Workflow State Helpers
    |--------------------------------------------------------------------------
    */

    public function requiresTechnicalInterview(): bool
    {
        return $this->job ? (bool) $this->job->technical_interview_required : true;
    }

    public function hasHRInterviewPassed(): bool
    {
        $hr = $this->hrInterview;
        return $hr && $hr->status === 'completed' && $hr->result === 'passed';
    }

    public function hasTechnicalInterviewPassed(): bool
    {
        $tech = $this->technicalInterview;
        return $tech && $tech->status === 'completed' && $tech->result === 'passed';
    }

    public function canScheduleHRInterview(): bool
    {
        return in_array($this->status, ['shortlisted', 'hr_interview', 'interview']) && $this->status !== 'rejected';
    }

    public function canScheduleTechnicalInterview(): bool
    {
        return $this->requiresTechnicalInterview() &&
               $this->hasHRInterviewPassed() &&
               in_array($this->status, ['technical_interview', 'hr_passed', 'interview']) &&
               $this->status !== 'rejected';
    }
}