@extends('layouts.app')
@vite(['resources/css/qa.css'])
@vite(['resources/js/qa.js'])
@section('title', 'Questions and answers')

@section('content')
    <div class="search-container">
        <form action="{{ route('questions.index') }}" method="GET" class="search-form">
            <input type="text" name="search" class="search-input" placeholder="Pretražite pitanja..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('questions.create') }}" class="btn btn-secondary">Ask a question</a>
        </form>
    </div>

    <div class="sort-container">
        <h1 class="page-title">Questions and answers</h1>
        <div class="sort-options">
            <span class="sort-label">Sort by:</span>
            <select name="sort" class="sort-select">
                <option value="newest" {{ ($sort ?? '') == 'newest' ? 'selected' : '' }}>Newest</option>
                <option value="oldest" {{ ($sort ?? '') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                <option value="most_answers" {{ ($sort ?? '') == 'most_answers' ? 'selected' : '' }}>Most Answers</option>
                <option value="views" {{ ($sort ?? '') == 'views' ? 'selected' : '' }}>Most viewed</option>
            </select>
        </div>
    </div>

    <div class="question-list">
        @if ($questions->count() > 0)
            @foreach ($questions as $question)
                <div class="question-card">
                    <div class="question-header">
                        <h2 class="question-title">
                            <a href="{{ route('questions.show', $question) }}">{{ $question->title }}</a>
                        </h2>
                        <span class="question-status status-{{ $question->status }}">{{ $question->status }}</span>
                    </div>
                    
                    <div class="question-meta">
                        <span>Posted by <span class="question-author">{{ $question->user->name }}</span></span>
                        <span>{{ $question->created_at->diffForHumans() }}</span>
                    </div>
                    
                    <div class="question-content">
                        {{ \Illuminate\Support\Str::limit(strip_tags($question->content), 200) }}
                    </div>
                    
                    <div class="question-stats">
                        <div class="question-stat">
                            <i class="icon-eye"></i>
                            <span>{{ $question->views }} views</span>
                        </div>
                        <div class="question-stat">
                            <i class="icon-message"></i>
                            <span>{{ $question->answers_count }} reply</span>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <div class="pagination">
                {{ $questions->appends(['search' => $search ?? '', 'sort' => $sort ?? ''])->links() }}
            </div>
        @else
            <div class="empty-state">
                <p>No questions found{{ $search ? ' for the required term "' . $search . '"' : '' }}.</p>
                
                @auth
                    <a href="{{ route('questions.create') }}" class="btn btn-primary">Ask the first question</a>
                @else
                    <p>
                        <a href="{{ route('login') }}">Login</a> or 
                        <a href="{{ route('register') }}">Register</a> to ask a question.
                    </p>
                @endauth
            </div>
        @endif
    </div>
@endsection