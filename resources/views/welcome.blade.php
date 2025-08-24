@extends('layouts.app')

@section('content')
<div class="text-center">
    <h1 class="text-3xl font-bold mb-8">Welcome to TechSubs</h1>
    <p class="text-gray-600 mb-8">Manage your services and subscriptions</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto">
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Services</h2>
            <p class="text-gray-600 mb-4">Manage all your services</p>
            <a href="{{ url('/services') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Manage Services</a>
        </div>
        
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Subscriptions</h2>
            <p class="text-gray-600 mb-4">Track your subscriptions</p>
            <a href="{{ url('/subscriptions') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Manage Subscriptions</a>
        </div>
    </div>
</div>
@endsection