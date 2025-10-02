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
        if (stripos($this->plan, 'annual') !== false || stripos($this->plan, 'yearly') !== false) {
            $this->update(['next_billing_date' => $this->next_billing_date->addYear()]);
        } else {
            $this->advanceBillingDate();
        }
        
        SubscriptionBillingAdvanced::dispatch($this);
    }

    /**
     * Calculate billing cycle based on the difference between created_at and next_billing_date
     */
    public function calculateBillingCycle(): string
    {
        if (!$this->created_at || !$this->next_billing_date) {
            return 'monthly';
        }

        $daysDifference = floor($this->created_at->diffInDays($this->next_billing_date));

        // Se a diferença for maior ou igual a 330 dias, consideramos anual
        if ($daysDifference >= 330) {
            return 'annual';
        }

        // Caso contrário, é mensal
        return 'monthly';
    }

    /**
     * Calculate days until next billing
     */
    public function calculateDaysUntilNextBilling(): ?int
    {
        if (!$this->next_billing_date) {
            return null;
        }

        return now()->diffInDays($this->next_billing_date, false);
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        if (!$this->next_billing_date) {
            return false;
        }

        return now()->isAfter($this->next_billing_date);
    }

    /**
     * Get formatted price with currency
     */
    public function getPriceWithCurrency(): string
    {
        return $this->price . ' ' . $this->currency;
    }
}
