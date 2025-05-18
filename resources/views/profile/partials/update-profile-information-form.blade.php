<section class="profile-form">
    <header class="profile-header">
        <h2>{{ __('Profile Information') }}</h2>
        <p>{{ __("Update your account's profile information and email address.") }}</p>
    </header>

    <form method="POST" action="{{ route('profile.update') }}" class="mt-6" novalidate>
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

        <div class="form-group mt-4">
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

        <div class="form-group mt-6 flex items-center space-x-4">
            <button type="submit" class="btn-profile btn-profile-primary">
                {{ __('Save') }}
            </button>
            @if (session('status') === 'profile-updated')
                <p class="form-help text-green-600" role="status" aria-live="polite">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
