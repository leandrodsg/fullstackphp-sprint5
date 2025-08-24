@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Service Details</h1>
        <div class="space-x-2">
            <a href="{{ url('/services/' . $service->id . '/edit') }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit</a>
            <a href="{{ url('/services') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Back</a>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded shadow">
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">ID</label>
            <p class="text-gray-900">{{ $service->id }}</p>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Service Name</label>
            <p class="text-gray-900">{{ $service->name }}</p>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Category</label>
            <p class="text-gray-900">{{ $service->category }}</p>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Description</label>
            <p class="text-gray-900">{{ $service->description ?? 'No description' }}</p>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Website</label>
            @if($service->website_url)
                <a href="{{ $service->website_url }}" target="_blank" class="text-blue-500 hover:text-blue-700">{{ $service->website_url }}</a>
            @else
                <p class="text-gray-900">No website provided</p>
            @endif
        </div>
    </div>
</div>
@endsection