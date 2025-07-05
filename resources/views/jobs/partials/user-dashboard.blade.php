@vite('resources/js/jobs.js')
<section class="jobs-services-hero">
    <div class="container">
        <div class="jobs-services-hero-content">
            <h1>Jobs & Services</h1>
            <p>Find the perfect professional for your project or post your job</p>
        </div>
    </div>
</section>
<!-- User Dashboard -->
<div class="user-dashboard">
    <div class="dashboard-header">
        <h1>My Jobs</h1>
        <button class="btn btn-primary" onclick="openJobModal()">
            <i class="fas fa-plus"></i> Post New Job
        </button>
    </div>

    <!-- My Jobs Section -->
    <section class="dashboard-section">
        <h2>My Posted Jobs</h2>

        <!-- Results Header -->
        <div class="jobs-results-header">
            <h2>My Posted Jobs</h2>
            <p class="results-count">
                Showing {{ $jobs->firstItem() ?? 0 }}-{{ $jobs->lastItem() ?? 0 }} of {{ $jobs->total() }} jobs
            </p>
        </div>

        <div class="jobs-grid">
            @forelse($jobs as $job)
                <div class="job-card" title="Click to view details" data-full-description="{{ $job->description }}">
                    <div class="job-header">
                        <h3>{{ $job->title }}</h3>
                        <span class="job-status status-{{ $job->status }}">
                            {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                        </span>
                    </div>
                    <p class="job-description" data-full-description="{{ $job->description }}">{{ Str::limit($job->description, 100) }}</p>
                    <div class="job-details">
                        <div class="category">{{ ucfirst($job->category) }}</div>
                        @if($job->budget)
                            <div class="budget">
                                <i class="fas fa-dollar-sign"></i> ${{ number_format($job->budget, 0) }}
                            </div>
                        @endif
                        <div class="location">
                            <i class="fas fa-map-marker-alt"></i> {{ $job->location }}
                        </div>
                        @if($job->deadline)
                            <div class="deadline">
                                <i class="fas fa-calendar"></i> {{ $job->deadline->format('M d, Y') }}
                            </div>
                        @endif
                    </div>
                    @if($job->assignedProfessional)
                        <div class="assigned-professional">
                            <strong>Assigned to:</strong> 
                            <a href="{{ route('users.show', $job->assignedProfessional) }}" class="user-profile-link">{{ $job->assignedProfessional->name }}</a>
                        </div>
                    @endif
                    @if($job->status === 'open' && $job->requests && $job->requests->count() > 0)
                        <div class="job-requests">
                            <h4>Professional Requests ({{ $job->requests->count() }})</h4>
                            @foreach($job->requests as $request)
                                <div class="request-item">
                                    <div class="request-header">
                                        <strong>
                                            <a href="{{ route('users.show', $request->professional) }}" class="user-profile-link">{{ $request->professional->name }}</a>
                                        </strong>
                                        @if($request->professional->specialization)
                                            <span class="specialization">{{ $request->professional->specialization }}</span>
                                        @endif
                                    </div>
                                    <p class="request-message">{{ $request->message }}</p>
                                    @if($request->proposed_price)
                                        <p class="proposed-price">
                                            <strong>Proposed Price:</strong> ${{ number_format($request->proposed_price, 0) }}
                                        </p>
                                    @endif
                                    @if($request->status === 'pending')
                                        <div class="request-actions">
                                            <form action="{{ route('job-requests.accept', $request) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fas fa-check"></i> Accept
                                                </button>
                                            </form>
                                            <form action="{{ route('job-requests.reject', $request) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-times"></i> Decline
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="job-actions">
                        <button class="btn btn-secondary btn-sm" onclick="event.stopPropagation(); editJob({{ $job->id }})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" 
                                class="btn btn-danger btn-sm job-delete-btn"
                                data-job-id="{{ $job->id }}"
                                data-job-title="{{ $job->title }}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-briefcase"></i>
                    <p>You haven't posted any jobs yet</p>
                    <button class="btn btn-primary" onclick="openJobModal()">Post Your First Job</button>
                </div>
            @endforelse
        </div>

        <!-- Posted Jobs Pagination -->
        @if($jobs->hasPages())
            <div class="posted-jobs-pagination-container">
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
                        <a href="{{ $jobs->previousPageUrl() }}" class="pagination-btn prev-btn">
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
                                <a href="{{ $jobs->url($i) }}" class="page-number">{{ $i }}</a>
                            @endif
                        @endfor
                    </div>

                    {{-- Next Page Link --}}
                    @if ($jobs->hasMorePages())
                        <a href="{{ $jobs->nextPageUrl() }}" class="pagination-btn next-btn">
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
    </section>

    <!-- Available Services Section with Filters -->
    <section class="dashboard-section">
        <h2>Available Services</h2>
        
        <!-- Service Filters -->
        <div class="filters-section">
            <button class="btn btn-secondary btn-sm" onclick="toggleFilters('serviceFilters')">
                <i class="fas fa-filter"></i> Filters
            </button>
            <div id="serviceFilters" class="filters-container" style="display: none;">
                <form method="GET" action="{{ route('jobs.index') }}" class="filters-form">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="service_search">Search</label>
                            <input type="text" id="service_search" name="service_search" class="form-control" 
                                   placeholder="Search services..." value="{{ request('service_search') }}">
                        </div>
                        <div class="filter-group">
                            <label for="service_category">Category</label>
                            <select id="service_category" name="service_category" class="form-control">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" {{ request('service_category') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="service_area">Service Area</label>
                            <input type="text" id="service_area" name="service_area" class="form-control" 
                                   placeholder="Enter area..." value="{{ request('service_area') }}">
                        </div>
                        <div class="filter-group">
                            <label for="professional_name">Professional Name</label>
                            <input type="text" id="professional_name" name="professional_name" class="form-control" 
                                   placeholder="Professional name..." value="{{ request('professional_name') }}">
                        </div>
                    </div>
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="service_price_min">Min Price ($)</label>
                            <input type="number" id="service_price_min" name="service_price_min" class="form-control" 
                                   min="0" placeholder="0" value="{{ request('service_price_min') }}">
                        </div>
                        <div class="filter-group">
                            <label for="service_price_max">Max Price ($)</label>
                            <input type="number" id="service_price_max" name="service_price_max" class="form-control" 
                                   min="0" placeholder="No limit" value="{{ request('service_price_max') }}">
                        </div>
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-search"></i> Apply Filters
                        </button>
                        <a href="{{ route('jobs.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Header -->
        <div class="services-results-header">
            <h2>Available Services</h2>
            <p class="results-count">
                Showing {{ $availableServices->firstItem() ?? 0 }}-{{ $availableServices->lastItem() ?? 0 }} of {{ $availableServices->total() }} services
            </p>
        </div>

        <div class="services-grid">
            @forelse($availableServices as $service)
                <div class="service-card" title="Click to view details" data-full-description="{{ $service->description }}">
                    <div class="service-header">
                        <h3>{{ $service->title }}</h3>
                        <div class="professional-info">
                            <strong>
                                <a href="{{ route('users.show', $service->professional) }}" class="user-profile-link">{{ $service->professional->name }}</a>
                            </strong>
                            @if($service->professional->specialization)
                                <span class="specialization">{{ $service->professional->specialization }}</span>
                            @endif
                        </div>
                    </div>
                    <p class="service-description" data-full-description="{{ $service->description }}">{{ Str::limit($service->description, 100) }}</p>
                    <div class="service-details">
                        <span class="category">{{ ucfirst($service->category) }}</span>
                        @if($service->price_from || $service->price_to)
                            <span class="price">
                                <i class="fas fa-dollar-sign"></i>
                                @if($service->price_from && $service->price_to)
                                    ${{ number_format($service->price_from, 0) }} - ${{ number_format($service->price_to, 0) }}
                                @elseif($service->price_from)
                                    From ${{ number_format($service->price_from, 0) }}
                                @else
                                    Up to ${{ number_format($service->price_to, 0) }}
                                @endif
                            </span>
                        @endif
                    </div>
                    <div class="service-area">
                        <i class="fas fa-map-marker-alt"></i> {{ $service->service_area }}
                    </div>
                    <div class="service-actions">
                        <button class="btn btn-primary" onclick="event.stopPropagation(); requestService({{ $service->id }})">
                            <i class="fas fa-envelope"></i> Request Service
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <p>No services match your criteria</p>
                    @if(request()->hasAny(['service_search', 'service_category', 'service_area', 'professional_name', 'service_price_min', 'service_price_max']))
                        <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Clear Filters</a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Available Services Pagination -->
        @if($availableServices->hasPages())
            <div class="available-services-pagination-container">
                <div class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($availableServices->onFirstPage())
                        <button class="pagination-btn prev-btn" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15,18 9,12 15,6"></polyline>
                            </svg>
                            Previous
                        </button>
                    @else
                        <a href="{{ $availableServices->previousPageUrl() }}" class="pagination-btn prev-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15,18 9,12 15,6"></polyline>
                            </svg>
                            Previous
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    <div class="pagination-numbers">
                        @php
                            $start = max($availableServices->currentPage() - 2, 1);
                            $end = min($start + 4, $availableServices->lastPage());
                            $start = max($end - 4, 1);
                        @endphp

                        @for ($i = $start; $i <= $end; $i++)
                            @if ($i == $availableServices->currentPage())
                                <span class="page-number active">{{ $i }}</span>
                            @else
                                <a href="{{ $availableServices->url($i) }}" class="page-number">{{ $i }}</a>
                            @endif
                        @endfor
                    </div>

                    {{-- Next Page Link --}}
                    @if ($availableServices->hasMorePages())
                        <a href="{{ $availableServices->nextPageUrl() }}" class="pagination-btn next-btn">
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
    </section>

    <!-- My Service Requests Section -->
    <section class="dashboard-section">
        <h2>My Service Requests</h2>

        <!-- Results Header -->
        <div class="service-requests-results-header">
            <h2>My Service Requests</h2>
            <p class="results-count">
                Showing {{ $serviceRequests->firstItem() ?? 0 }}-{{ $serviceRequests->lastItem() ?? 0 }} of {{ $serviceRequests->total() }} requests
            </p>
        </div>

        <div class="requests-list">
            @forelse($serviceRequests as $request)
                <div class="request-card">
                    <div class="request-header">
                        <h3>{{ $request->service->title }}</h3>
                        <span class="request-status status-{{ $request->status }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </div>
                    <div class="professional-info">
                        <strong>Professional:</strong> 
                        <a href="{{ route('users.show', $request->service->professional) }}" class="user-profile-link">{{ $request->service->professional->name }}</a>
                    </div>
                    <p class="request-description">{{ $request->job_description }}</p>
                    <div class="request-details">
                        <div class="location">
                            <i class="fas fa-map-marker-alt"></i> {{ $request->location }}
                        </div>
                        @if($request->budget)
                            <div class="budget">
                                <i class="fas fa-dollar-sign"></i> ${{ number_format($request->budget, 0) }}
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No service requests</p>
                </div>
            @endforelse
        </div>

        <!-- Service Requests Pagination -->
        @if($serviceRequests->hasPages())
            <div class="service-requests-pagination-container">
                <div class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($serviceRequests->onFirstPage())
                        <button class="pagination-btn prev-btn" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15,18 9,12 15,6"></polyline>
                            </svg>
                            Previous
                        </button>
                    @else
                        <a href="{{ $serviceRequests->previousPageUrl() }}" class="pagination-btn prev-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15,18 9,12 15,6"></polyline>
                            </svg>
                            Previous
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    <div class="pagination-numbers">
                        @php
                            $start = max($serviceRequests->currentPage() - 2, 1);
                            $end = min($start + 4, $serviceRequests->lastPage());
                            $start = max($end - 4, 1);
                        @endphp

                        @for ($i = $start; $i <= $end; $i++)
                            @if ($i == $serviceRequests->currentPage())
                                <span class="page-number active">{{ $i }}</span>
                            @else
                                <a href="{{ $serviceRequests->url($i) }}" class="page-number">{{ $i }}</a>
                            @endif
                        @endfor
                    </div>

                    {{-- Next Page Link --}}
                    @if ($serviceRequests->hasMorePages())
                        <a href="{{ $serviceRequests->nextPageUrl() }}" class="pagination-btn next-btn">
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
    </section>
</div>