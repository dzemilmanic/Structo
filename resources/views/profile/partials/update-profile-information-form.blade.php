<section class="profile-form">
    <header class="profile-header">
        <h2>{{ __('Profile Information') }}</h2>
        <p>{{ __("Update your account's profile information and email address.") }}</p>
    </header>

    <div class="profile-section-content">
        <!-- Display Mode -->
        <div class="profile-info">
            <div class="profile-display">
                <div class="profile-value">
                    <span class="profile-label">{{ __('Name') }}</span>
                    <span class="profile-text">{{ $user->name }}</span>
                </div>
                <button class="edit-icon edit-toggle" type="button" aria-label="Edit name">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                    </svg>
                </button>
            </div>
            
            <div class="profile-display">
                <div class="profile-value">
                    <span class="profile-label">{{ __('Email') }}</span>
                    <span class="profile-text">{{ $user->email }}</span>
                </div>
            </div>
            
            @if (session('status') === 'profile-updated')
                <p class="form-help text-green-600 flash-message" role="status" aria-live="polite">{{ __('Profile information updated successfully.') }}</p>
            @endif
        </div>

        <!-- Edit Mode -->
        <form method="POST" action="{{ route('profile.update') }}" class="profile-edit-form" novalidate>
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    class="form-input @error('name') form-input-error @enderror"
                    value="{{ old('name', $user->name) }}"
                    required
                    autofocus
                    autocomplete="name"
                    aria-describedby="name-error"
                />
                @error('name')
                    <div id="name-error" class="form-error" role="alert">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    class="form-input @error('email') form-input-error @enderror"
                    value="{{ old('email', $user->email) }}"
                    required
                    autocomplete="username"
                    aria-describedby="email-error"
                />
                @error('email')
                    <div id="email-error" class="form-error" role="alert">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group mt-6 flex items-center space-x-4" style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn-profile btn-profile-primary">
                    {{ __('Save') }}
                </button>
                <button type="button" class="btn-profile btn-profile-secondary cancel-edit">
                    {{ __('Cancel') }}
                </button>
            </div>
        </form>
    </div>
</section>