@extends('layouts.app')
@vite(['resources/css/qa.css'])
@section('title', 'Izmeni pitanje')

@section('content')
    <div class="form-container">
        <h1 class="form-title">Izmeni pitanje</h1>
        
        <form action="{{ route('questions.update', $question) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="title" class="form-label">Naslov pitanja</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $question->title) }}" required minlength="5" maxlength="255">
                
                @error('title')
                    <span class="form-text text-danger">{{ $message }}</span>
                @else
                    <span class="form-text">Naslov treba da bude jasan i koncizan (5-255 karaktera).</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="content" class="form-label">Opis pitanja</label>
                <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="10" required minlength="10">{{ old('content', $question->content) }}</textarea>
                
                @error('content')
                    <span class="form-text text-danger">{{ $message }}</span>
                @else
                    <span class="form-text">Detaljno opišite svoje pitanje kako bi dobili što precizniji odgovor.</span>
                @enderror
            </div>
            
            <div class="form-actions">
                <a href="{{ route('questions.show', $question) }}" class="btn btn-outline">Odustani</a>
                <button type="submit" class="btn btn-primary">Sačuvaj izmene</button>
            </div>
        </form>
    </div>
@endsection