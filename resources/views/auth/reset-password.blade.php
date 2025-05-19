@extends('layouts.auth')

@section('auth-header')
<div class="auth-header">
    <h1>{{ __('Reset Password') }}</h1>
</div>
@endsection

@section('content')
<form method="POST" action="{{ route('password.store') }}">
    @csrf
    
    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $request->route('token') }}">
    
    <!-- Email Address -->
    <div class="form-group">
        <label for="email" class="form-label">{{ __('Email') }}</label>
        <input id="email" class="form-control" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <!-- Password -->
    <div class="form-group">
        <label for="password" class="form-label">{{ __('Password') }}</label>
        <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <!-- Confirm Password -->
    <div class="form-group">
        <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
        <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
        @error('password_confirmation')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            {{ __('Reset Password') }}
        </button>
    </div>
</form>
@endsection