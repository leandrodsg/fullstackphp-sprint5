<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index() {
        $userServices = Service::where('user_id', Auth::id())->get();
        return view('services.index', ['services' => $userServices]);
    }

    public function create() {
        return view('services.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'website_url' => 'nullable|url'
        ]);

        Service::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'website_url' => $validated['website_url'],
            'user_id' => Auth::id()
        ]);

        return redirect()->route('services.index')->with('success', 'Service created successfully!');
    }

    public function show(string $id){
        $service = Service::where('user_id', Auth::id())->findOrFail($id);
        return view('services.show', ['service' => $service]);
    }

    public function edit(string $id){
        $service = Service::where('user_id', Auth::id())->findOrFail($id);
        return view('services.edit', ['service' => $service]);
    }

    public function update(Request $request, string $id)  {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'website_url' => 'nullable|url'
        ]);

        $service = Service::where('user_id', Auth::id())->findOrFail($id);

        $service->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'website_url' => $validated['website_url']
        ]);

        return redirect()->route('services.index')->with('success', 'Service updated successfully!');
    }

    public function destroy(string $id) {
        $service = Service::where('user_id', Auth::id())->findOrFail($id);
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service deleted successfully!');
    }
}
