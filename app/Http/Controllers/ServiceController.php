<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Requests\ServiceStoreRequest;
use App\Http\Requests\ServiceUpdateRequest;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index() {
        $userServices = Service::forUser()->get();
        return view('services.index', ['services' => $userServices]);
    }

    public function create() {
        return view('services.create');
    }

    public function store(ServiceStoreRequest $request) {
        $data = $request->validated();
        Service::create([
            'name' => $data['name'],
            'category' => $data['category'],
            'description' => $data['description'] ?? null,
            'website_url' => $data['website_url'] ?? null,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('services.index')->with('success', 'Service created successfully!');
    }

    public function show(string $id){
        $service = Service::forUser()->findOrFail($id);
        return view('services.show', ['service' => $service]);
    }

    public function edit(string $id){
        $service = Service::forUser()->findOrFail($id);
        return view('services.edit', ['service' => $service]);
    }

    public function update(ServiceUpdateRequest $request, string $id)  {
        $data = $request->validated();
        $service = Service::forUser()->findOrFail($id);
        $service->update([
            'name' => $data['name'],
            'category' => $data['category'],
            'description' => $data['description'] ?? null,
            'website_url' => $data['website_url'] ?? null
        ]);

        return redirect()->route('services.index')->with('success', 'Service updated successfully!');
    }

    public function destroy(string $id) {
        $service = Service::forUser()->findOrFail($id);
        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service deleted successfully!');
    }
}
