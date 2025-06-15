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
                        <button type="button" class="btn btn-accent btn-lg" data-bs-toggle="modal" data-bs-target="#professionalRequestModal">
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

@auth
    @if(auth()->user()->role !== 'profi' && auth()->user()->role !== 'admin')
        <!-- Professional Request Modal -->
        <div class="modal fade" id="professionalRequestModal" tabindex="-1" aria-labelledby="professionalRequestModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="professionalRequestModalLabel">
                            <svg class="modal-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Become a Professional
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('profi-requests.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <ul class="error-list">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="specialization" class="form-label">
                                    <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6z"/>
                                    </svg>
                                    Specialization *
                                </label>
                                <input type="text" name="specialization" id="specialization" class="form-control" 
                                       placeholder="e.g., Full Stack Developer, UI/UX Designer, Data Scientist" 
                                       value="{{ old('specialization') }}" required>
                                <small class="form-text">Describe your professional expertise and main area of specialization</small>
                            </div>

                            <div class="form-group">
                                <label for="image" class="form-label">
                                    <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Professional Proof (Optional)
                                </label>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                <small class="form-text">Upload a certificate, diploma, or other proof of your professional qualifications (Max: 2MB)</small>
                            </div>

                            <div class="professional-benefits">
                                <h6 class="benefits-title">Professional Benefits:</h6>
                                <ul class="benefits-list">
                                    <li>
                                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Verified professional badge
                                    </li>
                                    <li>
                                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Enhanced profile visibility
                                    </li>
                                    <li>
                                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Access to professional features
                                    </li>
                                    <li>
                                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Priority in search results
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Send Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endauth

<script>
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>
@endsection