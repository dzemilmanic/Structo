@extends('layouts.app')
@vite(['resources/css/qa.css'])
@section('title', 'Edit answer')

@section('content')
    <div class="form-container">
        <h1 class="form-title">Edit Answer</h1>
        
        <div class="question-reference">
            <h3 class="reference-title">The answer to the question:</h3>
            <p class="reference-content">{{ $question->title }}</p>
        </div>
        
        <form action="{{ route('answers.update', $answer) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="content" class="form-label">Answer content</label>
                <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="10" required minlength="5">{{ old('content', $answer->content) }}</textarea>
                
                @error('content')
                    <span class="form-text text-danger">{{ $message }}</span>
                @endif
            </div>
            
            <div class="form-actions">
                <a href="{{ route('questions.show', $question) }}" class="btn btn-outline">Exit</a>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
@endsection