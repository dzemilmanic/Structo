@extends('layouts.app')

@section('title', 'Post New Job')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Post New Job</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('jobs.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="title" class="form-label">Job Title *</label>
                            <input type="text" id="title" name="title" class="form-control" 
                                   value="{{ old('title') }}" required
                                   placeholder="e.g. Kitchen tile installation">
                            @error('title')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="category" class="form-label">Category *</label>
                            <select id="category" name="category" class="form-control" required>
                                <option value="">Select category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" {{ old('category') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Job Description *</label>
                            <textarea id="description" name="description" class="form-control" rows="4" required
                                      placeholder="Describe what needs to be done in detail...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="budget" class="form-label">Budget ($)</label>
                            <input type="number" id="budget" name="budget" class="form-control" min="0"
                                   value="{{ old('budget') }}" placeholder="Enter approximate budget">
                            @error('budget')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="location" class="form-label">Location *</label>
                            <input type="text" id="location" name="location" class="form-control" 
                                   value="{{ old('location') }}" required placeholder="Enter address">
                            @error('location')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="deadline" class="form-label">Desired Deadline</label>
                            <input type="date" id="deadline" name="deadline" class="form-control" 
                                   value="{{ old('deadline') }}">
                            @error('deadline')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Post Job</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection