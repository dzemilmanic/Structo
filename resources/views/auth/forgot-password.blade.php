@extends('layouts.auth')

@section('auth-header')
<div class="auth-header">
    <h1>{{ __('Forgot Password') }}</h1>
</div>
@endsection

@section('content')
<div class="auth-description">
    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
</div>

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    
    <!-- Email Address -->
    <div class="form-group">
        <label for="email" class="form-label">{{ __('Email') }}</label>
        <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            {{ __('Email Password Reset Link') }}
        </button>
    </div>
</form>
@endsection