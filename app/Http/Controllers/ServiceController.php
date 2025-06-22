<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isProfi()) {
            $services = $user->services()->latest()->get();
            return view('services.index', compact('services'));
        } else {
            $services = Service::where('is_active', true)
                ->with('professional')
                ->latest()
                ->get();
            return view('services.index', compact('services'));
        }
    }

    public function create()
    {
        $user = Auth::user();
        
        if (!$user->isProfi()) {
            return redirect()->route('jobs.index')->with('error', 'Nemate dozvolu za ovu akciju.');
        }
        
        return view('services.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isProfi()) {
            return redirect()->route('jobs.index')->with('error', 'Nemate dozvolu za ovu akciju.');
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

        return redirect()->route('jobs.index')->with('success', 'Usluga je uspešno kreirana!');
    }

    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        $user = Auth::user();
        
        if ($service->professional_id !== $user->id) {
            return redirect()->route('jobs.index')->with('error', 'Nemate dozvolu za ovu akciju.');
        }
        
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $user = Auth::user();
        
        if ($service->professional_id !== $user->id) {
            return redirect()->route('jobs.index')->with('error', 'Nemate dozvolu za ovu akciju.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'price_from' => 'nullable|numeric|min:0',
            'price_to' => 'nullable|numeric|min:0',
            'service_area' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $service->update($validated);

        return redirect()->route('jobs.index')->with('success', 'Usluga je uspešno ažurirana!');
    }

    public function destroy(Service $service)
    {
        $user = Auth::user();
        
        if ($service->professional_id !== $user->id) {
            return redirect()->route('jobs.index')->with('error', 'Nemate dozvolu za ovu akciju.');
        }

        $service->delete();

        return redirect()->route('jobs.index')->with('success', 'Usluga je uspešno obrisana!');
    }

    public function requestService(Request $request, Service $service)
    {
        $user = Auth::user();

        if (!$user->isUser()) {
            return redirect()->back()->with('error', 'Nemate dozvolu za ovu akciju.');
        }

        $validated = $request->validate([
            'message' => 'required|string',
            'job_description' => 'required|string',
            'budget' => 'nullable|numeric|min:0',
            'location' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $validated['service_id'] = $service->id;
        $validated['user_id'] = $user->id;
        $validated['status'] = ServiceRequest::STATUS_PENDING;

        ServiceRequest::create($validated);

        return redirect()->back()->with('success', 'Zahtev je uspešno poslat!');
    }

    public function acceptServiceRequest(ServiceRequest $serviceRequest)
    {
        $professional = Auth::user();

        if ($serviceRequest->service->professional_id !== $professional->id) {
            return redirect()->back()->with('error', 'Nemate dozvolu za ovu akciju.');
        }

        $serviceRequest->update(['status' => ServiceRequest::STATUS_ACCEPTED]);

        return redirect()->back()->with('success', 'Zahtev je prihvaćen!');
    }

    public function rejectServiceRequest(ServiceRequest $serviceRequest)
    {
        $professional = Auth::user();

        if ($serviceRequest->service->professional_id !== $professional->id) {
            return redirect()->back()->with('error', 'Nemate dozvolu za ovu akciju.');
        }

        $serviceRequest->update(['status' => ServiceRequest::STATUS_REJECTED]);

        return redirect()->back()->with('success', 'Zahtev je odbijen!');
    }
}