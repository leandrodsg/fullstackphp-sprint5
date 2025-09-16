<?php

namespace App\Listeners;

use App\Events\SubscriptionBillingAdvanced;
use Illuminate\Support\Facades\Log;

class LogSubscriptionBillingAdvance
{
    /**
     * Handle the event.
     */
    public function handle(SubscriptionBillingAdvanced $event): void
    {
        Log::info('Subscription billing advanced', [
            'subscription_id' => $event->subscription->id,
            'user_id' => $event->subscription->user_id,
            'service' => $event->subscription->service->name ?? 'Unknown',
            'new_billing_date' => $event->subscription->next_billing_date->format('Y-m-d')
        ]);
    }
}