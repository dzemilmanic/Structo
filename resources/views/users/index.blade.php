@extends('layouts.app')
@vite(['resources/css/users.css'])
@section('title', 'Users - Structo')

@section('content')
<div class="users-container">
    <div class="users-header">
        <h1 class="users-title">Professional Users</h1>
        <p class="users-subtitle">Meet our verified professionals</p>
        
        @auth
            @if(auth()->user()->role !== 'profi' && auth()->user()->role !== 'admin')
                @php
                    $hasPendingRequest = \App\Models\ProfiRequest::where('user_id', auth()->id())
                        ->where('status', 'pending')
                        ->exists();
                @endphp
                
                @if(!$hasPendingRequest)
                    <div class="become-professional-section">
                        <button type="button" class="btn btn-accent btn-lg" id="becomeProfessionalBtn">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Become a Professional
                        </button>
                    </div>
                @else
                    <div class="pending-request-notice">
                        <svg class="notice-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Your professional request is pending review</span>
                    </div>
                @endif
            @endif
        @endauth
    </div>

    <div class="search-container">
        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" id="searchInput" placeholder="Search professionals by name, specialization, or location..." class="search-input">
    </div>

    <div class="users-grid" id="usersGrid">
        @forelse($users->where('role', 'profi') as $user)
            <div class="user-card" data-search="{{ strtolower($user->name . ' ' . ($user->lastname ?? '') . ' ' . ($user->specialization ?? '') . ' ' . ($user->location ?? '') . ' ' . $user->email) }}">
                <div class="user-avatar">
                    @if($user->avatar)
                        <img src="{{ Storage::disk('s3')->url($user->avatar) }}" alt="{{ $user->name }}" class="avatar-image">
                    @else
                        <div class="avatar-placeholder">
                            {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr($user->lastname ?? '', 0, 1)) }}
                        </div>
                    @endif
                    <div class="professional-badge">
                        <svg class="badge-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <div class="user-info">
                    <h3 class="user-name">{{ $user->name }} {{ $user->lastname ?? '' }}</h3>
                    <p class="user-email">{{ $user->email }}</p>
                    
                    @if($user->specialization)
                        <p class="user-specialization">{{ $user->specialization }}</p>
                    @endif
                    
                    @if($user->location)
                        <p class="user-location">
                            <svg class="location-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $user->location }}
                        </p>
                    @endif

                    <div class="user-stats">
                        @if(($user->projects_count ?? 0) > 0)
                            <span class="stat-item">
                                <span class="stat-number">{{ $user->projects_count }}</span>
                                <span class="stat-label">Projects</span>
                            </span>
                        @endif
                        
                        @if(($user->rating ?? 0) > 0)
                            <span class="stat-item">
                                <span class="stat-number">{{ number_format($user->rating, 1) }}</span>
                                <span class="stat-label">Rating</span>
                            </span>
                        @endif
                        
                        <span class="stat-item">
                            <span class="stat-number">{{ $user->created_at->format('Y') }}</span>
                            <span class="stat-label">Joined</span>
                        </span>
                    </div>
                </div>

                <div class="user-actions">
                    <a href="{{ route('users.show', $user) }}" class="btn btn-primary btn-sm">View Profile</a>
                    @if($user->phone)
                        <a href="tel:{{ $user->phone }}" class="btn btn-outline btn-sm">Contact</a>
                    @endif
                </div>
            </div>
        @empty
            <div class="no-users">
                <div class="no-users-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="no-users-title">No Professional Users Found</h3>
                <p class="no-users-text">We couldn't find any professional users matching your search criteria. Try adjusting your search terms or check back later.</p>
            </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const usersGrid = document.getElementById('usersGrid');
    const userCards = usersGrid.querySelectorAll('.user-card');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        let visibleCount = 0;

        userCards.forEach(card => {
            const searchData = card.getAttribute('data-search');
            if (searchData.includes(searchTerm)) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Show/hide no results message
        const noUsersElement = usersGrid.querySelector('.no-users');
        if (visibleCount === 0 && searchTerm !== '') {
            if (!noUsersElement) {
                const noResults = document.createElement('div');
                noResults.className = 'no-users search-no-results';
                noResults.innerHTML = `
                    <div class="no-users-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="no-users-title">No Results Found</h3>
                    <p class="no-users-text">No professionals match your search criteria. Try different keywords.</p>
                `;
                usersGrid.appendChild(noResults);
            }
        } else {
            const searchNoResults = usersGrid.querySelector('.search-no-results');
            if (searchNoResults) {
                searchNoResults.remove();
            }
        }
    });

    // Professional request modal
    const becomeProfessionalBtn = document.getElementById('becomeProfessionalBtn');
    
    if (becomeProfessionalBtn) {
        becomeProfessionalBtn.addEventListener('click', function() {
            showProfessionalRequestModal();
        });
    }

    // Show success/error notifications
    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            customClass: {
                popup: 'swal2-toast-custom'
            }
        });
    @endif

    @if(session('error'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: "{{ session('error') }}",
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            customClass: {
                popup: 'swal2-toast-custom'
            }
        });
    @endif

    @if($errors->any())
        let errorMessages = '';
        @foreach($errors->all() as $error)
            errorMessages += '• {{ $error }}\n';
        @endforeach
        
        Swal.fire({
            icon: 'error',
            title: 'Validation Errors',
            text: errorMessages,
            confirmButtonText: 'OK',
            customClass: {
                popup: 'swal2-popup-custom',
                confirmButton: 'swal2-confirm-custom'
            }
        });
    @endif
});

function showProfessionalRequestModal() {
    Swal.fire({
        title: '<div class="swal-title-with-icon"><svg class="swal-title-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>Become a Professional</div>',
        html: `
            <form id="professionalRequestForm" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <div class="swal-form-group">
                    <label for="swal-specialization" class="swal-form-label">
                        <svg class="swal-label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6z"/>
                        </svg>
                        Specialization *
                    </label>
                    <input type="text" name="specialization" id="swal-specialization" class="swal-input" 
                           placeholder="e.g., Full Stack Developer, UI/UX Designer, Data Scientist" required>
                    <small class="swal-form-text">Describe your professional expertise and main area of specialization</small>
                </div>

                <div class="swal-form-group">
                    <label for="swal-image" class="swal-form-label">
                        <svg class="swal-label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Professional Proof (Optional)
                    </label>
                    <input type="file" name="image" id="swal-image" class="swal-file-input" accept="image/*">
                    <small class="swal-form-text">Upload a certificate, diploma, or other proof of your professional qualifications (Max: 2MB)</small>
                </div>

                <div class="swal-professional-benefits">
                    <h6 class="swal-benefits-title">Professional Benefits:</h6>
                    <ul class="swal-benefits-list">
                        <li>
                            <svg class="swal-benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Verified professional badge
                        </li>
                        <li>
                            <svg class="swal-benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Enhanced profile visibility
                        </li>
                        <li>
                            <svg class="swal-benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Access to professional features
                        </li>
                        <li>
                            <svg class="swal-benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Priority in search results
                        </li>
                    </ul>
                </div>
            </form>
        `,
        width: '600px',
        showCancelButton: true,
        confirmButtonText: '<svg class="swal-btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>Send Request',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        customClass: {
            popup: 'swal2-popup-professional',
            confirmButton: 'swal2-confirm-professional',
            cancelButton: 'swal2-cancel-professional',
            htmlContainer: 'swal2-html-professional'
        },
        preConfirm: () => {
            const form = document.getElementById('professionalRequestForm');
            const specialization = document.getElementById('swal-specialization').value;
            const imageFile = document.getElementById('swal-image').files[0];
            
            if (!specialization.trim()) {
                Swal.showValidationMessage('Please enter your specialization');
                return false;
            }
            
            if (imageFile && imageFile.size > 2097152) { // 2MB in bytes
                Swal.showValidationMessage('Image file size must be less than 2MB');
                return false;
            }
            
            return {
                specialization: specialization,
                image: imageFile
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            submitProfessionalRequest(result.value);
        }
    });
}

function submitProfessionalRequest(data) {
    // Show loading
    Swal.fire({
        title: 'Submitting Request...',
        text: 'Please wait while we process your professional request.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Create FormData
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('specialization', data.specialization);
    if (data.image) {
        formData.append('image', data.image);
    }

    // Submit form
    fetch('{{ route("profi-requests.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Request Submitted!',
                text: data.message || 'Your professional request has been submitted successfully and is pending review.',
                confirmButtonText: 'Great!',
                customClass: {
                    popup: 'swal2-popup-custom',
                    confirmButton: 'swal2-confirm-custom'
                }
            }).then(() => {
                // Reload page to update UI
                window.location.reload();
            });
        } else {
            throw new Error(data.message || 'An error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Submission Failed',
            text: error.message || 'Failed to submit your request. Please try again.',
            confirmButtonText: 'OK',
            customClass: {
                popup: 'swal2-popup-custom',
                confirmButton: 'swal2-confirm-custom'
            }
        });
    });
}
</script>

<style>
/* SweetAlert2 Custom Styles */
.swal2-toast-custom {
    font-family: inherit;
}

.swal2-popup-professional {
    border-radius: 12px;
    padding: 0;
}

.swal2-html-professional {
    padding: 0 20px 20px 20px;
}

.swal-title-with-icon {
    display: flex;
    align-items: center;
    gap: 12px;
    justify-content: center;
}

.swal-title-icon {
    width: 24px;
    height: 24px;
    color: #FF6B35;
}

.swal-form-group {
    margin-bottom: 20px;
    text-align: left;
}

.swal-form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 14px;
}

.swal-label-icon {
    width: 16px;
    height: 16px;
    color: #FF6B35;
}

.swal-input {
    width: 100%;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.swal-input:focus {
    outline: none;
    border-color: #FF6B35;
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
}

.swal-file-input {
    width: 100%;
    padding: 8px;
    border: 2px dashed #e0e0e0;
    border-radius: 8px;
    background: #f9f9f9;
    cursor: pointer;
    transition: all 0.3s ease;
}

.swal-file-input:hover {
    border-color: #FF6B35;
    background: #fff5f2;
}

.swal-form-text {
    display: block;
    margin-top: 4px;
    font-size: 12px;
    color: #666;
}

.swal-professional-benefits {
    background: #f8f9fa;
    padding: 16px;
    border-radius: 8px;
    margin-top: 20px;
}

.swal-benefits-title {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin: 0 0 12px 0;
}

.swal-benefits-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.swal-benefits-list li {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-size: 13px;
    color: #555;
}

.swal-benefit-icon {
    width: 16px;
    height: 16px;
    color: #28a745;
    flex-shrink: 0;
}

.swal2-confirm-professional {
    background: linear-gradient(135deg, #FF6B35, #E85D2C) !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 12px 24px !important;
    font-weight: 600 !important;
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
}

.swal2-cancel-professional {
    background: #6c757d !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 12px 24px !important;
    font-weight: 600 !important;
}

.swal-btn-icon {
    width: 16px;
    height: 16px;
}

.swal2-popup-custom {
    border-radius: 12px;
}

.swal2-confirm-custom {
    background: linear-gradient(135deg, #FF6B35, #E85D2C) !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 12px 24px !important;
    font-weight: 600 !important;
}
</style>
@endsection