@extends('layouts.app')
@vite(['resources/css/jobs.css'])
@section('title', 'Service Details')

@section('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/jobs.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="jobs-dashboard">
    <div class="dashboard-header">
        <h1><i class="fas fa-tools"></i> Service Details</h1>
        <div class="header-actions">
            <a href="{{ route('services.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Services
            </a>
            @if(Auth::user()->role === 'admin' || $service->professional_id === Auth::user()->id)
                <a href="{{ route('services.edit', $service) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Service
                </a>
            @endif
        </div>
    </div>

    <div class="job-details-container">
        <div class="job-main-content">
            <!-- Service Header -->
            <div class="job-header-card">
                <div class="job-status-badge">
                    <span class="status status-{{ $service->is_active ? 'active' : 'inactive' }}">
                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <h1 class="job-title">{{ $service->title }}</h1>
                <div class="job-meta-info">
                    <div class="meta-item">
                        <i class="fas fa-user"></i>
                        <span>Provided by {{ $service->professional->name }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <span>{{ $service->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-tag"></i>
                        <span>{{ ucfirst(str_replace('-', ' ', $service->category)) }}</span>
                    </div>
                    @if($service->price_from || $service->price_to)
                    <div class="meta-item">
                        <i class="fas fa-dollar-sign"></i>
                        <span>
                            @if($service->price_from && $service->price_to)
                                ${{ number_format($service->price_from) }} - ${{ number_format($service->price_to) }}
                            @elseif($service->price_from)
                                From ${{ number_format($service->price_from) }}
                            @else
                                Up to ${{ number_format($service->price_to) }}
                            @endif
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Service Description -->
            <div class="job-section">
                <h3><i class="fas fa-file-text"></i> Description</h3>
                <div class="job-description">
                    {{ $service->description }}
                </div>
            </div>

            <!-- Service Details -->
            <div class="job-details-grid">
                <div class="job-section">
                    <h3><i class="fas fa-map-marker-alt"></i> Service Area</h3>
                    <p class="detail-text">{{ $service->service_area }}</p>
                </div>

                @if($service->availability)
                <div class="job-section">
                    <h3><i class="fas fa-calendar"></i> Availability</h3>
                    <p class="detail-text">{{ $service->availability }}</p>
                </div>
                @endif
            </div>

            <!-- Professional Information -->
            <div class="job-section">
                <h3><i class="fas fa-user-tie"></i> Professional</h3>
                <div class="assigned-professional-card">
                    <div class="professional-info">
                        <h4>{{ $service->professional->name }}</h4>
                        <p>{{ $service->professional->email }}</p>
                        @if($service->professional->phone)
                            <p><i class="fas fa-phone"></i> {{ $service->professional->phone }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Service Requests -->
            @if($service->requests && $service->requests->count() > 0 && ($service->professional_id === Auth::user()->id || Auth::user()->role === 'admin'))
            <div class="job-section">
                <h3><i class="fas fa-hands-helping"></i> Service Requests ({{ $service->requests->count() }})</h3>
                <div class="requests-list">
                    @foreach($service->requests as $request)
                    <div class="request-card">
                        <div class="request-header">
                            <div class="professional-info">
                                <h4>{{ $request->user->name }}</h4>
                                <p>{{ $request->user->email }}</p>
                            </div>
                            <span class="request-status status-{{ $request->status }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                        
                        <div class="request-content">
                            <p><strong>Message:</strong> {{ $request->message }}</p>
                            @if($request->proposed_date)
                            <p><strong>Proposed Date:</strong> {{ \Carbon\Carbon::parse($request->proposed_date)->format('F d, Y') }}</p>
                            @endif
                            <p><strong>Sent:</strong> {{ $request->created_at->diffForHumans() }}</p>
                        </div>

                        @if($request->status === 'pending' && $service->professional_id === Auth::user()->id)
                        <div class="request-actions">
                            <form action="{{ route('service-requests.accept', $request) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-check"></i> Accept
                                </button>
                            </form>
                            <form action="{{ route('service-requests.reject', $request) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="job-sidebar">
            @if(!Auth::user()->isProfi() && $service->is_active && $service->professional_id !== Auth::user()->id)
            <div class="sidebar-card">
                <h4><i class="fas fa-hand-paper"></i> Request Service</h4>
                <p>Interested in this service? Send a request to the professional.</p>
                <button onclick="openServiceRequestModal({{ $service->id }})" class="btn btn-primary btn-block">
                    <i class="fas fa-paper-plane"></i> Request Service
                </button>
            </div>
            @endif

            <!-- Service Statistics -->
            <div class="sidebar-card">
                <h4><i class="fas fa-chart-bar"></i> Service Statistics</h4>
                <div class="stat-item">
                    <span class="stat-label">Requests:</span>
                    <span class="stat-value">{{ $service->requests ? $service->requests->count() : 0 }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Created:</span>
                    <span class="stat-value">{{ $service->created_at->format('M d, Y') }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Updated:</span>
                    <span class="stat-value">{{ $service->updated_at->format('M d, Y') }}</span>
                </div>
            </div>

            @if(Auth::user()->role === 'admin')
            <div class="sidebar-card admin-actions">
                <h4><i class="fas fa-cog"></i> Admin Actions</h4>
                <button onclick="deleteService({{ $service->id }})" class="btn btn-danger btn-sm btn-block">
                    <i class="fas fa-trash"></i> Delete Service
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Service Request Modal -->
<div id="serviceRequestModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Request Service</h2>
            <button class="modal-close" onclick="closeServiceRequestModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="serviceRequestForm" method="POST" action="{{ route('services.request', $service) }}">
                @csrf
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" required class="form-control" rows="4"
                              placeholder="Describe your project and requirements..."></textarea>
                </div>

                <div class="form-group">
                    <label for="proposed_date">Preferred Date</label>
                    <input type="date" id="proposed_date" name="proposed_date" class="form-control"
                           min="{{ date('Y-m-d') }}">
                </div>

                <div class="form-actions">
                    <button type="button" onclick="closeServiceRequestModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Send Request
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
        function openServiceRequestModal(serviceId) {
            const modal = document.getElementById('serviceRequestModal');
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeServiceRequestModal() {
            const modal = document.getElementById('serviceRequestModal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        }

        function deleteService(serviceId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the service and all its requests!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Delete!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/services/${serviceId}`;
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    
                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                closeServiceRequestModal();
            }
        });
    </script>
@endsection