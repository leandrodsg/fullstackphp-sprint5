<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the subscription resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'service_id' => $this->service_id,
            'service_name' => $this->service->name ?? null,
            'plan' => $this->plan,
            'price' => $this->price,
            'currency' => $this->currency,
            'status' => $this->status,
            'next_billing_date' => $this->next_billing_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'days_until_next_billing' => $this->calculateDaysUntilNextBilling(),
            'is_expired' => $this->isExpired(),
            'price_with_currency' => $this->getPriceWithCurrency(),
            'billing_cycle' => $this->calculateBillingCycle(),
        ];
    }
}
