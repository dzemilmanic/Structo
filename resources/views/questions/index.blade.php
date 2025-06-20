@extends('layouts.app')
@vite(['resources/css/qa.css'])
@vite(['resources/js/qa.js'])
@section('title', 'Questions and answers')

@section('content')
    <div class="search-container">
        <form action="{{ route('questions.index') }}" method="GET" class="search-form">
            <input type="text" name="search" class="search-input" placeholder="Search..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-primary">Search</button>
            @auth
                <button type="button" class="btn btn-secondary" id="askQuestionBtn">Ask a question</button>
            @else
                <button type="button" class="btn btn-secondary" id="askQuestionBtnGuest">Ask a question</button>
            @endauth
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
                <div class="question-card" data-url="{{ route('questions.show', $question) }}">

                    <div class="question-header">
                        <h2 class="question-title">
                            <a href="{{ route('questions.show', $question) }}">{{ $question->title }}</a>
                        </h2>
                        <span class="question-status status-{{ $question->status }}">{{ $question->status }}</span>
                    </div>
                    
                    <div class="question-content">
                        {{ \Illuminate\Support\Str::limit(strip_tags($question->content), 200) }}
                    </div>

                    <div class="question-meta">
                        <span>Posted by <span class="question-author">{{ $question->user->name }}</span></span>
                        <span>{{ $question->created_at->diffForHumans() }}</span>
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
                    <button type="button" class="btn btn-primary" id="askQuestionBtnEmpty">Ask the first question</button>
                @else
                    <button type="button" class="btn btn-primary" id="askQuestionBtnEmptyGuest">Ask the first question</button>
                    <p>
                        <a href="{{ route('login') }}">Login</a> or 
                        <a href="{{ route('register') }}">Register</a> to ask a question.
                    </p>
                @endauth
            </div>
        @endif
    </div>

    <!-- Ask Question Modal -->
    @auth
    <div id="askQuestionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Ask a new question</h2>
                <span class="modal-close">&times;</span>
            </div>
            <form id="askQuestionForm" action="{{ route('questions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modal-title" class="form-label">Title of the question</label>
                        <input type="text" name="title" id="modal-title" class="form-control" required minlength="5" maxlength="255">
                        <span class="form-text">The title should be clear and concise (5-255 characters).</span>
                        <span class="form-text text-danger" id="title-error" style="display: none;"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="modal-content" class="form-label">Question description</label>
                        <textarea name="content" id="modal-content" class="form-control" rows="8" required minlength="10"></textarea>
                        <span class="form-text">Please describe your question in detail in order to get the most accurate answer possible.</span>
                        <span class="form-text text-danger" id="content-error" style="display: none;"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" id="modalCancelBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Ask a question</button>
                </div>
            </form>
        </div>
    </div>
    @endauth
@endsection

@section('scripts')
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                popup: 'swal2-toast-custom'
            }
        });
    @endif

    @if($errors->any())
        // Show modal again if there are validation errors
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
    </script>
@endsection