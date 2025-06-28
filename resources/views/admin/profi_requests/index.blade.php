@extends('layouts.app')
@vite(['resources/css/admin_requests.css'])
@section('title', 'Professional Requests - Admin')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <h1 class="admin-title">
            <svg class="title-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Professional Requests
        </h1>
        <p class="admin-subtitle">Review and manage professional upgrade requests</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="requests-stats">
        <div class="stat-card">
            <div class="stat-icon pending">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-number">{{ $requests->count() }}</span>
                <span class="stat-label">Pending Requests</span>
            </div>
        </div>
    </div>

    <div class="requests-grid">
        @forelse($requests as $request)
            <div class="request-card">
                <div class="request-header">
                    <div class="user-info">
                        <div class="user-avatar-small">
                            @if($request->user->photo)
                                @php
                                    if (str_contains($request->user->photo, 'amazonaws.com') || str_contains($request->user->photo, 'http')) {
                                        $photoUrl = $request->user->photo;
                                    } else {
                                        $photoPath = ltrim($request->user->photo, '/');
                                        $bucket = config('filesystems.disks.s3.bucket') ?? config('filesystems.disks.profile_photos.bucket');
                                        $region = config('filesystems.disks.s3.region') ?? config('filesystems.disks.profile_photos.region');
                                        $photoUrl = 'https://' . $bucket . '.s3.' . $region . '.amazonaws.com/' . $photoPath;
                                    }
                                @endphp
                                <img src="{{ $photoUrl }}" alt="{{ $request->user->name }}" class="avatar-image-small" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="avatar-placeholder-small" style="display: none;">
                                    {{ strtoupper(substr($request->user->name, 0, 1)) }}{{ strtoupper(substr($request->user->lastname ?? '', 0, 1)) }}
                                </div>
                            @else
                                <div class="avatar-placeholder-small">
                                    {{ strtoupper(substr($request->user->name, 0, 1)) }}{{ strtoupper(substr($request->user->lastname ?? '', 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="user-details">
                            <h3 class="user-name-small">{{ $request->user->name }} {{ $request->user->lastname ?? '' }}</h3>
                            <p class="user-email-small">{{ $request->user->email }}</p>
                        </div>
                    </div>
                    <div class="request-date">
                        <svg class="date-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $request->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="request-content">
                    <div class="specialization-info">
                        <h4 class="specialization-title">
                            <svg class="spec-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6z"/>
                            </svg>
                            Specialization
                        </h4>
                        <p class="specialization-text">{{ $request->specialization }}</p>
                    </div>

                    @if($request->files_with_urls && count($request->files_with_urls) > 0)
                        <div class="files-section">
                            <h4 class="files-title">
                                <svg class="files-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Uploaded Documents ({{ count($request->files_with_urls) }})
                            </h4>
                            <div class="files-list">
                                @foreach($request->files_with_urls as $file)
                                    <div class="file-item">
                                        <div class="file-info">
                                            <svg class="file-icon {{ $file['icon_class'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($file['icon_class'] === 'file-pdf')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                @elseif($file['icon_class'] === 'file-image')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                @endif
                                            </svg>
                                            <div class="file-details">
                                                <span class="file-name">{{ $file['original_name'] }}</span>
                                                <span class="file-size">{{ $file['formatted_size'] }}</span>
                                            </div>
                                        </div>
                                        <div class="file-actions">
                                            @if(str_contains($file['mime_type'], 'image'))
                                                <button type="button" class="file-action-btn view-btn" onclick="openImageModal('{{ $file['url'] }}', '{{ $file['original_name'] }}')">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                            @endif
                                            <a href="{{ $file['url'] }}" target="_blank" class="file-action-btn download-btn">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="request-actions">
                    <form id="approve-form-{{ $request->id }}" method="POST" action="{{ route('admin.profi-requests.approve', $request->id) }}" style="display: none;">
                        @csrf
                    </form>
                    <button type="button" class="btn btn-success btn-sm" onclick="confirmApprove({{ $request->id }}, '{{ $request->user->name }} {{ $request->user->lastname ?? '' }}')">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Approve
                    </button>
                    
                    <form id="reject-form-{{ $request->id }}" method="POST" action="{{ route('admin.profi-requests.reject', $request->id) }}" style="display: none;">
                        @csrf
                    </form>
                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmReject({{ $request->id }}, '{{ $request->user->name }} {{ $request->user->lastname ?? '' }}')">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reject
                    </button>
                </div>
            </div>
        @empty
            <div class="no-requests">
                <div class="no-requests-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="no-requests-title">No Pending Requests</h3>
                <p class="no-requests-text">There are currently no professional upgrade requests to review.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Document Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Document preview" class="img-fluid" style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>

<script>
function openImageModal(imageSrc, fileName) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModalLabel').textContent = fileName || 'Document Preview';
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

function confirmApprove(requestId, userName) {
    Swal.fire({
        title: 'Approve Professional Request?',
        text: `Are you sure you want to approve ${userName}'s professional request? This will upgrade their account to professional status.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Approve',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Processing...',
                text: 'Approving professional request',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit the form
            document.getElementById(`approve-form-${requestId}`).submit();
        }
    });
}

function confirmReject(requestId, userName) {
    Swal.fire({
        title: 'Reject Professional Request?',
        text: `Are you sure you want to reject ${userName}'s professional request? This action cannot be undone and all uploaded files will be permanently deleted.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Reject',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Processing...',
                text: 'Rejecting professional request',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit the form
            document.getElementById(`reject-form-${requestId}`).submit();
        }
    });
}

// Show success/error messages if they exist
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
@endif
</script>
@endsection