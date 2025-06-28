<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $services = Service::where('is_active', true)->with('professional')->latest()->paginate(15);
        
        return view('services.index', compact('services', 'categories'));
    }

    public function create()
    {
        $categories = ServiceCategory::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        return view('services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isProfi()) {
            return redirect()->route('jobs.index')->with('error', 'Only professionals can create services.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price_from' => 'nullable|numeric|min:0',
            'price_to' => 'nullable|numeric|min:0',
            'service_area' => 'required|string',
        ]);

        $validated['professional_id'] = $user->id;
        $validated['is_active'] = true;

        Service::create($validated);

        return redirect()->route('jobs.index')->with('success', 'Service created successfully!');
    }

    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        $user = Auth::user();
        
        if ($service->professional_id !== $user->id) {
            return redirect()->route('jobs.index')->with('error', 'You do not have permission for this action.');
        }

        $categories = ServiceCategory::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        return view('services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        $user = Auth::user();
        
        if ($service->professional_id !== $user->id) {
            return redirect()->route('jobs.index')->with('error', 'You do not have permission for this action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price_from' => 'nullable|numeric|min:0',
            'price_to' => 'nullable|numeric|min:0',
            'service_area' => 'required|string',
        ]);

        $service->update($validated);

        return redirect()->route('jobs.index')->with('success', 'Service updated successfully!');
    }

    public function destroy(Service $service)
    {
        $user = Auth::user();
        
        if ($service->professional_id !== $user->id) {
            return redirect()->route('jobs.index')->with('error', 'You do not have permission for this action.');
        }

        $service->delete();

        return redirect()->route('jobs.index')->with('success', 'Service deleted successfully!');
    }

    public function requestService(Request $request, Service $service)
    {
        $user = Auth::user();
        
        if ($user->isProfi()) {
            return redirect()->back()->with('error', 'Professionals cannot request services.');
        }

        // Check if user already sent request for this service
        $existingRequest = ServiceRequest::where('service_id', $service->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'You have already sent a request for this service.');
        }

        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        $validated['service_id'] = $service->id;
        $validated['user_id'] = $user->id;
        $validated['status'] = ServiceRequest::STATUS_PENDING;

        ServiceRequest::create($validated);

        return redirect()->back()->with('success', 'Service request sent successfully!');
    }

    public function acceptServiceRequest(ServiceRequest $serviceRequest)
    {
        $professional = Auth::user();
        
        if ($serviceRequest->service->professional_id !== $professional->id) {
            return redirect()->back()->with('error', 'You do not have permission for this action.');
        }

        $serviceRequest->update(['status' => ServiceRequest::STATUS_ACCEPTED]);

        return redirect()->back()->with('success', 'Service request accepted!');
    }

    public function rejectServiceRequest(ServiceRequest $serviceRequest)
    {
        $professional = Auth::user();
        
        if ($serviceRequest->service->professional_id !== $professional->id) {
            return redirect()->back()->with('error', 'You do not have permission for this action.');
        }

        $serviceRequest->update(['status' => ServiceRequest::STATUS_REJECTED]);

        return redirect()->back()->with('success', 'Service request rejected!');
    }
}