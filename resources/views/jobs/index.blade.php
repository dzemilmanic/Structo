@extends('layouts.app')
@vite('resources/css/jobs.css')
@vite('resources/js/jobs.js')
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
@include('jobs.partials.job-request-modal')

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Add job request modal functions BEFORE loading the main jobs.js
function openJobRequestModal(jobId) {
    console.log('Opening job request modal for job:', jobId);
    
    // Set form action
    const form = document.getElementById('jobRequestForm');
    if (form) {
        form.action = `/jobs/${jobId}/request`;
    }
    
    // Use the openModal function from jobs.js
    if (typeof openModal === 'function') {
        openModal('jobRequestModal');
    } else {
        // Fallback if openModal is not yet available
        const modal = document.getElementById('jobRequestModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }
}

function closeJobRequestModal() {
    console.log('Closing job request modal');
    
    // Use the closeModal function from jobs.js
    if (typeof closeModal === 'function') {
        closeModal('jobRequestModal');
    } else {
        // Fallback if closeModal is not yet available
        const modal = document.getElementById('jobRequestModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }
}

// Make functions globally available immediately
window.openJobRequestModal = openJobRequestModal;
window.closeJobRequestModal = closeJobRequestModal;
</script>
<script src="{{ asset('resources/js/jobs.js') }}"></script>
@endsection