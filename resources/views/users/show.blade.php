@extends('layouts.app')
@vite('resources/css/user_profile.css')
@section('title', $user->name . ' ' . $user->lastname . ' - Profile')

@section('content')
<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-image-container">
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
                    <img src="{{ $photoUrl }}" 
                         alt="{{ $user->name }} {{ $user->lastname }}" 
                         class="profile-image"
                         onerror="this.src='{{ asset('images/default-avatar.png') }}'; this.onerror=null;">
                @else
                    <div class="profile-image-placeholder">
                        <span class="profile-initials">
                            {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr($user->lastname ?? '', 0, 1)) }}
                        </span>
                    </div>
                @endif
                
                @if($user->role === 'profi')
                    <div class="profile-badge">
                        <svg class="badge-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="profile-info">
                <h1 class="profile-name">{{ $user->name }} {{ $user->lastname ?? '' }}</h1>
                @if($user->specialization)
                    <p class="profile-specialty">{{ $user->specialization }}</p>
                @endif
                @if($user->role === 'profi')
                    <span class="profile-role-badge">Verified Professional</span>
                @endif
            </div>
        </div>

        <div class="profile-details">
            <div class="detail-item">
                <div class="detail-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <polyline points="22,6 12,13 2,6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="detail-content">
                    <span class="detail-label">Email</span>
                    <span class="detail-value">{{ $user->email }}</span>
                </div>
            </div>

            @if($user->phone)
                <div class="detail-item">
                    <div class="detail-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 16.92V19.92C22 20.52 21.52 21 20.92 21C10.4 21 2 12.6 2 2.08C2 1.48 2.48 1 3.08 1H6.08C6.68 1 7.16 1.48 7.16 2.08V5.08C7.16 5.68 6.68 6.16 6.08 6.16H4.08C4.08 11.6 8.4 15.92 13.84 15.92V13.92C13.84 13.32 14.32 12.84 14.92 12.84H17.92C18.52 12.84 19 13.32 19 13.92V16.92C19 17.52 18.52 18 17.92 18H14.92C14.32 18 13.84 17.52 13.84 16.92Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Phone</span>
                        <span class="detail-value">
                            <a href="tel:{{ $user->phone }}" class="phone-link">{{ $user->phone }}</a>
                        </span>
                    </div>
                </div>
            @endif

            @if($user->portfolio_url)
                <div class="detail-item">
                    <div class="detail-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 14L14 10M17 7a5 5 0 00-7 0L7 10a5 5 0 007 7l3-3a5 5 0 000-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <div class="detail-content">
                        <span class="detail-label">Portfolio</span>
                        <span class="detail-value">
                            <a href="tel:{{ $user->portfolio_url }}" class="phone-link">{{ $user->portfolio_url }}</a>
                        </span>
                    </div>
                </div>
            @endif

            @if($user->location)
                <div class="detail-item">
                    <div class="detail-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 10C21 17 12 23 12 23S3 17 3 10C3 5.03 7.03 1 12 1S21 5.03 21 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Location</span>
                        <span class="detail-value">{{ $user->location }}</span>
                    </div>
                </div>
            @endif

            @if($user->created_at)
                <div class="detail-item">
                    <div class="detail-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke="currentColor" stroke-width="2"/>
                            <line x1="16" y1="2" x2="16" y2="6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <line x1="8" y1="2" x2="8" y2="6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <line x1="3" y1="10" x2="21" y2="10" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Member since</span>
                        <span class="detail-value">{{ $user->created_at->format('F Y') }}</span>
                    </div>
                </div>
            @endif

            @if($user->role === 'profi')
                <div class="detail-item">
                    <div class="detail-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L15.09 8.26L22 9L17 14L18.18 21L12 17.77L5.82 21L7 14L2 9L8.91 8.26L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="detail-content">
                        <span class="detail-label">Professional Status</span>
                        <span class="detail-value professional-status">Verified Professional</span>
                    </div>
                </div>
            @endif
        </div>

        @if($user->bio)
            <div class="profile-bio">
                <h3 class="bio-title">About</h3>
                <p class="bio-text">{{ $user->bio }}</p>
            </div>
        @endif

        <div class="profile-actions">
            @if($user->phone)
                <a href="tel:{{ $user->phone }}" class="profile-btn profile-btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    Call
                </a>
            @endif
            @if($user->email)
                <a href="mailto:{{ $user->email }}" class="profile-btn profile-btn-outline">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Email
                </a>
            @endif
            <a href="{{ route('users.index') }}" class="profile-btn profile-btn-secondary">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Users
            </a>
        </div>
    </div>
</div>
@endsection