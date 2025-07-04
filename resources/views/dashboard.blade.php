@extends('layouts.app')
@vite('resources/css/dashboard.css')
@section('title', 'Dashboard')

@section('content')
<div class="dashboard-container">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="welcome-content">
            <div class="welcome-header">
                <h1 class="welcome-title">
                    Welcome to our page!
                </h1>
                <p class="welcome-subtitle">
                    Hello {{ auth()->user()->name ?? 'User' }}, you're ready to start your journey with us.
                </p>
            </div>
            <div class="welcome-illustration">
                <div class="illustration-circle">
                    <svg viewBox="0 0 100 100" class="welcome-icon">
                        <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="3"/>
                        <path d="M30 50 L45 65 L70 40" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endpush