<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user() || Auth::user()->role !== 'admin') {
                return redirect()->route('jobs.index')->with('error', 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        // Get statistics
        $stats = [
            'total_categories' => ServiceCategory::count(),
            'active_categories' => ServiceCategory::where('is_active', true)->count(),
            'total_jobs' => Job::count(),
            'total_services' => Service::count(),
            'total_professionals' => User::where('role', 'profi')->count(),
            'total_users' => User::where('role', 'user')->count(),
        ];

        // Get recent activities
        $recent_jobs = Job::with('user')->latest()->take(5)->get();
        $recent_services = Service::with('professional')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_jobs', 'recent_services'));
    }

    public function jobs(Request $request)
    {
        $query = Job::with(['user', 'assignedProfessional', 'requests']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('description', 'LIKE', '%' . $request->search . '%')
                  ->orWhereHas('user', function($userQuery) use ($request) {
                      $userQuery->where('name', 'LIKE', '%' . $request->search . '%');
                  });
            });
        }

        $jobs = $query->latest()->paginate(15);
        $categories = ServiceCategory::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.jobs', compact('jobs', 'categories'));
    }

    public function services(Request $request)
    {
        $query = Service::with('professional');

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('description', 'LIKE', '%' . $request->search . '%')
                  ->orWhereHas('professional', function($profQuery) use ($request) {
                      $profQuery->where('name', 'LIKE', '%' . $request->search . '%');
                  });
            });
        }

        $services = $query->latest()->paginate(15);
        $categories = ServiceCategory::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.services', compact('services', 'categories'));
    }

    public function destroyJob(Job $job)
    {
        try {
            $job->delete();
            return redirect()->back()->with('success', 'Job deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting job: ' . $e->getMessage());
        }
    }

    public function destroyService(Service $service)
    {
        try {
            $service->delete();
            return redirect()->back()->with('success', 'Service deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting service: ' . $e->getMessage());
        }
    }
}