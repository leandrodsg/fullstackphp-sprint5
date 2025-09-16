<?php

namespace App\Events;

use App\Models\Subscription;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscriptionBillingAdvanced
{
    use Dispatchable, SerializesModels;

    public Subscription $subscription;

    /**
     * Create a new event instance.
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }
}