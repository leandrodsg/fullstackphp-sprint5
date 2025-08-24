@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Services</h1>
    <a href="{{ url('/services/create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add New Service</a>
</div>

<div class="bg-white rounded shadow">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-left">Category</th>
                <th class="p-3 text-left">Description</th>
                <th class="p-3 text-center">Website</th>
                <th class="p-3 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
            <tr class="border-t">
                <td class="p-3">{{ $service->id }}</td>
                <td class="p-3 font-bold">{{ $service->name }}</td>
                <td class="p-3">{{ $service->category }}</td>
                <td class="p-3">{{ $service->description }}</td>
                <td class="p-3 text-center">
                    @if($service->website_url)
                        <a href="{{ $service->website_url }}" target="_blank" class="text-blue-500 hover:text-blue-700 underline">{{ $service->website_url }}</a>
                    @else
                        -
                    @endif
                </td>
                <td class="p-3 text-center space-x-2">
                    <a href="{{ url('/services/' . $service->id) }}" class="text-blue-500 hover:text-blue-700">View</a>
                    <a href="{{ url('/services/' . $service->id . '/edit') }}" class="text-yellow-500 hover:text-yellow-700">Edit</a>
                    <form method="POST" action="{{ url('/services/' . $service->id) }}" class="inline">
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