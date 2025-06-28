@extends('layouts.app')
@vite(['resources/css/admin-jobs.css'])
@vite(['resources/css/jobs.css'])
@vite(['resources/js/admin-jobs.js'])
@section('title', 'Admin - Jobs & Services Management')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="admin-jobs-dashboard">
    <div class="admin-header">
        <h1><i class="fas fa-tools"></i> Jobs & Services Management</h1>
        <div class="admin-nav">
            <a href="{{ route('jobs.index') }}" class="admin-nav-link">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-list"></i>
            </div>
            <div class="stats-content">
                <h3>{{ $categories->count() }}</h3>
                <p>Total Categories</p>
                <small>{{ $categories->where('is_active', true)->count() }} active</small>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <div class="stats-content">
                <h3>{{ $jobs->total() }}</h3>
                <p>Total Jobs</p>
                <small>Posted by users</small>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-tools"></i>
            </div>
            <div class="stats-content">
                <h3>{{ $services->total() }}</h3>
                <p>Total Services</p>
                <small>From professionals</small>
            </div>
        </div>
    </div>

    <!-- Service Categories Management -->
    <div class="admin-section">
        <div class="section-header">
            <h2><i class="fas fa-tags"></i> Service Categories</h2>
            <button onclick="openCategoryModal()" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Category
            </button>
        </div>
        
        <div class="admin-content">
            <div class="categories-list">
                @forelse($categories as $category)
                    <div class="category-item">
                        <div class="category-info">
                            <div class="category-header">
                                <h3>{{ $category->name }}</h3>
                                <div class="category-meta">
                                    <span class="status status-{{ $category->is_active ? 'active' : 'inactive' }}">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            @if($category->description)
                                <p class="category-description">{{ $category->description }}</p>
                            @endif
                            <div class="category-stats">
                                <span><i class="fas fa-briefcase"></i> {{ \App\Models\Job::where('category', $category->slug)->count() }} jobs</span>
                                <span><i class="fas fa-tools"></i> {{ \App\Models\Service::where('category', $category->slug)->count() }} services</span>
                            </div>
                        </div>
                        <div class="category-actions">
                            <button onclick="editCategory({{ $category->id }})" class="btn btn-sm btn-secondary">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="deleteCategory({{ $category->id }})" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-tags"></i>
                        <p>No categories found</p>
                        <button onclick="openCategoryModal()" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add First Category
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Jobs Management -->
    <div class="admin-section">
        <div class="section-header">
            <h2><i class="fas fa-briefcase"></i> Jobs Management</h2>
            <button onclick="toggleFilters('jobFilters')" class="btn btn-secondary">
                <i class="fas fa-filter"></i> Filters
            </button>
        </div>
        
        <div id="jobFilters" class="filters-container" style="display: none;">
            <form method="GET" action="{{ route('admin.jobs.index') }}" class="filters-form">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="job_status">Status</label>
                        <select name="job_status" id="job_status" class="form-control">
                            <option value="">All Statuses</option>
                            <option value="open" {{ request('job_status') == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ request('job_status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('job_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('job_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="job_category">Category</label>
                        <select name="job_category" id="job_category" class="form-control">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('job_category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="job_search">Search</label>
                        <input type="text" name="job_search" id="job_search" class="form-control" 
                               placeholder="Search jobs..." value="{{ request('job_search') }}">
                    </div>
                </div>

                <div class="filter-actions">
                    <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">Clear</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>

        <div class="admin-content">
            <div class="jobs-list">
                @forelse($jobs as $job)
                    <div class="job-item">
                        <div class="job-info">
                            <div class="job-header">
                                <h3>{{ $job->title }}</h3>
                                <span class="status status-{{ str_replace(' ', '_', $job->status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                                </span>
                            </div>
                            <p class="job-description">{{ Str::limit($job->description, 150) }}</p>
                            <div class="job-meta">
                                <span><i class="fas fa-user"></i> {{ $job->user->name }}</span>
                                <span><i class="fas fa-tag"></i> {{ ucfirst($job->category) }}</span>
                                @if($job->budget)
                                    <span><i class="fas fa-dollar-sign"></i> ${{ number_format($job->budget) }}</span>
                                @endif
                                <span><i class="fas fa-map-marker-alt"></i> {{ $job->location }}</span>
                                <span><i class="fas fa-clock"></i> {{ $job->created_at->format('M d, Y') }}</span>
                            </div>
                            @if($job->assignedProfessional)
                                <div class="assigned-professional">
                                    <i class="fas fa-user-check"></i> Assigned to: {{ $job->assignedProfessional->name }}
                                </div>
                            @endif
                        </div>
                        <div class="job-actions">
                            <a href="{{ route('jobs.show', $job) }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <button onclick="deleteJob({{ $job->id }})" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-briefcase"></i>
                        <p>No jobs found</p>
                    </div>
                @endforelse
            </div>

            @if($jobs->hasPages())
                <div class="pagination-wrapper">
                    {{ $jobs->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Services Management -->
    <div class="admin-section">
        <div class="section-header">
            <h2><i class="fas fa-tools"></i> Services Management</h2>
            <button onclick="toggleFilters('serviceFilters')" class="btn btn-secondary">
                <i class="fas fa-filter"></i> Filters
            </button>
        </div>
        
        <div id="serviceFilters" class="filters-container" style="display: none;">
            <form method="GET" action="{{ route('admin.jobs.index') }}" class="filters-form">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="service_status">Status</label>
                        <select name="service_status" id="service_status" class="form-control">
                            <option value="">All Services</option>
                            <option value="1" {{ request('service_status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('service_status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="service_category">Category</label>
                        <select name="service_category" id="service_category" class="form-control">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('service_category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="service_search">Search</label>
                        <input type="text" name="service_search" id="service_search" class="form-control" 
                               placeholder="Search services..." value="{{ request('service_search') }}">
                    </div>
                </div>

                <div class="filter-actions">
                    <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">Clear</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>

        <div class="admin-content">
            <div class="services-list">
                @forelse($services as $service)
                    <div class="service-item">
                        <div class="service-info">
                            <div class="service-header">
                                <h3>{{ $service->title }}</h3>
                                <span class="status status-{{ $service->is_active ? 'active' : 'inactive' }}">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <p class="service-description">{{ Str::limit($service->description, 150) }}</p>
                            <div class="service-meta">
                                <span><i class="fas fa-user"></i> {{ $service->professional->name }}</span>
                                <span><i class="fas fa-tag"></i> {{ ucfirst($service->category) }}</span>
                                @if($service->price_from || $service->price_to)
                                    <span><i class="fas fa-dollar-sign"></i> 
                                        @if($service->price_from && $service->price_to)
                                            ${{ number_format($service->price_from) }} - ${{ number_format($service->price_to) }}
                                        @elseif($service->price_from)
                                            From ${{ number_format($service->price_from) }}
                                        @else
                                            Up to ${{ number_format($service->price_to) }}
                                        @endif
                                    </span>
                                @endif
                                <span><i class="fas fa-map-marker-alt"></i> {{ $service->service_area }}</span>
                                <span><i class="fas fa-clock"></i> {{ $service->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                        <div class="service-actions">
                            <a href="{{ route('services.show', $service) }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <button onclick="deleteService({{ $service->id }})" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-tools"></i>
                        <p>No services found</p>
                    </div>
                @endforelse
            </div>

            @if($services->hasPages())
                <div class="pagination-wrapper">
                    {{ $services->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Category Modal -->
<div id="categoryModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="categoryModalTitle">Add Category</h2>
            <button class="modal-close" onclick="closeCategoryModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="categoryForm" method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <input type="hidden" id="categoryMethod" name="_method" value="">
                
                <div class="form-group">
                    <label for="category_name">Category Name *</label>
                    <input type="text" id="category_name" name="name" required class="form-control"
                           placeholder="e.g. Interior Design">
                </div>

                <div class="form-group">
                    <label for="category_description">Description</label>
                    <textarea id="category_description" name="description" class="form-control" rows="3"
                              placeholder="Brief description of this category"></textarea>
                </div>

                <div class="form-group">
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="category_is_active" name="is_active" checked>
                            Active Category
                        </label>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" onclick="closeCategoryModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Pass categories data to JavaScript
        window.categoriesData = [
            @foreach($categories as $category)
                {
                    id: {{ $category->id }},
                    name: "{{ $category->name }}",
                    description: "{{ $category->description ?? '' }}",
                    is_active: {{ $category->is_active ? 'true' : 'false' }},
                    slug: "{{ $category->slug }}"
                }@if(!$loop->last),@endif
            @endforeach
        ];
    </script>
@endsection