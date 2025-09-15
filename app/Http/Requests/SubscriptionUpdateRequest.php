<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id' => 'required|exists:services,id',
            'plan' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'next_billing_date' => 'required|date',
            'status' => 'required|in:active,cancelled'
        ];
    }
}
