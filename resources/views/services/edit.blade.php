@extends('layouts.app')
@vite('resources/css/jobs.css');
@vite('resources/js/jobs.js');
@section('title', 'Edit Service')

@section('styles')
    <link rel="stylesheet" href="{{ asset('resources/css/jobs.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="jobs-dashboard">
    <div class="dashboard-header">
        <h1>Edit Service</h1>
        <a href="{{ route('jobs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="form-container">
        <form action="{{ route('services.update', $service) }}" method="POST" class="service-form">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="service_title">Service Title *</label>
                <input type="text" id="service_title" name="title" required class="form-control" 
                       placeholder="e.g. Tile Installation Services" value="{{ old('title', $service->title) }}">
                @error('title')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="service_category">Category *</label>
                <select id="service_category" name="category" required class="form-control">
                    <option value="">Select category</option>
                    <option value="tiles" {{ old('category', $service->category) == 'tiles' ? 'selected' : '' }}>Tiles</option>
                    <option value="electrical" {{ old('category', $service->category) == 'electrical' ? 'selected' : '' }}>Electrical</option>
                    <option value="plumbing" {{ old('category', $service->category) == 'plumbing' ? 'selected' : '' }}>Plumbing</option>
                    <option value="heating" {{ old('category', $service->category) == 'heating' ? 'selected' : '' }}>Heating</option>
                    <option value="facade" {{ old('category', $service->category) == 'facade' ? 'selected' : '' }}>Facade Work</option>
                    <option value="roofing" {{ old('category', $service->category) == 'roofing' ? 'selected' : '' }}>Roofing</option>
                    <option value="carpentry" {{ old('category', $service->category) == 'carpentry' ? 'selected' : '' }}>Carpentry</option>
                    <option value="painting" {{ old('category', $service->category) == 'painting' ? 'selected' : '' }}>Painting</option>
                    <option value="other" {{ old('category', $service->category) == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('category')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="service_description">Service Description *</label>
                <textarea id="service_description" name="description" required class="form-control" rows="4"
                          placeholder="Describe your service in detail...">{{ old('description', $service->description) }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="price_from">Price From ($)</label>
                    <input type="number" id="price_from" name="price_from" class="form-control" min="0"
                           placeholder="Minimum price" value="{{ old('price_from', $service->price_from) }}">
                    @error('price_from')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="price_to">Price To ($)</label>
                    <input type="number" id="price_to" name="price_to" class="form-control" min="0"
                           placeholder="Maximum price" value="{{ old('price_to', $service->price_to) }}">
                    @error('price_to')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="service_area">Service Area *</label>
                <input type="text" id="service_area" name="service_area" required class="form-control"
                       placeholder="e.g. New York and surrounding areas" value="{{ old('service_area', $service->service_area) }}">
                @error('service_area')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                    <span class="checkmark"></span>
                    Service is active
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Service</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection