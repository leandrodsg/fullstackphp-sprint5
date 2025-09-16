<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Requests\SubscriptionStoreRequest;
use App\Http\Requests\SubscriptionUpdateRequest;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index() 
    {
        $subscriptions = Subscription::with('service')
            ->forUser()
            ->get();
            
        return view('subscriptions.index', compact('subscriptions'));
    }

    public function create() 
    {
        $services = Service::forUser()->get();
        return view('subscriptions.create', compact('services'));
    }

    public function store(SubscriptionStoreRequest $request) 
    {
        $data = $request->validated();
        Subscription::create([
            'user_id' => Auth::id(),
            'service_id' => $data['service_id'],
            'plan' => $data['plan'],
            'price' => $data['price'],
            'currency' => $data['currency'],
            'next_billing_date' => $data['next_billing_date'],
            'status' => $data['status']
        ]);
        
        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription created successfully!');
    }

    public function show(string $id) 
    {
        $subscription = Subscription::with('service')
            ->forUser()
            ->findOrFail($id);
            
        return view('subscriptions.show', compact('subscription'));
    }

    public function edit(string $id) 
    {
        $subscription = Subscription::with('service')
            ->forUser()
            ->findOrFail($id);
            
        $services = Service::forUser()->get();
        
        return view('subscriptions.edit', compact('subscription', 'services'));
    }

    public function update(SubscriptionUpdateRequest $request, string $id) 
    {
        $data = $request->validated();
        $subscription = Subscription::forUser()->findOrFail($id);
        $subscription->update([
            'service_id' => $data['service_id'],
            'plan' => $data['plan'],
            'price' => $data['price'],
            'currency' => $data['currency'],
            'next_billing_date' => $data['next_billing_date'],
            'status' => $data['status']
        ]);
        
        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription updated successfully!');
    }

    public function destroy(string $id) 
    {
        Subscription::forUser()
            ->findOrFail($id)
            ->delete();
            
        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription deleted successfully!');
    }
}