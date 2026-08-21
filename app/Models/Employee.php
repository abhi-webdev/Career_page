<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'application_id',
        'offer_id',
        'employee_code',
        'joining_date',
        'joined_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'joining_date' => 'date',
            'joined_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(
            Application::class,
            'application_id'
        );
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(
            Offer::class,
            'offer_id'
        );
    }

    /**
     * Safely generate a unique, sequential employee code.
     */
    public static function generateEmployeeCode(): string
    {
        $lastEmployee = static::orderBy('id', 'desc')->first();

        if (!$lastEmployee || !$lastEmployee->employee_code) {
            return 'EMP-0001';
        }

        if (preg_match('/EMP-(\d+)/', $lastEmployee->employee_code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
            return sprintf('EMP-%04d', $nextNumber);
        }

        return 'EMP-' . str_pad((string) (static::count() + 1), 4, '0', STR_PAD_LEFT);
    }
}
