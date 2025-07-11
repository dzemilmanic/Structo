@extends('layouts.app')
@vite(['resources/css/admin-jobs.css'])
@vite(['resources/css/jobs.css'])
@vite(['resources/css/modal-details.css'])
@vite(['resources/js/admin-jobs.js'])
@section('title', 'Admin - Jobs & Services Management')
 
@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="admin-jobs-dashboard">
    <!-- Hidden session message data for JavaScript -->
    @if(session('success'))
        <div data-session-success="{{ session('success') }}" style="display: none;"></div>
    @endif
    @if(session('error'))
        <div data-session-error="{{ session('error') }}" style="display: none;"></div>
    @endif

    <div class="admin-header">
        <h1><i class="fas fa-tools"></i> Jobs & Services Management</h1>
        <div class="admin-nav">
            
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
                            <button type="button" 
                                    class="btn btn-sm btn-danger category-delete-btn"
                                    data-category-id="{{ $category->id }}"
                                    data-category-name="{{ $category->name }}">
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
                            <button onclick="openJobDetailModal({{ json_encode([
                                'title' => $job->title,
                                'description' => $job->description,
                                'status' => ucfirst(str_replace('_', ' ', $job->status)),
                                'clientName' => $job->user->name,
                                'category' => ucfirst($job->category),
                                'budget' => $job->budget ? '$' . number_format($job->budget) : 'Not specified',
                                'location' => $job->location,
                                'deadline' => $job->deadline ? $job->deadline->format('M d, Y') : 'Not specified',
                                'assignedProfessional' => $job->assignedProfessional ? $job->assignedProfessional->name : null
                            ]) }})" class="btn btn-sm btn-secondary">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-danger job-delete-btn"
                                    data-job-id="{{ $job->id }}"
                                    data-job-title="{{ $job->title }}">
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

            <!-- Q&A Style Pagination for Jobs -->
            @if($jobs->hasPages())
                <div class="qa-pagination-container">
                    <div class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($jobs->onFirstPage())
                            <button class="pagination-btn prev-btn" disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="15,18 9,12 15,6"></polyline>
                                </svg>
                                Previous
                            </button>
                        @else
                            <a href="{{ $jobs->appends(request()->query())->previousPageUrl() }}" class="pagination-btn prev-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="15,18 9,12 15,6"></polyline>
                                </svg>
                                Previous
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        <div class="pagination-numbers">
                            @php
                                $start = max($jobs->currentPage() - 2, 1);
                                $end = min($start + 4, $jobs->lastPage());
                                $start = max($end - 4, 1);
                            @endphp

                            @for ($i = $start; $i <= $end; $i++)
                                @if ($i == $jobs->currentPage())
                                    <span class="page-number active">{{ $i }}</span>
                                @else
                                    <a href="{{ $jobs->appends(request()->query())->url($i) }}" class="page-number">{{ $i }}</a>
                                @endif
                            @endfor
                        </div>

                        {{-- Next Page Link --}}
                        @if ($jobs->hasMorePages())
                            <a href="{{ $jobs->appends(request()->query())->nextPageUrl() }}" class="pagination-btn next-btn">
                                Next
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9,18 15,12 9,6"></polyline>
                                </svg>
                            </a>
                        @else
                            <button class="pagination-btn next-btn" disabled>
                                Next
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9,18 15,12 9,6"></polyline>
                                </svg>
                            </button>
                        @endif
                    </div>
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
                            <button onclick="openServiceDetailModal({{ json_encode([
                                'title' => $service->title,
                                'description' => $service->description,
                                'status' => $service->is_active ? 'Active' : 'Inactive',
                                'professionalName' => $service->professional->name,
                                'specialization' => $service->professional->specialization ?? 'Not specified',
                                'category' => ucfirst($service->category),
                                'price' => ($service->price_from && $service->price_to) 
                                    ? '$' . number_format($service->price_from) . ' - $' . number_format($service->price_to)
                                    : ($service->price_from 
                                        ? 'From $' . number_format($service->price_from)
                                        : ($service->price_to 
                                            ? 'Up to $' . number_format($service->price_to)
                                            : 'Price not specified')),
                                'serviceArea' => $service->service_area
                            ]) }})" class="btn btn-sm btn-secondary">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button type="button" 
                                    class="btn btn-sm btn-danger service-delete-btn"
                                    data-service-id="{{ $service->id }}"
                                    data-service-title="{{ $service->title }}">
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

            <!-- Q&A Style Pagination for Services -->
            @if($services->hasPages())
                <div class="qa-pagination-container">
                    <div class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($services->onFirstPage())
                            <button class="pagination-btn prev-btn" disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="15,18 9,12 15,6"></polyline>
                                </svg>
                                Previous
                            </button>
                        @else
                            <a href="{{ $services->appends(request()->query())->previousPageUrl() }}" class="pagination-btn prev-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="15,18 9,12 15,6"></polyline>
                                </svg>
                                Previous
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        <div class="pagination-numbers">
                            @php
                                $start = max($services->currentPage() - 2, 1);
                                $end = min($start + 4, $services->lastPage());
                                $start = max($end - 4, 1);
                            @endphp

                            @for ($i = $start; $i <= $end; $i++)
                                @if ($i == $services->currentPage())
                                    <span class="page-number active">{{ $i }}</span>
                                @else
                                    <a href="{{ $services->appends(request()->query())->url($i) }}" class="page-number">{{ $i }}</a>
                                @endif
                            @endfor
                        </div>

                        {{-- Next Page Link --}}
                        @if ($services->hasMorePages())
                            <a href="{{ $services->appends(request()->query())->nextPageUrl() }}" class="pagination-btn next-btn">
                                Next
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9,18 15,12 9,6"></polyline>
                                </svg>
                            </a>
                        @else
                            <button class="pagination-btn next-btn" disabled>
                                Next
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9,18 15,12 9,6"></polyline>
                                </svg>
                            </button>
                        @endif
                    </div>
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

<!-- Job Detail Modal -->
@include('jobs.partials.job-detail-modal')

<!-- Service Detail Modal -->
@include('jobs.partials.service-detail-modal')

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