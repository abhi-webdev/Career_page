<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Offer extends Model
{
    protected $fillable = [
        'application_id',
        'version',
        'salary',
        'joining_date',
        'offer_expiry_date',
        'offer_letter_path',
        'signed_offer_letter_path',
        'signed_at',
        'notes',
        'status',
        'decline_reason',
        'declined_at',
        'joining_date_note',
        'requested_joining_date',
        'joining_date_request_status',
        'joining_date_requested_at',
    ];

    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'salary' => 'decimal:2',
            'joining_date' => 'date',
            'offer_expiry_date' => 'date',
            'requested_joining_date' => 'date',
            'signed_at' => 'datetime',
            'declined_at' => 'datetime',
            'joining_date_requested_at' => 'datetime',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(
            Application::class,
            'application_id'
        );
    }

    public function versions(): HasMany
    {
        return $this->hasMany(
            OfferVersion::class,
            'offer_id'
        )->orderBy('version', 'desc');
    }

    public function employee(): HasOne
    {
        return $this->hasOne(
            Employee::class,
            'offer_id'
        );
    }

    /**
     * Snapshot the current state into offer_versions table.
     */
    public function snapshotVersion(?int $versionNumber = null, ?string $statusOverride = null): OfferVersion
    {
        $v = $versionNumber ?? $this->version ?? 1;

        return OfferVersion::updateOrCreate(
            [
                'offer_id' => $this->id,
                'version' => $v,
            ],
            [
                'salary' => $this->salary,
                'joining_date' => $this->joining_date,
                'offer_expiry_date' => $this->offer_expiry_date,
                'offer_letter_path' => $this->offer_letter_path,
                'signed_offer_letter_path' => $this->signed_offer_letter_path,
                'signed_at' => $this->signed_at,
                'decline_reason' => $this->decline_reason,
                'declined_at' => $this->declined_at,
                'joining_date_note' => $this->joining_date_note,
                'requested_joining_date' => $this->requested_joining_date,
                'joining_date_request_status' => $this->joining_date_request_status,
                'notes' => $this->notes,
                'status' => $statusOverride ?? $this->status,
            ]
        );
    }
}