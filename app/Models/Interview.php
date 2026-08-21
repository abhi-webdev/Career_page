<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interview extends Model
{
    protected $fillable = [
        'application_id',
        'interview_date',
        'start_time',
        'end_time',
        'meeting_link',
        'notes',
        'status',
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

    public function application(): BelongsTo
    {
        return $this->belongsTo(
            Application::class,
            'application_id'
        );
    }
}