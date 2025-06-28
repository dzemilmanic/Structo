<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use App\Models\Job;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ServiceCategoryController extends Controller
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

    public function store(Request $request)
    {
        try {
            // Debug: Log incoming request data
            \Log::info('Category creation request:', $request->all());

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:service_categories,name',
                'description' => 'nullable|string|max:500',
                'is_active' => 'nullable'
            ]);

            // Manually handle the checkbox
            $categoryData = [
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'slug' => Str::slug($validated['name']),
                'is_active' => $request->has('is_active') ? 1 : 0,
                'sort_order' => (ServiceCategory::max('sort_order') ?? 0) + 1
            ];

            // Debug: Log data being inserted
            \Log::info('Category data to insert:', $categoryData);

            $category = ServiceCategory::create($categoryData);

            // Debug: Log created category
            \Log::info('Created category:', $category->toArray());

            return redirect()->route('admin.jobs.index')->with('success', 'Category "' . $category->name . '" created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error creating category:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Error creating category: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, ServiceCategory $category)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:service_categories,name,' . $category->id,
                'description' => 'nullable|string|max:500',
                'is_active' => 'nullable'
            ]);

            $updateData = [
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'slug' => Str::slug($validated['name']),
                'is_active' => $request->has('is_active') ? 1 : 0
            ];

            $category->update($updateData);

            return redirect()->route('admin.jobs.index')->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Error updating category:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error updating category: ' . $e->getMessage());
        }
    }

    public function destroy(ServiceCategory $category)
    {
        try {
            // Check if category is being used
            $jobsCount = Job::where('category', $category->slug)->count();
            $servicesCount = Service::where('category', $category->slug)->count();
            
            if ($jobsCount > 0 || $servicesCount > 0) {
                return redirect()->back()->with('error', 
                    "Cannot delete category. It's being used by {$jobsCount} jobs and {$servicesCount} services.");
            }

            $category->delete();
            return redirect()->route('admin.jobs.index')->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Error deleting category:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error deleting category: ' . $e->getMessage());
        }
    }
}