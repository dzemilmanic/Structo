@extends('layouts.app')
@vite(['resources/css/qa.css'])
@vite(['resources/js/qa.js'])
@section('title', 'Questions and Answers - Structo')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<!-- Hidden elements for session messages (JOBS-STYLE) -->
@if(session('success'))
    <div data-session-success="{{ session('success') }}" style="display: none;"></div>
@endif

@if(session('error'))
    <div data-session-error="{{ session('error') }}" style="display: none;"></div>
@endif

@if(session('info'))
    <div data-session-info="{{ session('info') }}" style="display: none;"></div>
@endif

@if(session('warning'))
    <div data-session-warning="{{ session('warning') }}" style="display: none;"></div>
@endif

<!-- Q&A Hero Section -->
<section class="qa-hero">
    <div class="container">
        <div class="qa-hero-content">
            <h1>Questions & Answers</h1>
            <p>Get expert advice from construction and architecture professionals</p>
        </div>
    </div>
</section>

<!-- Search and Filters Section -->
<section class="qa-search-section">
    <div class="container">
        <div class="search-container">
            <form action="{{ route('questions.index') }}" method="GET" class="search-form">
                <div class="search-box">
                    <input type="text" name="search" class="search-input" placeholder="Search questions..." value="{{ $search ?? '' }}">
                    <button type="submit" class="search-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="action-buttons">
                    @auth
                        <button type="button" class="btn btn-primary" id="askQuestionBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                            Ask Question
                        </button>
                    @else
                        <button type="button" class="btn btn-primary" id="askQuestionBtnGuest">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                            Ask Question
                        </button>
                    @endauth
                </div>
            </form>
        </div>

        <div class="sort-container">
            <div class="results-info">
                <h2 class="section-title">
                    @if($search)
                        Search Results for "{{ $search }}"
                    @else
                        All Questions
                    @endif
                </h2>
                <p class="results-count">{{ $questions->total() }} {{ Str::plural('question', $questions->total()) }} found</p>
            </div>
            
            <div class="sort-options">
                <label for="sort" class="sort-label">Sort by:</label>
                <select name="sort" class="sort-select" id="sortSelect">
                    <option value="newest" {{ ($sort ?? '') == 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ ($sort ?? '') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="most_answers" {{ ($sort ?? '') == 'most_answers' ? 'selected' : '' }}>Most Answers</option>
                    <option value="views" {{ ($sort ?? '') == 'views' ? 'selected' : '' }}>Most Viewed</option>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- Questions List Section -->
<section class="questions-section">
    <div class="container">
        <div class="question-list">
            @if ($questions->count() > 0)
                @foreach ($questions as $question)
                    <div class="question-card" data-url="{{ route('questions.show', $question) }}">
                        <div class="question-header">
                            <h3 class="question-title">
                                <a href="{{ route('questions.show', $question) }}">{{ $question->title }}</a>
                            </h3>
                            <span class="question-status status-{{ $question->status }}">{{ ucfirst($question->status) }}</span>
                        </div>
                        
                        <div class="question-content">
                            {{ \Illuminate\Support\Str::limit(strip_tags($question->content), 200) }}
                        </div>

                        <div class="question-footer">
                            <div class="question-meta">
                                <div class="author-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span>{{ $question->user->name }}</span>
                                </div>
                                <div class="date-info">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12,6 12,12 16,14"></polyline>
                                    </svg>
                                    <span>{{ $question->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            
                            <div class="question-stats">
                                <div class="question-stat">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <span>{{ $question->views }}</span>
                                </div>
                                <div class="question-stat">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                    <span>{{ $question->answers_count }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <div class="pagination-wrapper">
                    {{ $questions->appends(['search' => $search ?? '', 'sort' => $sort ?? ''])->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M9,9h0"></path>
                            <path d="M15,9h0"></path>
                            <path d="M8,15s1.5,2 4,2 4-2 4-2"></path>
                        </svg>
                    </div>
                    <h3>No questions found</h3>
                    <p>
                        @if($search)
                            No questions match your search for "{{ $search }}". Try different keywords or browse all questions.
                        @else
                            Be the first to ask a question and get expert advice from our community!
                        @endif
                    </p>
                    
                    @auth
                        <button type="button" class="btn btn-primary" id="askQuestionBtnEmpty">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                            Ask the First Question
                        </button>
                    @else
                        <div class="login-prompt">
                            <button type="button" class="btn btn-primary" id="askQuestionBtnEmptyGuest">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                                Ask a Question
                            </button>
                            <p class="login-text">
                                <a href="{{ route('login') }}">Login</a> or 
                                <a href="{{ route('register') }}">Register</a> to ask questions and get expert answers.
                            </p>
                        </div>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Ask Question Modal -->
@auth
<div id="askQuestionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Ask a New Question</h2>
            <span class="modal-close">&times;</span>
        </div>
        <form id="askQuestionForm" action="{{ route('questions.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="modal-title" class="form-label">Question Title</label>
                    <input type="text" name="title" id="modal-title" class="form-control" required minlength="5" maxlength="255" placeholder="What's your question about?">
                    <span class="form-text">Be specific and clear (5-255 characters)</span>
                    <span class="form-text text-danger" id="title-error" style="display: none;"></span>
                </div>
                
                <div class="form-group">
                    <label for="modal-content" class="form-label">Question Details</label>
                    <textarea name="content" id="modal-content" class="form-control" rows="8" required minlength="10" placeholder="Provide detailed information about your question to get the best answers..."></textarea>
                    <span class="form-text">Include relevant details, context, and any specific requirements</span>
                    <span class="form-text text-danger" id="content-error" style="display: none;"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" id="modalCancelBtn">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 2L11 13"></path>
                        <path d="M22 2l-7 20-4-9-9-4 20-7z"></path>
                    </svg>
                    Post Question
                </button>
            </div>
        </form>
    </div>
</div>
@endauth
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Handle validation errors
@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('askQuestionModal').style.display = 'block';
        
        @if($errors->has('title'))
            document.getElementById('title-error').textContent = "{{ $errors->first('title') }}";
            document.getElementById('title-error').style.display = 'block';
            document.getElementById('modal-title').classList.add('is-invalid');
        @endif
        
        @if($errors->has('content'))
            document.getElementById('content-error').textContent = "{{ $errors->first('content') }}";
            document.getElementById('content-error').style.display = 'block';
            document.getElementById('modal-content').classList.add('is-invalid');
        @endif
        
        // Populate form with old values
        @if(old('title'))
            document.getElementById('modal-title').value = "{{ old('title') }}";
        @endif
        
        @if(old('content'))
            document.getElementById('modal-content').value = "{{ old('content') }}";
        @endif
    });
@endif

// Sort functionality
document.addEventListener('DOMContentLoaded', function() {
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('sort', this.value);
            window.location.href = currentUrl.toString();
        });
    }
});
</script>
@endsection