@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
    @vite('resources/css/jobs.css')
@endsection

@section('content')
<div class="jobs-dashboard">
    @if(Auth::user()->isProfi())
        @include('jobs.partials.professional-dashboard')
    @else
        @include('jobs.partials.user-dashboard')
    @endif
</div>
@endsection

@section('scripts')
    @vite('resources/js/jobs.js')
@endsection