@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Service</h1>
    
    <div class="bg-white p-6 rounded shadow">
        <form method="POST" action="{{ url('/services/' . $service->id) }}">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Service Name</label>
                <input type="text" name="name" value="{{ $service->name }}" required class="w-full p-2 border rounded">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Category</label>
                <select name="category" required class="w-full p-2 border rounded">
                    <option value="Streaming" {{ $service->category == 'Streaming' ? 'selected' : '' }}>Streaming</option>
                    <option value="Software" {{ $service->category == 'Software' ? 'selected' : '' }}>Software</option>
                    <option value="Cloud Storage" {{ $service->category == 'Cloud Storage' ? 'selected' : '' }}>Cloud Storage</option>
                    <option value="Music" {{ $service->category == 'Music' ? 'selected' : '' }}>Music</option>
                    <option value="Gaming" {{ $service->category == 'Gaming' ? 'selected' : '' }}>Gaming</option>
                    <option value="Other" {{ $service->category == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full p-2 border rounded">{{ $service->description }}</textarea>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Website URL</label>
                <input type="url" name="website_url" value="{{ $service->website_url }}" class="w-full p-2 border rounded">
            </div>
            
            <div class="flex space-x-4">
                <a href="{{ url('/services') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Service</button>
            </div>
        </form>
    </div>
</div>
@endsection