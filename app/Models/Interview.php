<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interview extends Model
{
    protected $fillable = [
        'application_id',
        'type', // 'hr', 'technical'
        'interview_date',
        'start_time',
        'end_time',
        'meeting_link',
        'notes',
        'status', // 'scheduled', 'completed', 'cancelled', 'rescheduled'
        'result', // 'pending', 'passed', 'failed'
        'interviewer_id',
        'admin_feedback',
        'feedback_attachment_path',
        'candidate_feedback',
        'feedback_submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'interview_date' => 'date',
            'feedback_submitted_at' => 'datetime',
        ];
    }

    public function isHR(): bool
    {
        return $this->type === 'hr';
    }

    public function isTechnical(): bool
    {
        return $this->type === 'technical';
    }

    public function isPassed(): bool
    {
        return $this->result === 'passed';
    }

    public function isFailed(): bool
    {
        return $this->result === 'failed';
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(
            Application::class,
            'application_id'
        );
    }

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'interviewer_id'
        );
    }
}