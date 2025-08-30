<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index() 
    {
        $subscriptions = Subscription::with('service')
            ->where('user_id', Auth::id())
            ->get();
            
        return view('subscriptions.index', compact('subscriptions'));
    }

    public function create() 
    {
        $services = Service::all();
        return view('subscriptions.create', compact('services'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'plan' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'next_billing_date' => 'required|date',
            'status' => 'required|in:active,cancelled'
        ]);
        
        Subscription::create([
            'user_id' => Auth::id(),
            'service_id' => $request->service_id,
            'plan' => $request->plan,
            'price' => $request->price,
            'currency' => $request->currency,
            'next_billing_date' => $request->next_billing_date,
            'status' => $request->status
        ]);
        
        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription created successfully!');
    }

    public function show(string $id) 
    {
        $subscription = Subscription::with('service')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('subscriptions.show', compact('subscription'));
    }

    public function edit(string $id) 
    {
        $subscription = Subscription::with('service')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        $services = Service::all();
        
        return view('subscriptions.edit', compact('subscription', 'services'));
    }

    public function update(Request $request, string $id) 
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'plan' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'next_billing_date' => 'required|date',
            'status' => 'required|in:active,cancelled'
        ]);
        
        $subscription = Subscription::where('user_id', Auth::id())
            ->findOrFail($id);
            
        $subscription->update([
            'service_id' => $request->service_id,
            'plan' => $request->plan,
            'price' => $request->price,
            'currency' => $request->currency,
            'next_billing_date' => $request->next_billing_date,
            'status' => $request->status
        ]);
        
        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription updated successfully!');
    }

    public function destroy(string $id) 
    {
        Subscription::where('user_id', Auth::id())
            ->findOrFail($id)
            ->delete();
            
        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription deleted successfully!');
    }
}