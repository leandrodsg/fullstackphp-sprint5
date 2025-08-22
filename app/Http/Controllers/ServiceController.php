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
        Service::create($request->all());
        return redirect()->route('services.index');
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
        $service = Service::find($id);
        $service->update($request->all());
        return redirect()->route('services.index');
    }

    public function destroy(string $id) {
        Service::find($id)->delete();
        return redirect()->route('services.index');
    }
}
