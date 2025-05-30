@extends('layouts.app')
@vite(['resources/css/qa.css'])
@section('title', 'Edit the question')

@section('content')
    <div class="form-container">
        <h1 class="form-title">Edit the question</h1>
        
        <form action="{{ route('questions.update', $question) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="title" class="form-label">Title of the question</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $question->title) }}" required minlength="5" maxlength="255">
                
                @error('title')
                    <span class="form-text text-danger">{{ $message }}</span>
                @else
                    <span class="form-text">The title should be clear and concise (5-255 characters).</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="content" class="form-label">Question description</label>
                <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="10" required minlength="10">{{ old('content', $question->content) }}</textarea>
                
                @error('content')
                    <span class="form-text text-danger">{{ $message }}</span>
                @else
                    <span class="form-text">Please describe your question in detail in order to get the most accurate answer possible.</span>
                @enderror
            </div>
            
            <div class="form-actions">
                <a href="{{ route('questions.show', $question) }}" class="btn btn-outline">Exit</a>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
@endsection