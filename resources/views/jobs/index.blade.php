@extends('layouts.app')
@vite('resources/css/jobs.css');
@vite('resources/js/jobs.js');
@section('title', 'Dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/jobs.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    <div class="jobs-dashboard">
        @if(Auth::user()->isProfi())
            @include('jobs.partials.professional-dashboard')
        @else
            @include('jobs.partials.user-dashboard')
        @endif
    </div>

    <!-- Modals -->
    @include('jobs.partials.job-modal')
    @include('jobs.partials.service-modal')
    @include('jobs.partials.service-request-modal')
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('resources/js/jobs.js') }}"></script>
@endsection