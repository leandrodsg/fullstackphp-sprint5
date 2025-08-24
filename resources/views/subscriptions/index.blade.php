@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Subscriptions</h1>
    <a href="{{ url('/subscriptions/create') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Add New Subscription</a>
</div>

<div class="bg-white rounded shadow">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Service</th>
                <th class="p-3 text-left">Plan</th>
                <th class="p-3 text-center">Price</th>
                <th class="p-3 text-center">Status</th>
                <th class="p-3 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscriptions as $subscription)
            <tr class="border-t">
                <td class="p-3">{{ $subscription->id }}</td>
                <td class="p-3 font-bold">{{ $subscription->service->name }}</td>
                <td class="p-3">{{ $subscription->plan }}</td>
                <td class="p-3 text-center font-semibold">{{ $subscription->currency }} {{ number_format($subscription->price, 2) }}</td>
                <td class="p-3 text-center">
                    <span class="px-2 py-1 rounded text-sm {{ $subscription->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $subscription->status }}</span>
                </td>
                <td class="p-3 text-center space-x-2">
                    <a href="{{ url('/subscriptions/' . $subscription->id) }}" class="text-blue-500 hover:text-blue-700">View</a>
                    <a href="{{ url('/subscriptions/' . $subscription->id . '/edit') }}" class="text-yellow-500 hover:text-yellow-700">Edit</a>
                    <form method="POST" action="{{ url('/subscriptions/' . $subscription->id) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection