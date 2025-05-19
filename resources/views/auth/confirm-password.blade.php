@extends('layouts.auth')

@section('auth-header')
<div class="auth-header">
    <h1>{{ __('Confirm Password') }}</h1>
</div>
@endsection

@section('content')
<div class="auth-description">
    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
</div>

<form method="POST" action="{{ route('password.confirm') }}">
    @csrf
    
    <!-- Password -->
    <div class="form-group">
        <label for="password" class="form-label">{{ __('Password') }}</label>
        <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            {{ __('Confirm') }}
        </button>
    </div>
</form>
@endsection