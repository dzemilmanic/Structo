@extends('layouts.app')
@vite(['resources/css/admin_requests.css', 'resources/css/admin-requests-pagination.css'])
@vite(['resources/css/sweetalert-global.css'])
@vite(['resources/js/admin-requests.js'])
@section('title', 'Professional Requests - Admin')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<!-- Hidden elements for session messages (JOBS-STYLE) -->
@if(session('success'))
    <div data-session-success="{{ session('success') }}" style="display: none;"></div>
@endif

@if(session('error'))
    <div data-session-error="{{ session('error') }}" style="display: none;"></div>
@endif

@if(session('info'))
    <div data-session-info="{{ session('info') }}" style="display: none;"></div>
@endif

@if(session('warning'))
    <div data-session-warning="{{ session('warning') }}" style="display: none;"></div>
@endif

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

    <div class="requests-stats">
        <div class="stat-card">
            <div class="stat-icon pending">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-number">{{ $requests->total() }}</span>
                <span class="stat-label">Total Pending Requests</span>
            </div>
        </div>
    </div>

    <!-- Results Header -->
    <div class="requests-results-header">
        <h2>Pending Requests</h2>
        <p class="results-count">
            Showing {{ $requests->firstItem() ?? 0 }}-{{ $requests->lastItem() ?? 0 }} of {{ $requests->total() }} requests
        </p>
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
                                                <button type="button" 
                                                        class="file-action-btn view-btn" 
                                                        data-image-src="{{ $file['url'] }}" 
                                                        data-file-name="{{ $file['original_name'] }}">
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
                    <button type="button" 
                            class="btn btn-success btn-sm approve-btn" 
                            data-request-id="{{ $request->id }}" 
                            data-user-name="{{ $request->user->name }} {{ $request->user->lastname ?? '' }}">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Approve
                    </button>
                    
                    <form id="reject-form-{{ $request->id }}" method="POST" action="{{ route('admin.profi-requests.reject', $request->id) }}" style="display: none;">
                        @csrf
                    </form>
                    <button type="button" 
                            class="btn btn-danger btn-sm reject-btn" 
                            data-request-id="{{ $request->id }}" 
                            data-user-name="{{ $request->user->name }} {{ $request->user->lastname ?? '' }}">
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

    <!-- Pagination -->
    @if($requests->hasPages())
        <div class="pagination-container">
            <div class="pagination">
                {{-- Previous Page Link --}}
                @if ($requests->onFirstPage())
                    <button class="pagination-btn prev-btn" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15,18 9,12 15,6"></polyline>
                        </svg>
                        Previous
                    </button>
                @else
                    <a href="{{ $requests->previousPageUrl() }}" class="pagination-btn prev-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15,18 9,12 15,6"></polyline>
                        </svg>
                        Previous
                    </a>
                @endif

                {{-- Pagination Elements --}}
                <div class="pagination-numbers">
                    @php
                        $start = max($requests->currentPage() - 2, 1);
                        $end = min($start + 4, $requests->lastPage());
                        $start = max($end - 4, 1);
                    @endphp

                    @for ($i = $start; $i <= $end; $i++)
                        @if ($i == $requests->currentPage())
                            <span class="page-number active">{{ $i }}</span>
                        @else
                            <a href="{{ $requests->url($i) }}" class="page-number">{{ $i }}</a>
                        @endif
                    @endfor
                </div>

                {{-- Next Page Link --}}
                @if ($requests->hasMorePages())
                    <a href="{{ $requests->nextPageUrl() }}" class="pagination-btn next-btn">
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
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection