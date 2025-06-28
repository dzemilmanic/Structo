@extends('layouts.app')

@section('title', 'Create New Service')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Create New Service</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('services.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="title" class="form-label">Service Title *</label>
                            <input type="text" id="title" name="title" class="form-control" 
                                   value="{{ old('title') }}" required
                                   placeholder="e.g. Tile Installation Services">
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
                            <label for="description" class="form-label">Service Description *</label>
                            <textarea id="description" name="description" class="form-control" rows="4" required
                                      placeholder="Describe your service in detail...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="price_from" class="form-label">Price From ($)</label>
                                    <input type="number" id="price_from" name="price_from" class="form-control" min="0"
                                           value="{{ old('price_from') }}" placeholder="Minimum price">
                                    @error('price_from')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="price_to" class="form-label">Price To ($)</label>
                                    <input type="number" id="price_to" name="price_to" class="form-control" min="0"
                                           value="{{ old('price_to') }}" placeholder="Maximum price">
                                    @error('price_to')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="service_area" class="form-label">Service Area *</label>
                            <input type="text" id="service_area" name="service_area" class="form-control" 
                                   value="{{ old('service_area') }}" required 
                                   placeholder="e.g. New York and surrounding areas">
                            @error('service_area')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('jobs.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Service</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection