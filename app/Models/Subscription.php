<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use App\Events\SubscriptionBillingAdvanced;

class Subscription extends Model
{
    use HasFactory;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Scope to filter subscriptions by user
     */
    public function scopeForUser($query, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        return $query->where('user_id', $userId);
    }

    /**
     * Check if the subscription is due (past the billing date)
     */
    public function isDue(): bool
    {
        return $this->next_billing_date < now()->toDate();
    }

    /**
     * Advance the billing date by one month
     */
    public function advanceBillingDate(): void
    {
        $this->update(['next_billing_date' => $this->next_billing_date->addMonth()]);
    }

    /**
     * Advance one billing cycle (monthly or annual based on plan)
     */
    public function advanceOneCycle(): void
    {
        // Detect if plan is annual or monthly
        if (stripos($this->plan, 'annual') !== false || stripos($this->plan, 'yearly') !== false) {
            $this->update(['next_billing_date' => $this->next_billing_date->addYear()]);
        } else {
            // Default to monthly for all other plans
            $this->advanceBillingDate();
        }
        
        SubscriptionBillingAdvanced::dispatch($this);
    }
}
