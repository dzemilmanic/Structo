@extends('layouts.app')
@vite('resources/css/user_profile.css')
@section('title', $user->name . ' ' . $user->lastname . ' - Profile')


@section('content')
<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-image-container">
                @if($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                         alt="{{ $user->name }} {{ $user->lastname }}" 
                         class="profile-image">
                @else
                    <div class="profile-image-placeholder">
                        <span class="profile-initials">
                            {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr($user->lastname, 0, 1)) }}
                        </span>
                    </div>
                @endif
            </div>
            <div class="profile-info">
                <h1 class="profile-name">{{ $user->name }} {{ $user->lastname }}</h1>
                @if($user->specialization)
                    <p class="profile-specialty">{{ $user->specialization }}</p>
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
                        <span class="detail-value">{{ $user->phone }}</span>
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
        </div>

        @if($user->bio)
            <div class="profile-bio">
                <h3>About</h3>
                <p>{{ $user->bio }}</p>
            </div>
        @endif

    </div>
</div>
@endsection