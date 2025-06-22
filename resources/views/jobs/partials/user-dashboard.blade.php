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

    <!-- Available Services Section -->
    <section class="dashboard-section">
        <h2>Available Services</h2>
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
                    <p>No services available at the moment</p>
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