@extends('layouts.app')
@vite(['resources/css/qa.css'])

@section('title', 'Pitanja i odgovori')

@section('content')
    <div class="search-container">
        <form action="{{ route('questions.index') }}" method="GET" class="search-form">
            <input type="text" name="search" class="search-input" placeholder="Pretražite pitanja..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-primary">Pretraži</button>
            <a href="{{ route('questions.create') }}" class="btn btn-secondary">Postavi pitanje</a>
        </form>
    </div>

    <div class="sort-container">
        <h1 class="page-title">Pitanja i odgovori</h1>
        <div class="sort-options">
            <span class="sort-label">Sortiraj po:</span>
            <select name="sort" class="sort-select">
                <option value="newest" {{ ($sort ?? '') == 'newest' ? 'selected' : '' }}>Najnovije</option>
                <option value="oldest" {{ ($sort ?? '') == 'oldest' ? 'selected' : '' }}>Najstarije</option>
                <option value="most_answers" {{ ($sort ?? '') == 'most_answers' ? 'selected' : '' }}>Najviše odgovora</option>
                <option value="views" {{ ($sort ?? '') == 'views' ? 'selected' : '' }}>Najviše pregleda</option>
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
                        <span>Postavio <span class="question-author">{{ $question->user->name }}</span></span>
                        <span>{{ $question->created_at->diffForHumans() }}</span>
                    </div>
                    
                    <div class="question-content">
                        {{ \Illuminate\Support\Str::limit(strip_tags($question->content), 200) }}
                    </div>
                    
                    <div class="question-stats">
                        <div class="question-stat">
                            <i class="icon-eye"></i>
                            <span>{{ $question->views }} pregleda</span>
                        </div>
                        <div class="question-stat">
                            <i class="icon-message"></i>
                            <span>{{ $question->answers_count }} odgovora</span>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <div class="pagination">
                {{ $questions->appends(['search' => $search ?? '', 'sort' => $sort ?? ''])->links() }}
            </div>
        @else
            <div class="empty-state">
                <p>Nema pronađenih pitanja{{ $search ? ' za traženi pojam "' . $search . '"' : '' }}.</p>
                
                @auth
                    <a href="{{ route('questions.create') }}" class="btn btn-primary">Postavite prvo pitanje</a>
                @else
                    <p>
                        <a href="{{ route('login') }}">Prijavite se</a> ili 
                        <a href="{{ route('register') }}">registrujte</a> da biste postavili pitanje.
                    </p>
                @endauth
            </div>
        @endif
    </div>
@endsection