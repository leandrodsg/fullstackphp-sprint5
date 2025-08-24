@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-6">Add New Service</h1>
    
    <div class="bg-white p-6 rounded shadow">
        <form method="POST" action="{{ url('/services') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Service Name</label>
                <input type="text" name="name" required class="w-full p-2 border rounded">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Category</label>
                <select name="category" required class="w-full p-2 border rounded">
                    <option value="">Select Category</option>
                    <option value="Streaming">Streaming</option>
                    <option value="Software">Software</option>
                    <option value="Cloud Storage">Cloud Storage</option>
                    <option value="Music">Music</option>
                    <option value="Gaming">Gaming</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full p-2 border rounded"></textarea>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Website URL</label>
                <input type="url" name="website_url" placeholder="https://example.com" class="w-full p-2 border rounded">
            </div>
            
            <div class="flex space-x-4">
                <a href="{{ url('/services') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Create Service</button>
            </div>
        </form>
    </div>
</div>
@endsection