@extends('layouts.app')
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
                            @if($request->user->avatar)
                                <img src="{{ Storage::disk('s3')->url($request->user->avatar) }}" alt="{{ $request->user->name }}" class="avatar-image-small">
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

                    @if($request->image)
                        <div class="proof-section">
                            <h4 class="proof-title">
                                <svg class="proof-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Professional Proof
                            </h4>
                            <div class="proof-image-container">
                                <img src="{{ Storage::disk('s3')->url($request->image) }}" alt="Professional proof" class="proof-image" onclick="openImageModal('{{ Storage::disk('s3')->url($request->image) }}')">
                                <div class="image-overlay">
                                    <svg class="zoom-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="request-actions">
                    <form method="POST" action="{{ route('admin.profi-requests.approve', $request->id) }}" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this request?')">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Approve
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.profi-requests.reject', $request->id) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject this request?')">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject
                        </button>
                    </form>
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
                <h5 class="modal-title" id="imageModalLabel">Professional Proof</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Professional proof" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>
@endsection