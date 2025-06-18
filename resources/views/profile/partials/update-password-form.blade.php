<section class="profile-form">
    <header class="profile-header">
        <h2>{{ __('Update Password') }}</h2>
        <p>{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
    </header>

    <div class="profile-section-content">
        <!-- Display Mode -->
        <div class="profile-info">
            <div class="profile-display">
                <div class="profile-value">
                    <span class="profile-text">{{ __('Your password is securely stored.') }}</span>
                </div>
                <button class="edit-icon edit-toggle" type="button" aria-label="Edit password" data-field="password">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                    </svg>
                </button>
            </div>
            
            @if (session('status') === 'password-updated')
                <p class="form-help text-green-600 flash-message" role="status" aria-live="polite">{{ __('Password updated successfully.') }}</p>
            @endif
        </div>

        <!-- Edit Mode -->
        <form method="post" action="{{ route('password.update') }}" class="profile-edit-form">
            @csrf
            @method('put')

            <div class="form-group">
                <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
                <input 
                    id="current_password" 
                    name="current_password" 
                    type="password" 
                    class="form-input @error('current_password') form-input-error @enderror" 
                    autocomplete="current-password" 
                />
                @error('current_password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">{{ __('New Password') }}</label>
                <input 
                    id="password" 
                    name="password" 
                    type="password" 
                    class="form-input @error('password') form-input-error @enderror" 
                    autocomplete="new-password" 
                />
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                <input 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    type="password" 
                    class="form-input" 
                    autocomplete="new-password" 
                />
            </div>

            <div class="form-group" style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn-profile btn-profile-primary">
                    {{ __('Update Password') }}
                </button>
                <button type="button" class="btn-profile btn-profile-secondary cancel-edit">
                    {{ __('Cancel') }}
                </button>
            </div>
        </form>
    </div>
</section>