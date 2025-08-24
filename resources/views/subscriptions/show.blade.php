@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Subscription Details</h1>
        <div class="space-x-2">
            <a href="{{ url('/subscriptions/' . $subscription->id . '/edit') }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit</a>
            <a href="{{ url('/subscriptions') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Back</a>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded shadow">
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">ID</label>
            <p class="text-gray-900">{{ $subscription->id }}</p>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Service</label>
            <p class="text-gray-900">{{ $subscription->service->name }}</p>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Plan</label>
            <p class="text-gray-900">{{ $subscription->plan }}</p>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Price</label>
            <p class="text-gray-900">{{ $subscription->currency }} {{ number_format($subscription->price, 2) }}</p>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Next Billing Date</label>
            <p class="text-gray-900">{{ $subscription->next_billing_date ? \Carbon\Carbon::parse($subscription->next_billing_date)->format('d/m/Y') : 'N/A' }}</p>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Status</label>
            <span class="px-2 py-1 rounded text-sm {{ $subscription->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $subscription->status }}</span>
        </div>
    </div>
</div>
@endsection