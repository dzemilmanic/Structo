@extends('layouts.app')

@section('title', 'Post New Job')

@section('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/jobs.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="jobs-dashboard">
    <div class="dashboard-header">
        <h1>Post New Job</h1>
        <a href="{{ route('jobs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="form-container">
        <form action="{{ route('jobs.store') }}" method="POST" class="job-form">
            @csrf
            <div class="form-group">
                <label for="job_title">Job Title *</label>
                <input type="text" id="job_title" name="title" required class="form-control"
                       placeholder="e.g. Kitchen tile installation" value="{{ old('title') }}">
                @error('title')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="job_category">Category *</label>
                <select id="job_category" name="category" required class="form-control">
                    <option value="">Select category</option>
                    @php
                        $categories = \App\Models\ServiceCategory::where('is_active', true)->orderBy('sort_order')->get();
                    @endphp
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" {{ old('category') == $category->slug ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="job_description">Job Description *</label>
                <textarea id="job_description" name="description" required class="form-control" rows="4"
                          placeholder="Describe what needs to be done in detail...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="job_budget">Budget ($)</label>
                <input type="number" id="job_budget" name="budget" class="form-control" min="0"
                       placeholder="Enter approximate budget" value="{{ old('budget') }}">
                @error('budget')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="job_location">Location *</label>
                <input type="text" id="job_location" name="location" required class="form-control"
                       placeholder="Enter address" value="{{ old('location') }}">
                @error('location')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="job_deadline">Desired Deadline</label>
                <input type="date" id="job_deadline" name="deadline" class="form-control" value="{{ old('deadline') }}">
                @error('deadline')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Post Job</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection