<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends BaseController
{
    public function index(Request $request)
    {
        $query = Service::forUser();
        
        // Filter by name
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        
        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        
        $services = $query->get();
        return $this->responseSuccess(ServiceResource::collection($services), 'Service list');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'website_url' => 'nullable|url',
        ]);
        
        $data['user_id'] = Auth::id();
        $service = Service::create($data);
        
        return $this->responseSuccess(new ServiceResource($service), 'Service created successfully', 201);
    }

    public function show($id)
    {
        $service = Service::forUser()->find($id);
        
        if (!$service) {
            return $this->responseError('Service not found', 404);
        }
        
        return $this->responseSuccess(new ServiceResource($service), 'Service details');
    }

    public function update(Request $request, $id)
    {
        $service = Service::forUser()->find($id);
        
        if (!$service) {
            return $this->responseError('Service not found', 404);
        }
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'website_url' => 'nullable|url',
        ]);
        
        $service->update($data);
        
        return $this->responseSuccess(new ServiceResource($service), 'Service updated successfully');
    }

    public function partialUpdate(Request $request, $id)
    {
        $service = Service::forUser()->find($id);
        
        if (!$service) {
            return $this->responseError('Service not found', 404);
        }
        
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:100',
            'description' => 'sometimes|nullable|string',
            'website_url' => 'sometimes|nullable|url',
        ]);
        
        $service->update($data);
        
        return $this->responseSuccess(new ServiceResource($service), 'Service updated successfully');
    }

    public function destroy($id)
    {
        $service = Service::forUser()->find($id);
        
        if (!$service) {
            return $this->responseError('Service not found', 404);
        }
        
        $service->delete();
        
        return $this->responseSuccess(null, 'Service deleted successfully');
    }

    public function categories()
    {
        $categories = Service::forUser()
            ->select('category')
            ->distinct()
            ->whereNotNull('category')
            ->pluck('category')
            ->sort()
            ->values();
            
        return $this->responseSuccess($categories, 'Categories list');
    }
}
