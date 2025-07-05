@extends('layouts.app')
@vite(['resources/css/users.css',   'resources/js/users.js'])
@section('title', 'Users - Structo')

@section('styles')
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

<!-- Users Hero Section -->
<section class="users-hero">
    <div class="container">
        <div class="users-hero-content">
            <h1>Professional Users</h1>
            <p>Connect with verified construction and architecture professionals for your next project</p>
        </div>
    </div>
</section>

<div class="professionals-container">
    <div class="professionals-header">
        @auth
            @if(auth()->user()->role !== 'profi' && auth()->user()->role !== 'admin')
                @php
                    $hasPendingRequest = \App\Models\ProfiRequest::where('user_id', auth()->id())
                        ->where('status', 'pending')
                        ->exists();
                @endphp
                
                @if(!$hasPendingRequest)
                    <div class="become-professional-section">
                        <button type="button" class="professional-btn professional-btn-accent professional-btn-lg" id="becomeProfessionalBtn">
                            <svg class="professional-btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Become a Professional
                        </button>
                    </div>
                @else
                    <div class="pending-request-notice">
                        <svg class="notice-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Your professional request is pending review</span>
                    </div>
                @endif
            @endif
        @endauth
    </div>

    <!-- Enhanced Search Container -->
    <div class="professionals-search-container">
        <svg class="professionals-search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        
        <input type="text" 
               id="professionalsSearchInput" 
               placeholder="Search professionals by name, specialization, or location..." 
               class="professionals-search-input"
               autocomplete="off"
               spellcheck="false">
        
        <div class="search-actions">
            <button type="button" class="search-clear-btn" aria-label="Clear search">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            <div class="search-loading">
                <div class="loading-spinner"></div>
            </div>
            
            <button type="button" class="search-submit-btn" aria-label="Search professionals">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Search Results Info (will be dynamically added) -->
    
    <!-- Results Header -->
    <div class="users-results-header">
        <h2>Professional Users</h2>
        <p class="results-count">
            Showing {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} of {{ $users->total() }} professionals
        </p>
    </div>

    <div class="professionals-grid" id="professionalsGrid">
        @forelse($users as $user)
            <div class="profi-card" data-search="{{ strtolower($user->name . ' ' . ($user->lastname ?? '') . ' ' . ($user->specialization ?? '') . ' ' . ($user->location ?? '') . ' ' . $user->email) }}">
                <div class="profi-avatar">
                    @if($user->photo)
                        @php
                            // Check if photo is already a full URL or just a path
                            if (str_contains($user->photo, 'amazonaws.com') || str_contains($user->photo, 'http')) {
                                $photoUrl = $user->photo;
                            } else {
                                // Generate full S3 URL from path
                                // Remove leading slash if present
                                $photoPath = ltrim($user->photo, '/');
                                $bucket = config('filesystems.disks.s3.bucket') ?? config('filesystems.disks.profile_photos.bucket');
                                $region = config('filesystems.disks.s3.region') ?? config('filesystems.disks.profile_photos.region');
                                $photoUrl = 'https://' . $bucket . '.s3.' . $region . '.amazonaws.com/' . $photoPath;
                            }
                        @endphp
                        <img src="{{ $photoUrl }}" alt="Profile Photo" class="profi-photo" onerror="this.src='{{ asset('images/default-avatar.png') }}'; this.onerror=null;">
                    @else
                        <div class="profi-photo-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                    @endif
                    <div class="profi-badge">
                        <svg class="profi-badge-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <div class="profi-info">
                    <h3 class="profi-name">{{ $user->name }} {{ $user->lastname ?? '' }}</h3>
                    <p class="profi-email">{{ $user->email }}</p>
                    
                    @if($user->specialization)
                        <p class="profi-specialization">{{ $user->specialization }}</p>
                    @endif
                    
                    @if($user->location)
                        <p class="profi-location">
                            <svg class="profi-location-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $user->location }}
                        </p>
                    @endif

                    <div class="profi-stats">
                        @if(($user->projects_count ?? 0) > 0)
                            <span class="profi-stat-item">
                                <span class="profi-stat-number">{{ $user->projects_count }}</span>
                                <span class="profi-stat-label">Projects</span>
                            </span>
                        @endif
                        
                        @if(($user->rating ?? 0) > 0)
                            <span class="profi-stat-item">
                                <span class="profi-stat-number">{{ number_format($user->rating, 1) }}</span>
                                <span class="profi-stat-label">Rating</span>
                            </span>
                        @endif
                        
                        <span class="profi-stat-item">
                            <span class="profi-stat-number">{{ $user->created_at->format('Y') }}</span>
                            <span class="profi-stat-label">Joined</span>
                        </span>
                    </div>
                </div>

                <div class="profi-actions">
                    <a href="{{ route('users.show', $user) }}" class="professional-btn professional-btn-primary professional-btn-sm">View Profile</a>
                    @if($user->phone)
                        <a href="tel:{{ $user->phone }}" class="professional-btn professional-btn-outline professional-btn-sm">Contact</a>
                    @endif
                </div>
            </div>
        @empty
            <div class="no-professionals">
                <div class="no-professionals-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="no-professionals-title">No Professional Users Found</h3>
                <p class="no-professionals-text">We couldn't find any professional users matching your search criteria. Try adjusting your search terms or check back later.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="pagination-container">
            <div class="pagination">
                {{-- Previous Page Link --}}
                @if ($users->onFirstPage())
                    <button class="pagination-btn prev-btn" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15,18 9,12 15,6"></polyline>
                        </svg>
                        Previous
                    </button>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="pagination-btn prev-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15,18 9,12 15,6"></polyline>
                        </svg>
                        Previous
                    </a>
                @endif

                {{-- Pagination Elements --}}
                <div class="pagination-numbers">
                    @php
                        $start = max($users->currentPage() - 2, 1);
                        $end = min($start + 4, $users->lastPage());
                        $start = max($end - 4, 1);
                    @endphp

                    @for ($i = $start; $i <= $end; $i++)
                        @if ($i == $users->currentPage())
                            <span class="page-number active">{{ $i }}</span>
                        @else
                            <a href="{{ $users->url($i) }}" class="page-number">{{ $i }}</a>
                        @endif
                    @endfor
                </div>

                {{-- Next Page Link --}}
                @if ($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="pagination-btn next-btn">
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
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection