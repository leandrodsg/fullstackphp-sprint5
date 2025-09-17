<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends BaseController
{
    public function index()
    {
        $services = Service::forUser()->get();
        return $this->responseSuccess($services, 'Service list');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'website_url' => 'nullable|string',
        ]);
        $data['user_id'] = Auth::id();
        $service = Service::create($data);
        return $this->responseSuccess($service, 'Service created', 201);
    }

    public function show($id)
    {
        $service = Service::forUser()->find($id);
        if (!$service) {
            return $this->responseError('Service not found', 404);
        }
        return $this->responseSuccess($service, 'Service details');
    }

    public function update(Request $request, $id)
    {
        $service = Service::forUser()->find($id);
        if (!$service) {
            return $this->responseError('Service not found', 404);
        }
        $data = $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'website_url' => 'nullable|string',
        ]);
        $service->update($data);
        return $this->responseSuccess($service, 'Service updated');
    }

    public function destroy($id)
    {
        $service = Service::forUser()->find($id);
        if (!$service) {
            return $this->responseError('Service not found', 404);
        }
        $service->delete();
        return $this->responseSuccess(null, 'Service deleted');
    }
}
