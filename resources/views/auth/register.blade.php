@extends('layouts.app')

@section('title', 'Register - Structo')

@section('content')
<x-guest-layout>
    @vite(['resources/js/app.js', 'resources/css/login.css', 'resources/js/login.js'])
    <!-- Session Status -->
    <x-auth-session-status class="session-status" :status="session('status')" />

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>Create Account</h1>
                <p>Sign up for your new account</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="login-form" id="registerForm">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <x-input-label for="name" :value="__('Name')" />
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/></svg>
                        </span>
                        <x-text-input id="name" class="form-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="input-error" />
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <x-input-label for="email" :value="__('Email')" />
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        </span>
                        <x-text-input id="email" class="form-input" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="input-error" />
                </div>

                <!-- Password -->
                <div class="form-group">
                    <x-input-label for="password" :value="__('Password')" />
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <x-text-input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" />
                    </div>
                    
                    <!-- Password Requirements -->
                    <div class="password-requirements" id="passwordRequirements" style="display: none;">
                        <div class="requirement-item" id="lengthRequirement">
                            <svg class="requirement-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M8 12l2 2 4-4"/>
                            </svg>
                            <span>The password must be at least 8 characters long</span>
                        </div>
                        <div class="requirement-item" id="uppercaseRequirement">
                            <svg class="requirement-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M8 12l2 2 4-4"/>
                            </svg>
                            <span>The password must contain at least one uppercase letter.</span>
                        </div>
                        <div class="requirement-item" id="numberRequirement">
                            <svg class="requirement-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M8 12l2 2 4-4"/>
                            </svg>
                            <span>The password must contain at least one number.</span>
                        </div>
                    </div>
                    
                    <x-input-error :messages="$errors->get('password')" class="input-error" />
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <x-text-input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" />
                    </div>
                    
                    <!-- Password Match Indicator -->
                    <div class="password-match-indicator" id="passwordMatchIndicator" style="display: none;">
                        <div class="match-item" id="passwordMatch">
                            <svg class="requirement-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M8 12l2 2 4-4"/>
                            </svg>
                            <span>Passwords match.</span>
                        </div>
                    </div>
                    
                    <x-input-error :messages="$errors->get('password_confirmation')" class="input-error" />
                </div>

                <div class="form-footer">
                    <a class="forgot-password" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <button type="submit" class="login-button" id="registerButton" disabled>
                        {{ __('Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
@endsection