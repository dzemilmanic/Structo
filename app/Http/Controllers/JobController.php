<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobRequest;
use App\Models\Service;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isProfi()) {
            $services = $user->services()->latest()->get();
            $jobRequests = $user->jobRequests()->with('job.user')->latest()->get();
            $assignedJobs = $user->assignedJobs()->with('user')->latest()->get();
            
            return view('jobs.partials.professional-dashboard', compact('services', 'jobRequests', 'assignedJobs'));
        } else {
            $jobs = $user->jobs()->latest()->get();
            $serviceRequests = $user->serviceRequests()->with('service.professional')->latest()->get();
            $availableServices = Service::where('is_active', true)
                ->with('professional')
                ->latest()
                ->get();
            
            return view('jobs.partials.user-dashboard', compact('jobs', 'serviceRequests', 'availableServices'));
        }
    }

    public function create()
    {
        return view('jobs.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->isProfi()) {
            // Professional posting a service
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
        } else {
            // Regular user posting a job
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|string',
                'budget' => 'nullable|numeric|min:0',
                'location' => 'required|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'deadline' => 'nullable|date|after:today',
            ]);

            $validated['user_id'] = $user->id;
            $validated['status'] = Job::STATUS_OPEN;

            Job::create($validated);

            return redirect()->route('jobs.index')->with('success', 'Posao je uspešno kreiran!');
        }
    }

    public function requestJob(Request $request, Job $job)
    {
        $professional = Auth::user();

        if (!$professional->isProfi()) {
            return redirect()->back()->with('error', 'Nemate dozvolu za ovu akciju.');
        }

        $validated = $request->validate([
            'message' => 'required|string',
            'proposed_price' => 'nullable|numeric|min:0',
        ]);

        $validated['job_id'] = $job->id;
        $validated['professional_id'] = $professional->id;
        $validated['status'] = JobRequest::STATUS_PENDING;

        JobRequest::create($validated);

        return redirect()->back()->with('success', 'Zahtev je uspešno poslat!');
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

    public function acceptJobRequest(JobRequest $jobRequest)
    {
        $user = Auth::user();

        if ($jobRequest->job->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Nemate dozvolu za ovu akciju.');
        }

        $jobRequest->update(['status' => JobRequest::STATUS_ACCEPTED]);
        
        $job = $jobRequest->job;
        $job->update([
            'assigned_professional_id' => $jobRequest->professional_id,
            'status' => Job::STATUS_IN_PROGRESS
        ]);

        // Reject other pending requests for this job
        $job->requests()
            ->where('id', '!=', $jobRequest->id)
            ->where('status', JobRequest::STATUS_PENDING)
            ->update(['status' => JobRequest::STATUS_REJECTED]);

        return redirect()->back()->with('success', 'Zahtev je prihvaćen!');
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

    public function completeJob(Job $job)
    {
        $professional = Auth::user();

        if ($job->assigned_professional_id !== $professional->id) {
            return redirect()->back()->with('error', 'Nemate dozvolu za ovu akciju.');
        }

        $job->update(['status' => Job::STATUS_COMPLETED]);

        return redirect()->back()->with('success', 'Posao je označen kao završen!');
    }
}