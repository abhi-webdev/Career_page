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
    ];

    protected function casts(): array
    {
        return [
            'interview_date' => 'date',
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