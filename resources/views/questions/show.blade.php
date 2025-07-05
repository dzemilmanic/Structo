@extends('layouts.app')
@vite(['resources/css/qa.css'])
@vite(['resources/js/qa.js'])
@section('title', $question->title)

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

    <div class="question-detail">
        <div class="question-detail-header">
            <h1 class="question-detail-title">{{ $question->title }}</h1>
            
            <div class="question-detail-meta">
                
                
                

                
            </div>
        </div>
        
        <div class="question-detail-content">
            {!! nl2br(e($question->content)) !!}
        
        </div>
        <div class="question-info">
            <span>Posted by <a href="/user/{{ $question->user->id }}" class="question-author">{{ $question->user->name }}</a></span>
            <span>{{ $question->created_at->format('d.m.Y. H:i') }}</span>
            <span>{{ $question->views }} views</span>
            <span class="question-status status-{{ $question->status }}">{{ $question->status }}</span>       
        </div>
                @auth
                    @can('update', $question)
                        <div class="question-actions">
                            <a href="{{ route('questions.edit', $question) }}" class="btn btn-outline btn-sm">Edit</a>
                            <form action="{{ route('questions.destroy', $question) }}" method="POST" class="d-inline delete-question-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm delete-btn">Delete</button>
                            </form>
                        </div>
                    @endcan
                @endauth
                
    </div>
    
    <div class="answers-section" id="answers">
        <h2 class="answers-title">{{ $question->answers->count() }} Answers</h2>
        
        @foreach ($question->answers as $answer)
            <div class="answer-card {{ $answer->is_solution ? 'solution' : '' }}">
                @if ($answer->is_solution)
                    <div class="solution-badge">Accepted solution</div>
                @endif
                
                <div class="answer-header">
                    <div class="answer-author-info">
                        <a href="/user/{{ $answer->user->id }}" class="answer-author">{{ $answer->user->name }}</a>
                        
                    </div>
                    
                    @auth
                        <div class="answer-actions">
                            @can('update', $answer)
                                <a href="{{ route('answers.edit', $answer) }}" class="btn btn-outline btn-sm">Edit</a>
                                <form action="{{ route('answers.destroy', $answer) }}" method="POST" class="d-inline delete-answer-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline btn-sm delete-btn">Delete</button>
                                </form>
                            @endcan
                            
                            @can('markSolution', $question)
                                @if (!$answer->is_solution && $question->status !== 'resolved')
                                    <form action="{{ route('answers.solution', $answer) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline btn-sm">Mark as a solution</button>
                                    </form>
                                @endif
                            @endcan
                        </div>
                    @endauth
                </div>
                
                <div class="answer-content">
                    {!! nl2br(e($answer->content)) !!}
                    
                </div>
                <div>
                    <span class="answer-date">{{ $answer->created_at->format('d.m.Y. H:i') }}</span>
                </div>
            </div>
        @endforeach
        
        @auth
            <div class="form-container">
                <h3 class="form-title">Your answer</h3>
                
                <form action="{{ route('questions.answers.store', $question) }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <input type="hidden" name="question_id" value="{{ $question->id }}">
                        <label for="content" class="form-label">Answer</label>
                        <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="5" required>{{ old('content') }}</textarea>
                        
                        @error('content')
                            @php $message = $message ?? ''; @endphp
                            <span class="form-text text-danger">{{ $message }}</span>
                        @enderror

                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Send a reply</button>
                    </div>
                </form>
            </div>
        @else
            <div class="login-prompt">
                <p>You must be logged in to answer this question.</p>
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            </div>
        @endauth
    </div>
@endsection

@section('scripts')
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection