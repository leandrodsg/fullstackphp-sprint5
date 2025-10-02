<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends BaseController
{
    public function index()
    {
        $subscriptions = Subscription::forUser()->get();
        return $this->responseSuccess(SubscriptionResource::collection($subscriptions), 'Subscription list');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_id' => 'required|integer|exists:services,id',
            'plan' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3|in:USD,EUR,BRL,GBP',
            'next_billing_date' => 'required|date|after:today',
            'status' => 'required|string|in:active,inactive,cancelled',
        ]);
        $data['user_id'] = Auth::id();
        
        // Definir billing_cycle automaticamente baseado no nome do plano
        $planName = strtolower($data['plan']);
        if (str_contains($planName, 'annual') || str_contains($planName, 'yearly')) {
            $data['billing_cycle'] = 'annual';
        } else {
            $data['billing_cycle'] = 'monthly';
        }
        
        $subscription = Subscription::create($data);
        return $this->responseSuccess(new SubscriptionResource($subscription), 'Subscription created', 201);
    }

    public function show($id)
    {
        $subscription = Subscription::forUser()->find($id);
        if (!$subscription) {
            return $this->responseError('Subscription not found', 404);
        }
        return $this->responseSuccess(new SubscriptionResource($subscription), 'Subscription details');
    }

    public function update(Request $request, $id)
    {
        $subscription = Subscription::forUser()->find($id);
        if (!$subscription) {
            return $this->responseError('Subscription not found', 404);
        }
        $data = $request->validate([
            'plan' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3|in:USD,EUR,BRL,GBP',
            'next_billing_date' => 'required|date|after:today',
            'status' => 'required|string|in:active,inactive,cancelled',
        ]);
        $subscription->update($data);
        return $this->responseSuccess(new SubscriptionResource($subscription), 'Subscription updated');
    }

    public function destroy($id)
    {
        $subscription = Subscription::forUser()->find($id);
        if (!$subscription) {
            return $this->responseError('Subscription not found', 404);
        }
        $subscription->delete();
        return $this->responseSuccess(null, 'Subscription deleted');
    }

    public function cancel($id)
    {
        $subscription = Subscription::forUser()->find($id);
        if (!$subscription) {
            return $this->responseError('Subscription not found', 404);
        }
        $subscription->update(['status' => 'cancelled']);
        return $this->responseSuccess(new SubscriptionResource($subscription), 'Subscription cancelled');
    }

    public function reactivate($id)
    {
        $subscription = Subscription::forUser()->find($id);
        if (!$subscription) {
            return $this->responseError('Subscription not found', 404);
        }
        
        // Atualizar status e recalcular next_billing_date baseado na data atual
        $nextBillingDate = now();
        if (stripos($subscription->plan, 'annual') !== false || stripos($subscription->plan, 'yearly') !== false) {
            $nextBillingDate = $nextBillingDate->addYear();
        } else {
            $nextBillingDate = $nextBillingDate->addMonth();
        }
        
        $subscription->update([
            'status' => 'active',
            'next_billing_date' => $nextBillingDate,
            'created_at' => now() // Atualiza para que calculateBillingCycle funcione corretamente
        ]);
        
        return $this->responseSuccess(new SubscriptionResource($subscription), 'Subscription reactivated');
    }
}
