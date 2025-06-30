@vite('resources/js/jobs.js')
@vite('resources/js/delete-confirmation.js')
<section class="jobs-services-hero">
    <div class="container">
        <div class="jobs-services-hero-content">
            <h1>Professional Dashboard</h1>
            <p>Manage your services, find new opportunities, and grow your business</p>
        </div>
    </div>
</section>
<!-- Professional Dashboard -->
<div class="professional-dashboard">
    <div class="dashboard-header">
        <h1>Professional Dashboard</h1>
        <button class="btn btn-primary" onclick="openServiceModal()">
            <i class="fas fa-plus"></i> Add New Service
        </button>
    </div>

    <!-- Available Jobs Section with Filters -->
    <section class="dashboard-section">
        <h2>Available Jobs</h2>
        
        <!-- Job Filters -->
        <div class="filters-section">
            <button class="btn btn-secondary btn-sm" onclick="toggleFilters('jobFilters')">
                <i class="fas fa-filter"></i> Filters
            </button>
            <div id="jobFilters" class="filters-container" style="display: none;">
                <form method="GET" action="{{ route('jobs.index') }}" class="filters-form">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="job_search">Search</label>
                            <input type="text" id="job_search" name="job_search" class="form-control" 
                                   placeholder="Search jobs..." value="{{ request('job_search') }}">
                        </div>
                        <div class="filter-group">
                            <label for="job_category">Category</label>
                            <select id="job_category" name="job_category" class="form-control">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" {{ request('job_category') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="job_location">Location</label>
                            <input type="text" id="job_location" name="job_location" class="form-control" 
                                   placeholder="Enter location..." value="{{ request('job_location') }}">
                        </div>
                    </div>
                    <div class="filter-row">
                        <div class="filter-group">
                            <label for="job_budget_min">Min Budget ($)</label>
                            <input type="number" id="job_budget_min" name="job_budget_min" class="form-control" 
                                   min="0" placeholder="0" value="{{ request('job_budget_min') }}">
                        </div>
                        <div class="filter-group">
                            <label for="job_budget_max">Max Budget ($)</label>
                            <input type="number" id="job_budget_max" name="job_budget_max" class="form-control" 
                                   min="0" placeholder="No limit" value="{{ request('job_budget_max') }}">
                        </div>
                        <div class="filter-group">
                            <label for="job_deadline_from">Deadline From</label>
                            <input type="date" id="job_deadline_from" name="job_deadline_from" class="form-control" 
                                   value="{{ request('job_deadline_from') }}">
                        </div>
                        <div class="filter-group">
                            <label for="job_deadline_to">Deadline To</label>
                            <input type="date" id="job_deadline_to" name="job_deadline_to" class="form-control" 
                                   value="{{ request('job_deadline_to') }}">
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

        <div class="jobs-grid">
            @forelse($availableJobs as $job)
                <div class="job-card" title="Click to view details" data-full-description="{{ $job->description }}">
                    <div class="job-header">
                        <h3>{{ $job->title }}</h3>
                        <span class="job-status status-{{ $job->status }}">
                            {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                        </span>
                    </div>
                    <p class="job-description" data-full-description="{{ $job->description }}">{{ Str::limit($job->description, 150) }}</p>
                    <div class="job-details">
                        <div class="client-info">
                            <strong>Client:</strong> 
                            <a href="{{ route('users.show', $job->user) }}" class="user-profile-link">{{ $job->user->name }}</a>
                        </div>
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
                    <div class="job-actions">
                        <button class="btn btn-primary" onclick="event.stopPropagation(); openJobRequestModal({{ $job->id }})">
                            <i class="fas fa-envelope"></i> Send Request
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <p>No available jobs match your criteria</p>
                    @if(request()->hasAny(['job_search', 'job_category', 'job_location', 'job_budget_min', 'job_budget_max', 'job_deadline_from', 'job_deadline_to']))
                        <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Clear Filters</a>
                    @endif
                </div>
            @endforelse
        </div>
    </section>

    <!-- My Services -->
    <section class="dashboard-section">
        <h2>My Services</h2>
        <div class="services-grid">
            @forelse($services as $service)
                <div class="service-card" title="Click to view details" data-full-description="{{ $service->description }}">
                    <div class="service-header">
                        <h3>{{ $service->title }}</h3>
                        <span class="service-status status-{{ $service->is_active ? 'active' : 'inactive' }}">
                            {{ $service->is_active ? 'Active' : 'Inactive' }}
                        </span>
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
                        <button class="btn btn-secondary btn-sm" onclick="event.stopPropagation(); editService({{ $service->id }})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" 
                                class="btn btn-danger btn-sm service-delete-btn"
                                data-service-id="{{ $service->id }}"
                                data-service-title="{{ $service->title }}"
                                onclick="event.stopPropagation();">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-briefcase"></i>
                    <p>You haven't created any services yet</p>
                    <button class="btn btn-primary" onclick="openServiceModal()">Add Your First Service</button>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Service Requests -->
    @if(isset($serviceRequests) && $serviceRequests->count() > 0)
        <section class="dashboard-section">
            <h2>Service Requests</h2>
            <div class="requests-list">
                @foreach($serviceRequests as $request)
                    <div class="request-card">
                        <div class="request-header">
                            <h3>{{ $request->service->title }}</h3>
                            <span class="request-status status-{{ $request->status }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                        <div class="client-info">
                            <strong>Client:</strong> 
                            <a href="{{ route('users.show', $request->user) }}" class="user-profile-link">{{ $request->user->name }}</a>
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
                        @if($request->status === 'pending')
                            <div class="request-actions">
                                <form action="{{ route('service-requests.accept', $request) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-check"></i> Accept
                                    </button>
                                </form>
                                <form action="{{ route('service-requests.reject', $request) }}" method="POST" style="display: inline;">
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
        </section>
    @endif

    <!-- Job Requests -->
    <section class="dashboard-section">
        <h2>My Job Requests</h2>
        <div class="requests-list">
            @forelse($jobRequests as $request)
                <div class="request-card">
                    <div class="request-header">
                        <h3>{{ $request->job->title }}</h3>
                        <span class="request-status status-{{ $request->status }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </div>
                    <p class="job-description">{{ Str::limit($request->job->description, 150) }}</p>
                    <div class="request-details">
                        <div class="client-info">
                            <strong>Client:</strong> 
                            <a href="{{ route('users.show', $request->job->user) }}" class="user-profile-link">{{ $request->job->user->name }}</a>
                        </div>
                        <div class="location">
                            <i class="fas fa-map-marker-alt"></i> {{ $request->job->location }}
                        </div>
                        @if($request->job->budget)
                            <div class="budget">
                                <i class="fas fa-dollar-sign"></i> ${{ number_format($request->job->budget, 0) }}
                            </div>
                        @endif
                    </div>
                    @if($request->status === 'pending')
                        <div class="pending-message">
                            <p><strong>Your message:</strong> {{ $request->message }}</p>
                            @if($request->proposed_price)
                                <p><strong>Proposed price:</strong> ${{ number_format($request->proposed_price, 0) }}</p>
                            @endif
                        </div>
                    @elseif($request->status === 'accepted')
                        <div class="accepted-message">
                            <p class="success"><i class="fas fa-check"></i> Your request has been accepted!</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No job requests</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Assigned Jobs -->
    <section class="dashboard-section">
        <h2>Assigned Jobs</h2>
        <div class="jobs-grid">
            @forelse($assignedJobs as $job)
                <div class="job-card" title="Click to view details" data-full-description="{{ $job->description }}">
                    <div class="job-header">
                        <h3>{{ $job->title }}</h3>
                        <span class="job-status status-{{ $job->status }}">
                            {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                        </span>
                    </div>
                    <p class="job-description" data-full-description="{{ $job->description }}">{{ Str::limit($job->description, 150) }}</p>
                    <div class="job-details">
                        <div class="client-info">
                            <strong>Client:</strong> 
                            <a href="{{ route('users.show', $job->user) }}" class="user-profile-link">{{ $job->user->name }}</a>
                        </div>
                        <div class="location">
                            <i class="fas fa-map-marker-alt"></i> {{ $job->location }}
                        </div>
                        @if($job->deadline)
                            <div class="deadline">
                                <i class="fas fa-calendar"></i> {{ $job->deadline->format('M d, Y') }}
                            </div>
                        @endif
                    </div>
                    @if($job->status === 'in_progress')
                        <div class="job-actions">
                            <button class="btn btn-success" onclick="event.stopPropagation(); completeJob({{ $job->id }})">
                                <i class="fas fa-check"></i> Mark as Completed
                            </button>
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-briefcase"></i>
                    <p>No assigned jobs</p>
                </div>
            @endforelse
        </div>
    </section>
</div>