<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends BaseController
{
    public function index()
    {
        $subscriptions = Subscription::forUser()->get();
        return $this->responseSuccess($subscriptions, 'Subscription list');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_id' => 'required|integer',
            'plan' => 'required|string',
            'price' => 'required|numeric',
            'currency' => 'required|string',
            'start_date' => 'required|date',
            'status' => 'required|string',
        ]);
        $data['user_id'] = Auth::id();
        $subscription = Subscription::create($data);
        return $this->responseSuccess($subscription, 'Subscription created', 201);
    }

    public function show($id)
    {
        $subscription = Subscription::forUser()->find($id);
        if (!$subscription) {
            return $this->responseError('Subscription not found', 404);
        }
        return $this->responseSuccess($subscription, 'Subscription details');
    }

    public function update(Request $request, $id)
    {
        $subscription = Subscription::forUser()->find($id);
        if (!$subscription) {
            return $this->responseError('Subscription not found', 404);
        }
        $data = $request->validate([
            'plan' => 'required|string',
            'price' => 'required|numeric',
            'currency' => 'required|string',
            'start_date' => 'required|date',
            'status' => 'required|string',
        ]);
        $subscription->update($data);
        return $this->responseSuccess($subscription, 'Subscription updated');
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
}
