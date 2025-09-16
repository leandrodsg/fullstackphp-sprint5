<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'service_id',
        'plan',
        'price',
        'currency',
        'next_billing_date',
        'status'
    ];

    protected $casts = [
        'next_billing_date' => 'date',
        'price' => 'decimal:2'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo {
        return $this->belongsTo(Service::class);
    }

    /**
     * Scope to filter subscriptions by user
     */
    public function scopeForUser($query, $userId = null) {
        $userId = $userId ?? auth()->id();
        return $query->where('user_id', $userId);
    }

    /**
     * Check if the subscription is due (past the billing date)
     */
    public function isDue(): bool {
        return $this->next_billing_date < now()->toDate();
    }

    /**
     * Advance the billing date by one month
     */
    public function advanceBillingDate(): void {
        $this->update(['next_billing_date' => $this->next_billing_date->addMonth()]);
    }
}
