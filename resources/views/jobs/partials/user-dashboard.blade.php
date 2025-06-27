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
        <div class="jobs-grid">
            @forelse($jobs as $job)
                <div class="job-card">
                    <div class="job-header">
                        <h3>{{ $job->title }}</h3>
                        <span class="job-status status-{{ $job->status }}">
                            {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                        </span>
                    </div>
                    <p class="job-description">{{ Str::limit($job->description, 100) }}</p>
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
                            <strong>Assigned to:</strong> {{ $job->assignedProfessional->name }}
                        </div>
                    @endif
                    @if($job->status === 'open' && $job->requests && $job->requests->count() > 0)
                        <div class="job-requests">
                            <h4>Professional Requests ({{ $job->requests->count() }})</h4>
                            @foreach($job->requests as $request)
                                <div class="request-item">
                                    <div class="request-header">
                                        <strong>{{ $request->professional->name }}</strong>
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
                        <button class="btn btn-secondary btn-sm" onclick="editJob({{ $job->id }})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteJob({{ $job->id }})">
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
                                <option value="tiles" {{ request('service_category') == 'tiles' ? 'selected' : '' }}>Tiles</option>
                                <option value="electrical" {{ request('service_category') == 'electrical' ? 'selected' : '' }}>Electrical</option>
                                <option value="plumbing" {{ request('service_category') == 'plumbing' ? 'selected' : '' }}>Plumbing</option>
                                <option value="heating" {{ request('service_category') == 'heating' ? 'selected' : '' }}>Heating</option>
                                <option value="facade" {{ request('service_category') == 'facade' ? 'selected' : '' }}>Facade Work</option>
                                <option value="roofing" {{ request('service_category') == 'roofing' ? 'selected' : '' }}>Roofing</option>
                                <option value="carpentry" {{ request('service_category') == 'carpentry' ? 'selected' : '' }}>Carpentry</option>
                                <option value="painting" {{ request('service_category') == 'painting' ? 'selected' : '' }}>Painting</option>
                                <option value="other" {{ request('service_category') == 'other' ? 'selected' : '' }}>Other</option>
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

        <div class="services-grid">
            @forelse($availableServices as $service)
                <div class="service-card">
                    <div class="service-header">
                        <h3>{{ $service->title }}</h3>
                        <div class="professional-info">
                            <strong>{{ $service->professional->name }}</strong>
                            @if($service->professional->specialization)
                                <span class="specialization">{{ $service->professional->specialization }}</span>
                            @endif
                        </div>
                    </div>
                    <p class="service-description">{{ Str::limit($service->description, 100) }}</p>
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
                        <button class="btn btn-primary" onclick="requestService({{ $service->id }})">
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
    </section>

    <!-- My Service Requests Section -->
    <section class="dashboard-section">
        <h2>My Service Requests</h2>
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
                        <strong>Professional:</strong> {{ $request->service->professional->name }}
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
    </section>
</div>