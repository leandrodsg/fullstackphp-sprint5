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
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'service_id' => $this->service_id,
            'plan' => $this->plan,
            'price' => $this->price,
            'currency' => $this->currency,
            'status' => $this->status,
            'next_billing_date' => $this->next_billing_date ? $this->next_billing_date->toISOString() : null,
            'created_at' => $this->created_at ? $this->created_at->toISOString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toISOString() : null,
            // Campos calculados
            'days_until_next_billing' => $this->next_billing_date ? now()->diffInDays($this->next_billing_date, false) : null,
            'is_expired' => $this->next_billing_date ? now()->isAfter($this->next_billing_date) : false,
            'price_with_currency' => $this->price . ' ' . $this->currency,
        ];
    }
}
