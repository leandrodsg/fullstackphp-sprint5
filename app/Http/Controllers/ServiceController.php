<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index() {
        $services = Service::all();
        return view('services.index', compact('services'));
    }

    public function create() {
        return view('services.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'website_url' => 'nullable|url'
        ]);
        
        Service::create([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'website_url' => $request->website_url
        ]);
        return redirect()->route('services.index')->with('success', 'Service created successfully!');
    }

    public function show(string $id) {
        $service = Service::find($id);
        return view('services.show', compact('service'));
    }

    public function edit(string $id) {
        $service = Service::find($id);
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, string $id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'website_url' => 'nullable|url'
        ]);
        
        $service = Service::find($id);
        $service->update([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'website_url' => $request->website_url
        ]);
        return redirect()->route('services.index')->with('success', 'Service updated successfully!');
    }

    public function destroy(string $id) {
        Service::find($id)->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfully!');
    }
}
