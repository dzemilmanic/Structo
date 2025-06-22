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
            $serviceRequests = ServiceRequest::whereHas('service', function ($query) use ($user) {
                $query->where('professional_id', $user->id);
            })->with('user', 'service')->latest()->get();
            
            return view('jobs.index', compact('services', 'jobRequests', 'assignedJobs', 'serviceRequests'));
        } else {
            $jobs = $user->jobs()->latest()->get();
            $serviceRequests = $user->serviceRequests()->with('service.professional')->latest()->get();
            $availableServices = Service::where('is_active', true)
                ->with('professional')
                ->latest()
                ->get();
            
            return view('jobs.index', compact('jobs', 'serviceRequests', 'availableServices'));
        }
    }

    public function create()
    {
        return view('jobs.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

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

    public function show(Job $job)
    {
        return view('jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        $user = Auth::user();
        
        if ($job->user_id !== $user->id) {
            return redirect()->route('jobs.index')->with('error', 'Nemate dozvolu za ovu akciju.');
        }
        
        return view('jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        $user = Auth::user();
        
        if ($job->user_id !== $user->id) {
            return redirect()->route('jobs.index')->with('error', 'Nemate dozvolu za ovu akciju.');
        }

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

        $job->update($validated);

        return redirect()->route('jobs.index')->with('success', 'Posao je uspešno ažuriran!');
    }

    public function destroy(Job $job)
    {
        $user = Auth::user();
        
        if ($job->user_id !== $user->id) {
            return redirect()->route('jobs.index')->with('error', 'Nemate dozvolu za ovu akciju.');
        }

        $job->delete();

        return redirect()->route('jobs.index')->with('success', 'Posao je uspešno obrisan!');
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

    public function rejectJobRequest(JobRequest $jobRequest)
    {
        $user = Auth::user();

        if ($jobRequest->job->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Nemate dozvolu za ovu akciju.');
        }

        $jobRequest->update(['status' => JobRequest::STATUS_REJECTED]);

        return redirect()->back()->with('success', 'Zahtev je odbijen!');
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