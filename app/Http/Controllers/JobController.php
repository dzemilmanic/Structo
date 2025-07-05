<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobRequest;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $categories = ServiceCategory::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        // ADMIN CHECK - Multiple ways to check if user is admin
        if ($user->role === 'admin' || 
            (method_exists($user, 'isAdmin') && $user->isAdmin()) ||
            (method_exists($user, 'hasRole') && $user->hasRole('admin'))) {
            return $this->adminJobsPanel($request);
        }
        
        if ($user->isProfi()) {
            // Professional Dashboard
            $services = $user->services()->latest()->paginate(3);
            $jobRequests = $user->jobRequests()->with('job.user')->latest()->paginate(3);
            $assignedJobs = $user->assignedJobs()->with('user')->latest()->paginate(3);
            $serviceRequests = ServiceRequest::whereHas('service', function ($query) use ($user) {
                $query->where('professional_id', $user->id);
            })->with('user', 'service')->latest()->paginate(3);
            
            // Available jobs for professionals with filters
            $availableJobsQuery = Job::where('status', Job::STATUS_OPEN)
                ->where('assigned_professional_id', null)
                ->whereDoesntHave('requests', function ($query) use ($user) {
                    $query->where('professional_id', $user->id);
                })
                ->with('user');

            // Apply filters for available jobs
            if ($request->filled('job_category')) {
                $availableJobsQuery->where('category', $request->job_category);
            }

            if ($request->filled('job_budget_min')) {
                $availableJobsQuery->where('budget', '>=', $request->job_budget_min);
            }

            if ($request->filled('job_budget_max')) {
                $availableJobsQuery->where('budget', '<=', $request->job_budget_max);
            }

            if ($request->filled('job_location')) {
                $availableJobsQuery->where('location', 'LIKE', '%' . $request->job_location . '%');
            }

            if ($request->filled('job_deadline_from')) {
                $availableJobsQuery->where('deadline', '>=', $request->job_deadline_from);
            }

            if ($request->filled('job_deadline_to')) {
                $availableJobsQuery->where('deadline', '<=', $request->job_deadline_to);
            }

            if ($request->filled('job_search')) {
                $availableJobsQuery->where(function($query) use ($request) {
                    $query->where('title', 'LIKE', '%' . $request->job_search . '%')
                          ->orWhere('description', 'LIKE', '%' . $request->job_search . '%');
                });
            }

            $availableJobs = $availableJobsQuery->latest()->paginate(3);
            
            return view('jobs.index', compact('services', 'jobRequests', 'assignedJobs', 'serviceRequests', 'availableJobs', 'categories'));
        } else {
            // Regular User Dashboard
            $jobs = $user->jobs()->with('requests.professional', 'assignedProfessional')->latest()->paginate(3);
            $serviceRequests = $user->serviceRequests()->with('service.professional')->latest()->paginate(3);
            
            // Available services with filters
            $availableServicesQuery = Service::where('is_active', true)->with('professional');

            // Apply filters for available services
            if ($request->filled('service_category')) {
                $availableServicesQuery->where('category', $request->service_category);
            }

            if ($request->filled('service_price_min')) {
                $availableServicesQuery->where(function($query) use ($request) {
                    $query->where('price_from', '>=', $request->service_price_min)
                          ->orWhere('price_to', '>=', $request->service_price_min);
                });
            }

            if ($request->filled('service_price_max')) {
                $availableServicesQuery->where(function($query) use ($request) {
                    $query->where('price_from', '<=', $request->service_price_max)
                          ->orWhere('price_to', '<=', $request->service_price_max);
                });
            }

            if ($request->filled('service_area')) {
                $availableServicesQuery->where('service_area', 'LIKE', '%' . $request->service_area . '%');
            }

            if ($request->filled('service_search')) {
                $availableServicesQuery->where(function($query) use ($request) {
                    $query->where('title', 'LIKE', '%' . $request->service_search . '%')
                          ->orWhere('description', 'LIKE', '%' . $request->service_search . '%');
                });
            }

            if ($request->filled('professional_name')) {
                $availableServicesQuery->whereHas('professional', function($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->professional_name . '%');
                });
            }

            $availableServices = $availableServicesQuery->latest()->paginate(3);
            
            return view('jobs.index', compact('jobs', 'serviceRequests', 'availableServices', 'categories'));
        }
    }

    public function adminJobsPanel(Request $request)
    {
        // Get all categories for filtering and management
        $categories = ServiceCategory::orderBy('sort_order')->orderBy('name')->get();
        
        // Jobs filtering
        $jobsQuery = Job::with(['user', 'assignedProfessional', 'requests']);
        
        if ($request->filled('job_status')) {
            $jobsQuery->where('status', $request->job_status);
        }
        
        if ($request->filled('job_category')) {
            $jobsQuery->where('category', $request->job_category);
        }
        
        if ($request->filled('job_search')) {
            $jobsQuery->where(function($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->job_search . '%')
                  ->orWhere('description', 'LIKE', '%' . $request->job_search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'LIKE', '%' . $request->job_search . '%');
                  });
            });
        }
        
        $jobs = $jobsQuery->latest()->paginate(15);
        
        // Services filtering
        $servicesQuery = Service::with('professional');
        
        if ($request->filled('service_category')) {
            $servicesQuery->where('category', $request->service_category);
        }
        
        if ($request->filled('service_status')) {
            $servicesQuery->where('is_active', $request->service_status);
        }
        
        if ($request->filled('service_search')) {
            $servicesQuery->where(function($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->service_search . '%')
                  ->orWhere('description', 'LIKE', '%' . $request->service_search . '%')
                  ->orWhereHas('professional', function($profQuery) use ($request) {
                      $profQuery->where('name', 'LIKE', '%' . $request->service_search . '%');
                  });
            });
        }
        
        $services = $servicesQuery->latest()->paginate(15);
        
        return view('admin.jobs.index', compact('categories', 'jobs', 'services'));
    }

    public function create()
    {
        $categories = ServiceCategory::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        return view('jobs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'budget' => 'nullable|numeric|min:0',
            'location' => 'required|string',
            'deadline' => 'nullable|date|after:today',
        ]);

        $validated['user_id'] = $user->id;
        $validated['status'] = Job::STATUS_OPEN;
        $validated['latitude'] = null;
        $validated['longitude'] = null;

        Job::create($validated);

        return redirect()->route('jobs.index')->with('success', 'Job posted successfully!');
    }

    public function show(Job $job)
    {
        return view('jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        $user = Auth::user();
        
        if ($job->user_id !== $user->id) {
            return redirect()->route('jobs.index')->with('error', 'You do not have permission for this action.');
        }

        $categories = ServiceCategory::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        return view('jobs.edit', compact('job', 'categories'));
    }

    public function update(Request $request, Job $job)
    {
        $user = Auth::user();
        
        if ($job->user_id !== $user->id) {
            return redirect()->route('jobs.index')->with('error', 'You do not have permission for this action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'budget' => 'nullable|numeric|min:0',
            'location' => 'required|string',
            'deadline' => 'nullable|date|after:today',
        ]);

        $job->update($validated);

        return redirect()->route('jobs.index')->with('success', 'Job updated successfully!');
    }

    public function destroy(Job $job)
    {
        $user = Auth::user();
        
        if ($job->user_id !== $user->id) {
            return redirect()->route('jobs.index')->with('error', 'You do not have permission for this action.');
        }

        $job->delete();

        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully!');
    }

    public function requestJob(Request $request, Job $job)
    {
        $professional = Auth::user();
        
        if (!$professional->isProfi()) {
            return redirect()->back()->with('error', 'You do not have permission for this action.');
        }

        // Check if professional already sent request for this job
        $existingRequest = JobRequest::where('job_id', $job->id)
            ->where('professional_id', $professional->id)
            ->first();

        if ($existingRequest) {
            return redirect()->back()->with('error', 'You have already sent a request for this job.');
        }

        $validated = $request->validate([
            'message' => 'required|string',
            'proposed_price' => 'nullable|numeric|min:0',
        ]);

        $validated['job_id'] = $job->id;
        $validated['professional_id'] = $professional->id;
        $validated['status'] = JobRequest::STATUS_PENDING;

        JobRequest::create($validated);

        return redirect()->back()->with('success', 'Request sent successfully!');
    }

    public function acceptJobRequest(JobRequest $jobRequest)
    {
        $user = Auth::user();
        
        if ($jobRequest->job->user_id !== $user->id) {
            return redirect()->back()->with('error', 'You do not have permission for this action.');
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

        return redirect()->back()->with('success', 'Request accepted!');
    }

    public function rejectJobRequest(JobRequest $jobRequest)
    {
        $user = Auth::user();
        
        if ($jobRequest->job->user_id !== $user->id) {
            return redirect()->back()->with('error', 'You do not have permission for this action.');
        }

        $jobRequest->update(['status' => JobRequest::STATUS_REJECTED]);

        return redirect()->back()->with('success', 'Request rejected!');
    }

    public function completeJob(Job $job)
    {
        $professional = Auth::user();
        
        if ($job->assigned_professional_id !== $professional->id) {
            return redirect()->back()->with('error', 'You do not have permission for this action.');
        }

        $job->update(['status' => Job::STATUS_COMPLETED]);

        return redirect()->back()->with('success', 'Job marked as completed!');
    }

    // Admin-only methods
    public function adminDestroyJob(Job $job)
    {
        $user = Auth::user();
        
        if ($user->role !== 'admin' && 
            !(method_exists($user, 'isAdmin') && $user->isAdmin()) &&
            !(method_exists($user, 'hasRole') && $user->hasRole('admin'))) {
            return redirect()->route('jobs.index')->with('error', 'Unauthorized access.');
        }

        try {
            $job->delete();
            return redirect()->back()->with('success', 'Job deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting job: ' . $e->getMessage());
        }
    }

    public function adminDestroyService(Service $service)
    {
        $user = Auth::user();
        
        if ($user->role !== 'admin' && 
            !(method_exists($user, 'isAdmin') && $user->isAdmin()) &&
            !(method_exists($user, 'hasRole') && $user->hasRole('admin'))) {
            return redirect()->route('jobs.index')->with('error', 'Unauthorized access.');
        }

        try {
            $service->delete();
            return redirect()->back()->with('success', 'Service deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting service: ' . $e->getMessage());
        }
    }
}