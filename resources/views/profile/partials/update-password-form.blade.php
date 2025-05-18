<section class="profile-form">
    <header class="profile-header">
        <h2>{{ __('Update Password') }}</h2>
        <p>{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="p-6 space-y-6">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
            <input id="current_password" name="current_password" type="password" class="form-input" autocomplete="current-password" placeholder="{{ __('Current Password') }}" />
            @error('current_password')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">{{ __('New Password') }}</label>
            <input id="password" name="password" type="password" class="form-input" autocomplete="new-password" placeholder="{{ __('New Password') }}" />
            @error('password')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="form-input" autocomplete="new-password" placeholder="{{ __('Confirm Password') }}" />
        </div>

        <div class="flex justify-end space-x-3">
            <button type="submit" class="btn-profile btn-profile-primary">
                {{ __('Save') }}
            </button>
        </div>
    </form>
</section>
