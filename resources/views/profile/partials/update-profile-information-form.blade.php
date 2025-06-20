<section class="profile-form">
    <header class="profile-header">
        <h2>{{ __('Profile Information') }}</h2>
        <p>{{ __("Update your account's profile information and personal details.") }}</p>
    </header>

    <div class="profile-section-content">
        <!-- Photo Section -->
        <div class="profile-photo-section">
            <div class="profile-photo-container">
                @if($user->photo)
                    @php
                        // Check if photo is already a full URL or just a path
                        if (str_contains($user->photo, 'amazonaws.com') || str_contains($user->photo, 'http')) {
                            $photoUrl = $user->photo;
                        } else {
                            // Generate full S3 URL from path
                            // Remove leading slash if present
                            $photoPath = ltrim($user->photo, '/');
                            $bucket = config('filesystems.disks.s3.bucket') ?? config('filesystems.disks.profile_photos.bucket');
                            $region = config('filesystems.disks.s3.region') ?? config('filesystems.disks.profile_photos.region');
                            $photoUrl = 'https://' . $bucket . '.s3.' . $region . '.amazonaws.com/' . $photoPath;
                        }
                    @endphp
                    <img src="{{ $photoUrl }}" alt="Profile Photo" class="profile-photo" onerror="this.src='{{ asset('images/default-avatar.png') }}'; this.onerror=null;">
                @else
                    <div class="profile-photo-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                @endif
                <button class="profile-photo-edit edit-toggle" type="button" aria-label="Edit photo" data-field="photo">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                    </svg>
                </button>
            </div>

            <!-- Photo Edit Form -->
            <form method="POST" action="{{ route('profile.update') }}" class="profile-edit-form" id="edit-form-photo" enctype="multipart/form-data" style="display: none;">
                @csrf
                @method('PATCH')
                <!-- Add hidden fields to preserve other data -->
                <input type="hidden" name="name" value="{{ $user->name }}">
                <input type="hidden" name="email" value="{{ $user->email }}">
                <input type="hidden" name="specialization" value="{{ $user->specialization }}">
                <input type="hidden" name="bio" value="{{ $user->bio }}">
                <input type="hidden" name="phone" value="{{ $user->phone }}">
                <input type="hidden" name="location" value="{{ $user->location }}">
                
                <div class="form-group">
                    <label for="photo" class="form-label">{{ __('Profile Photo') }}</label>
                    <input
                        id="photo"
                        name="photo"
                        type="file"
                        class="form-input @error('photo') form-input-error @enderror"
                        accept="image/*"
                        aria-describedby="photo-error"
                    />
                    <small class="form-help">{{ __('Upload a profile photo (JPG, PNG, max 2MB)') }}</small>
                    <small class="form-help text-info">{{ __('Photo will be stored securely on AWS S3') }}</small>
                    @error('photo')
                        <div id="photo-error" class="form-error" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group" style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="submit" class="btn-profile btn-profile-primary">
                        {{ __('Update Photo') }}
                    </button>
                    <button type="button" class="btn-profile btn-profile-secondary cancel-edit" data-field="photo">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </form>
        </div>

        <div class="profile-fields-grid">
            <!-- Name Section -->
            <div class="profile-display">
                <div class="profile-value">
                    <span class="profile-label">{{ __('Name') }}</span>
                    <span class="profile-text" id="display-name">{{ $user->name }}</span>
                </div>
                <button class="edit-icon edit-toggle" type="button" aria-label="Edit name" data-field="name">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                    </svg>
                </button>
            </div>

            <!-- Name Edit Form -->
            <form method="POST" action="{{ route('profile.update') }}" class="profile-edit-form" id="edit-form-name" style="display: none;">
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
                        autocomplete="name"
                        aria-describedby="name-error"
                        data-original-value="{{ $user->name }}"
                    />
                    @error('name')
                        <div id="name-error" class="form-error" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group" style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" class="btn-profile btn-profile-primary">
                        {{ __('Update') }}
                    </button>
                    <button type="button" class="btn-profile btn-profile-secondary cancel-edit" data-field="name">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </form>
            
            <!-- Email Section -->
            <div class="profile-display">
                <div class="profile-value">
                    <span class="profile-label">{{ __('Email') }}</span>
                    <span class="profile-text" id="display-email">{{ $user->email }}</span>
                </div>
                <button class="edit-icon edit-toggle" type="button" aria-label="Edit email" data-field="email">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                    </svg>
                </button>
            </div>

            <!-- Email Edit Form -->
            <form method="POST" action="{{ route('profile.update') }}" class="profile-edit-form" id="edit-form-email" style="display: none;">
                @csrf
                @method('PATCH')
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
                        data-original-value="{{ $user->email }}"
                    />
                    @error('email')
                        <div id="email-error" class="form-error" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group" style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" class="btn-profile btn-profile-primary">
                        {{ __('Update') }}
                    </button>
                    <button type="button" class="btn-profile btn-profile-secondary cancel-edit" data-field="email">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </form>

            <!-- Specialization Section -->
            <div class="profile-display">
                <div class="profile-value">
                    <span class="profile-label">{{ __('Specialization') }}</span>
                    <span class="profile-text {{ !$user->specialization ? 'empty' : '' }}" id="display-specialization">
                        {{ $user->specialization ?? __('Not specified') }}
                    </span>
                </div>
                <button class="edit-icon edit-toggle" type="button" aria-label="Edit specialization" data-field="specialization">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                    </svg>
                </button>
            </div>

            <!-- Specialization Edit Form -->
            <form method="POST" action="{{ route('profile.update') }}" class="profile-edit-form" id="edit-form-specialization" style="display: none;">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="specialization" class="form-label">{{ __('Specialization') }}</label>
                    <input
                        id="specialization"
                        name="specialization"
                        type="text"
                        class="form-input @error('specialization') form-input-error @enderror"
                        value="{{ old('specialization', $user->specialization) }}"
                        placeholder="{{ __('e.g., Web Developer, Designer, Teacher') }}"
                        autocomplete="organization-title"
                        aria-describedby="specialization-error"
                        data-original-value="{{ $user->specialization }}"
                    />
                    @error('specialization')
                        <div id="specialization-error" class="form-error" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group" style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" class="btn-profile btn-profile-primary">
                        {{ __('Update') }}
                    </button>
                    <button type="button" class="btn-profile btn-profile-secondary cancel-edit" data-field="specialization">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </form>

            <!-- Bio Section -->
            <div class="profile-display">
                <div class="profile-value">
                    <span class="profile-label">{{ __('Bio') }}</span>
                    <span class="profile-text profile-bio {{ !$user->bio ? 'empty' : '' }}" id="display-bio">
                        {{ $user->bio ?? __('Tell us about yourself...') }}
                    </span>
                </div>
                <button class="edit-icon edit-toggle" type="button" aria-label="Edit bio" data-field="bio">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                    </svg>
                </button>
            </div>

            <!-- Bio Edit Form -->
            <form method="POST" action="{{ route('profile.update') }}" class="profile-edit-form" id="edit-form-bio" style="display: none;">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="bio" class="form-label">{{ __('Bio') }}</label>
                    <textarea
                        id="bio"
                        name="bio"
                        rows="4"
                        class="form-input @error('bio') form-input-error @enderror"
                        placeholder="{{ __('Tell us about yourself, your interests, experience...') }}"
                        aria-describedby="bio-error"
                        data-original-value="{{ $user->bio }}"
                    >{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <div id="bio-error" class="form-error" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group" style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" class="btn-profile btn-profile-primary">
                        {{ __('Update') }}
                    </button>
                    <button type="button" class="btn-profile btn-profile-secondary cancel-edit" data-field="bio">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </form>

            <!-- Phone Section -->
            <div class="profile-display">
                <div class="profile-value">
                    <span class="profile-label">{{ __('Phone') }}</span>
                    <span class="profile-text {{ !$user->phone ? 'empty' : '' }}" id="display-phone">
                        {{ $user->phone ?? __('Not specified') }}
                    </span>
                </div>
                <button class="edit-icon edit-toggle" type="button" aria-label="Edit phone" data-field="phone">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                    </svg>
                </button>
            </div>

            <!-- Phone Edit Form -->
            <form method="POST" action="{{ route('profile.update') }}" class="profile-edit-form" id="edit-form-phone" style="display: none;">
                @csrf
                @method('PATCH')
                <div class="form-group">
                <label for="phone" class="form-label">{{ __('Phone') }}</label>
                    <input
                        id="phone"
                        name="phone"
                        type="tel"
                        class="form-input @error('phone') form-input-error @enderror"
                        value="{{ old('phone', $user->phone) }}"
                        placeholder="{{ __('e.g., +1 (555) 123-4567') }}"
                        autocomplete="tel"
                        aria-describedby="phone-error"
                        data-original-value="{{ $user->phone }}"
                    />
                    @error('phone')
                        <div id="phone-error" class="form-error" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group" style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" class="btn-profile btn-profile-primary">
                        {{ __('Update') }}
                    </button>
                    <button type="button" class="btn-profile btn-profile-secondary cancel-edit" data-field="phone">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </form>

            <!-- Location Section -->
            <div class="profile-display">
                <div class="profile-value">
                    <span class="profile-label">{{ __('Location') }}</span>
                    <span class="profile-text {{ !$user->location ? 'empty' : '' }}" id="display-location">
                        {{ $user->location ?? __('Not specified') }}
                    </span>
                </div>
                <button class="edit-icon edit-toggle" type="button" aria-label="Edit location" data-field="location">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                    </svg>
                </button>
            </div>

            <!-- Location Edit Form -->
            <form method="POST" action="{{ route('profile.update') }}" class="profile-edit-form" id="edit-form-location" style="display: none;">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="location" class="form-label">{{ __('Location') }}</label>
                    <input
                        id="location"
                        name="location"
                        type="text"
                        class="form-input @error('location') form-input-error @enderror"
                        value="{{ old('location', $user->location) }}"
                        placeholder="{{ __('e.g., New York, NY') }}"
                        autocomplete="address-level2"
                        aria-describedby="location-error"
                        data-original-value="{{ $user->location }}"
                    />
                    @error('location')
                        <div id="location-error" class="form-error" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group" style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <button type="submit" class="btn-profile btn-profile-primary">
                        {{ __('Update') }}
                    </button>
                    <button type="button" class="btn-profile btn-profile-secondary cancel-edit" data-field="location">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Handle Flash Messages with SweetAlert2 -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Success message
    @if (session('status') === 'profile-updated')
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Profile updated successfully.',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif
    
    // Error message
    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#d33'
        });
    @endif
    
    // Password updated message
    @if (session('status') === 'password-updated')
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Password updated successfully.',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif
    
    // Validation errors
    @if ($errors->any())
        let errorMessages = '';
        @foreach ($errors->all() as $error)
            errorMessages += '• {{ $error }}\n';
        @endforeach
        
        Swal.fire({
            icon: 'error',
            title: 'Validation Errors',
            text: errorMessages,
            confirmButtonText: 'OK',
            confirmButtonColor: '#d33'
        });
    @endif
});
</script>