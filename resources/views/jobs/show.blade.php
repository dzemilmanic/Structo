@extends('layouts.app')
@vite(['resources/css/jobs.css'])
@vite('resources/js/jobs.js')
@section('title', 'Job Details')

@section('styles')
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
@endsection

@section('content')
<div class="jobs-dashboard">
    <!-- Hidden session message data for JavaScript -->
    @if(session('success'))
        <div data-session-success="{{ session('success') }}" style="display: none;"></div>
    @endif
    @if(session('error'))
        <div data-session-error="{{ session('error') }}" style="display: none;"></div>
    @endif

    <div class="dashboard-header">
        <h1><i class="fas fa-briefcase"></i> Job Details</h1>
        <div class="header-actions">
            <a href="{{ route('jobs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Jobs
            </a>
            @if(Auth::user()->role === 'admin' || $job->user_id === Auth::user()->id)
                <a href="{{ route('jobs.edit', $job) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Job
                </a>
            @endif
        </div>
    </div>

    <div class="job-details-container">
        <div class="job-main-content">
            <!-- Job Header -->
            <div class="job-header-card">
                <div class="job-status-badge">
                    <span class="status status-{{ str_replace(' ', '_', $job->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $job->status)) }}
                    </span>
                </div>
                <h1 class="job-title">{{ $job->title }}</h1>
                <div class="job-meta-info">
                    <div class="meta-item">
                        <i class="fas fa-user"></i>
                        <span>Posted by {{ $job->user->name }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <span>{{ $job->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-tag"></i>
                        <span>{{ ucfirst(str_replace('-', ' ', $job->category)) }}</span>
                    </div>
                    @if($job->budget)
                    <div class="meta-item">
                        <i class="fas fa-dollar-sign"></i>
                        <span>${{ number_format($job->budget) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Job Description -->
            <div class="job-section">
                <h3><i class="fas fa-file-text"></i> Description</h3>
                <div class="job-description">
                    {{ $job->description }}
                </div>
            </div>

            <!-- Job Details -->
            <div class="job-details-grid">
                <div class="job-section">
                    <h3><i class="fas fa-map-marker-alt"></i> Location</h3>
                    <p class="detail-text">{{ $job->location }}</p>
                </div>

                @if($job->deadline)
                <div class="job-section">
                    <h3><i class="fas fa-calendar"></i> Deadline</h3>
                    <p class="detail-text">{{ $job->deadline->format('F d, Y') }}</p>
                </div>
                @endif
            </div>

            <!-- Assigned Professional -->
            @if($job->assignedProfessional)
            <div class="job-section">
                <h3><i class="fas fa-user-check"></i> Assigned Professional</h3>
                <div class="assigned-professional-card">
                    <div class="professional-info">
                        <h4>{{ $job->assignedProfessional->name }}</h4>
                        <p>{{ $job->assignedProfessional->email }}</p>
                    </div>
                    @if(Auth::user()->isProfi() && $job->assigned_professional_id === Auth::user()->id && $job->status === 'in_progress')
                    <form action="{{ route('jobs.complete', $job) }}" method="POST" class="complete-job-form">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Mark as Completed
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endif

            <!-- Job Requests -->
            @if($job->requests->count() > 0 && ($job->user_id === Auth::user()->id || Auth::user()->role === 'admin'))
            <div class="job-section">
                <h3><i class="fas fa-hands-helping"></i> Professional Requests ({{ $job->requests->count() }})</h3>
                <div class="requests-list">
                    @foreach($job->requests as $request)
                    <div class="request-card">
                        <div class="request-header">
                            <div class="professional-info">
                                <h4>{{ $request->professional->name }}</h4>
                                <p>{{ $request->professional->email }}</p>
                            </div>
                            <span class="request-status status-{{ $request->status }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                        
                        <div class="request-content">
                            <p><strong>Message:</strong> {{ $request->message }}</p>
                            @if($request->proposed_price)
                            <p><strong>Proposed Price:</strong> ${{ number_format($request->proposed_price) }}</p>
                            @endif
                            <p><strong>Sent:</strong> {{ $request->created_at->diffForHumans() }}</p>
                        </div>

                        @if($request->status === 'pending' && $job->user_id === Auth::user()->id)
                        <div class="request-actions">
                            <form action="{{ route('job-requests.accept', $request) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-check"></i> Accept
                                </button>
                            </form>
                            <form action="{{ route('job-requests.reject', $request) }}" method="POST" style="display: inline;">
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
            @if(Auth::user()->isProfi() && $job->status === 'open' && !$job->assignedProfessional && !$job->requests->where('professional_id', Auth::user()->id)->first())
            <div class="sidebar-card">
                <h4><i class="fas fa-hand-paper"></i> Send Request</h4>
                <p>Interested in this job? Send a request to the client.</p>
                <button onclick="openJobRequestModal({{ $job->id }})" class="btn btn-primary btn-block">
                    <i class="fas fa-paper-plane"></i> Send Request
                </button>
            </div>
            @endif

            @if(Auth::user()->isProfi() && $job->requests->where('professional_id', Auth::user()->id)->first())
            @php $userRequest = $job->requests->where('professional_id', Auth::user()->id)->first(); @endphp
            <div class="sidebar-card">
                <h4><i class="fas fa-info-circle"></i> Your Request</h4>
                <p><strong>Status:</strong> 
                    <span class="status status-{{ $userRequest->status }}">
                        {{ ucfirst($userRequest->status) }}
                    </span>
                </p>
                <p><strong>Sent:</strong> {{ $userRequest->created_at->diffForHumans() }}</p>
            </div>
            @endif

            <!-- Job Statistics -->
            <div class="sidebar-card">
                <h4><i class="fas fa-chart-bar"></i> Job Statistics</h4>
                <div class="stat-item">
                    <span class="stat-label">Requests:</span>
                    <span class="stat-value">{{ $job->requests->count() }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Created:</span>
                    <span class="stat-value">{{ $job->created_at->format('M d, Y') }}</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Updated:</span>
                    <span class="stat-value">{{ $job->updated_at->format('M d, Y') }}</span>
                </div>
            </div>

            @if(Auth::user()->role === 'admin')
            <div class="sidebar-card admin-actions">
                <h4><i class="fas fa-cog"></i> Admin Actions</h4>
                <button type="button" 
                        class="btn btn-danger btn-sm btn-block job-delete-btn"
                        data-job-id="{{ $job->id }}"
                        data-job-title="{{ $job->title }}">
                    <i class="fas fa-trash"></i> Delete Job
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Job Request Modal -->
<div id="jobRequestModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Send Job Request</h2>
            <button class="modal-close" onclick="closeJobRequestModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="jobRequestForm" method="POST" action="{{ route('jobs.request', $job) }}">
                @csrf
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" required class="form-control" rows="4"
                              placeholder="Explain why you're the right professional for this job..."></textarea>
                </div>

                <div class="form-group">
                    <label for="proposed_price">Proposed Price ($)</label>
                    <input type="number" id="proposed_price" name="proposed_price" class="form-control" min="0"
                           placeholder="Enter your proposed price (optional)">
                </div>

                <div class="form-actions">
                    <button type="button" onclick="closeJobRequestModal()" class="btn btn-secondary">Cancel</button>
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
        // Session Message Handler
        class SessionMessageHandler {
            constructor() {
                this.init();
            }

            init() {
                this.displaySessionMessages();
            }

            displaySessionMessages() {
                const successMessage = this.getSessionMessage('success');
                if (successMessage) {
                    this.showSuccessToast(successMessage);
                }

                const errorMessage = this.getSessionMessage('error');
                if (errorMessage) {
                    this.showErrorToast(errorMessage);
                }
            }

            getSessionMessage(type) {
                const element = document.querySelector(`[data-session-${type}]`);
                return element ? element.getAttribute(`data-session-${type}`) : null;
            }

            showSuccessToast(message) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: message,
                        timer: 5000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end',
                        showCloseButton: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });
                }
            }

            showErrorToast(message) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: message,
                        timer: 8000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end',
                        showCloseButton: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });
                }
            }
        }

        // Job Delete Handler
        class JobDeleteHandler {
            constructor() {
                this.init();
            }

            init() {
                document.addEventListener('click', (e) => {
                    if (e.target.classList.contains('job-delete-btn') || 
                        e.target.closest('.job-delete-btn')) {
                        e.preventDefault();
                        
                        const button = e.target.classList.contains('job-delete-btn') 
                            ? e.target 
                            : e.target.closest('.job-delete-btn');
                        
                        const jobId = button.getAttribute('data-job-id');
                        const jobTitle = button.getAttribute('data-job-title');
                        
                        this.showDeleteConfirmation(jobId, jobTitle);
                    }
                });
            }

            showDeleteConfirmation(jobId, jobTitle) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Delete Job?',
                        text: `Are you sure you want to delete "${jobTitle}"? This action cannot be undone and will permanently remove the job and all its requests.`,
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, Delete Job',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        focusCancel: true,
                        allowOutsideClick: true,
                        allowEscapeKey: true,
                        buttonsStyling: true,
                        icon: false,
                        iconHtml: ''
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Deleting Job...',
                                text: 'Please wait while we delete the job.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                icon: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            
                            this.submitDeleteForm(jobId);
                        }
                    });
                } else {
                    if (confirm(`Are you sure you want to delete "${jobTitle}"?`)) {
                        this.submitDeleteForm(jobId);
                    }
                }
            }

            submitDeleteForm(jobId) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    alert('CSRF token not found. Please refresh the page.');
                    return;
                }

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/jobs/${jobId}`;
                form.style.display = 'none';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        }

        function openJobRequestModal(jobId) {
            const modal = document.getElementById('jobRequestModal');
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }

        function closeJobRequestModal() {
            const modal = document.getElementById('jobRequestModal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        }

        // Initialize handlers
        document.addEventListener('DOMContentLoaded', function() {
            new SessionMessageHandler();
            new JobDeleteHandler();
        });

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                closeJobRequestModal();
            }
        });
    </script>
@endsection