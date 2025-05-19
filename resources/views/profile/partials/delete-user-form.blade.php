<section class="profile-form">
    <header class="profile-header">
        <h2>{{ __('Delete Account') }}</h2>
        <p>{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}</p>
    </header>

    <div class="profile-section-content">
        <button class="btn-profile btn-profile-danger delete-account-btn" type="button" onclick="document.getElementById('delete-account-modal').style.display='flex'">
            {{ __('Delete Account') }}
        </button>
    </div>

    <!-- Delete Account Modal -->
    <div id="delete-account-modal" class="modal-backdrop" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="font-size: 1.25rem; font-weight: 600;">{{ __('Are you sure you want to delete your account?') }}</h3>
            </div>
            
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                
                <div class="modal-body">
                    <p style="margin-bottom: 1rem; color: var(--neutral-medium);">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </p>
                    
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="form-input @error('password', 'userDeletion') form-input-error @enderror"
                            placeholder="{{ __('Password') }}"
                        />
                        
                        @error('password', 'userDeletion')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-profile btn-profile-secondary" onclick="document.getElementById('delete-account-modal').style.display='none'">
                        {{ __('Cancel') }}
                    </button>
                    
                    <button type="submit" class="btn-profile btn-profile-danger">
                        {{ __('Delete Account') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>