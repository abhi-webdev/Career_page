<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    protected $fillable = [
        'title',
        'company',
        'description',
        'skills',
        'location',
        'job_type',
        'experience',
        'apply_url',
        'application_start',
        'application_deadline',
        'screening_date',
        'interview_start',
        'interview_end',
        'result_date',
    ];

    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'application_start' => 'datetime',
            'application_deadline' => 'datetime',
            'screening_date' => 'datetime',
            'interview_start' => 'datetime',
            'interview_end' => 'datetime',
            'result_date' => 'datetime',
        ];
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'job_id');
    }
}