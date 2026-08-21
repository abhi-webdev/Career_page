<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfferVersion extends Model
{
    protected $fillable = [
        'offer_id',
        'version',
        'salary',
        'joining_date',
        'offer_expiry_date',
        'offer_letter_path',
        'signed_offer_letter_path',
        'signed_at',
        'decline_reason',
        'declined_at',
        'joining_date_note',
        'requested_joining_date',
        'joining_date_request_status',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'salary' => 'decimal:2',
            'joining_date' => 'date',
            'offer_expiry_date' => 'date',
            'requested_joining_date' => 'date',
            'signed_at' => 'datetime',
            'declined_at' => 'datetime',
        ];
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class, 'offer_id');
    }
}
