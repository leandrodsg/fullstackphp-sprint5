@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Subscription</h1>
    
    <div class="bg-white p-6 rounded shadow">
        <form method="POST" action="{{ url('/subscriptions/' . $subscription->id) }}">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Service</label>
                <select name="service_id" required class="w-full p-2 border rounded">
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ $subscription->service_id == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Plan</label>
                <input type="text" name="plan" value="{{ $subscription->plan }}" required class="w-full p-2 border rounded" placeholder="e.g., Basic, Premium, Pro">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Price</label>
                <input type="number" name="price" value="{{ $subscription->price }}" step="0.01" required class="w-full p-2 border rounded" placeholder="0.00">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Currency</label>
                <select name="currency" required class="w-full p-2 border rounded">
                    <option value="USD" {{ $subscription->currency == 'USD' ? 'selected' : '' }}>USD</option>
                    <option value="EUR" {{ $subscription->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Next Billing Date</label>
                <input type="date" name="next_billing_date" value="{{ $subscription->next_billing_date ? \Carbon\Carbon::parse($subscription->next_billing_date)->format('Y-m-d') : '' }}" required class="w-full p-2 border rounded">
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Status</label>
                <select name="status" required class="w-full p-2 border rounded">
                    <option value="active" {{ $subscription->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="cancelled" {{ $subscription->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            
            <div class="flex space-x-4">
                <a href="{{ url('/subscriptions') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Update Subscription</button>
            </div>
        </form>
    </div>
</div>
@endsection