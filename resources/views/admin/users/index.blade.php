@extends('layouts.app')
@vite(['resources/css/admin-users.css'])
@vite(['resources/js/allusers-admin.js'])
@section('title', 'All Users - Admin')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
<div class="admin-users-container">
    <!-- Hidden session message data for JavaScript -->
    @if(session('success'))
        <div data-session-success="{{ session('success') }}" style="display: none;"></div>
    @endif
    @if(session('error'))
        <div data-session-error="{{ session('error') }}" style="display: none;"></div>
    @endif

    <div class="admin-header">
    <section class="all-users-header">
    <div class="all-users-header-content">
        <h1 class="all-users-title">
            <i class="fas fa-users"></i>
            All Users Management
        </h1>
        
    </div>
    </section>
        <p class="admin-subtitle">Manage all users, professionals, and administrators</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card total">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $users->total() }}</h3>
                <p>Total Users</p>
                <small>All registered users</small>
            </div>
        </div>

        <div class="stat-card admin">
            <div class="stat-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $adminCount }}</h3>
                <p>Administrators</p>
                <small>System admins</small>
            </div>
        </div>

        <div class="stat-card profi">
            <div class="stat-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $profiCount }}</h3>
                <p>Professionals</p>
                <small>Verified professionals</small>
            </div>
        </div>

        <div class="stat-card user">
            <div class="stat-icon">
                <i class="fas fa-user"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $userCount }}</h3>
                <p>Regular Users</p>
                <small>Standard users</small>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="search-filters-section">
        <form method="GET" action="{{ route('admin.users.index') }}" class="search-form">
            <div class="search-row">
                <div class="search-group">
                    <div class="search-input-container">
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" class="search-input" 
                               placeholder="Search by name, email, specialization, or location..." 
                               value="{{ $search }}">
                    </div>
                </div>

                <div class="filter-group">
                    <select name="role" class="filter-select">
                        <option value="">All Roles</option>
                        <option value="admin" {{ $roleFilter == 'admin' ? 'selected' : '' }}>Administrators</option>
                        <option value="profi" {{ $roleFilter == 'profi' ? 'selected' : '' }}>Professionals</option>
                        <option value="user" {{ $roleFilter == 'user' ? 'selected' : '' }}>Regular Users</option>
                    </select>
                </div>

                <div class="search-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Header -->
    <div class="users-results-header">
        <h2 class="section-title">All Users</h2>
        <p class="results-count">
            Showing {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} of {{ $users->total() }} {{ Str::plural('user', $users->total()) }}
        </p>
    </div>

    <!-- Users Grid -->
    <div class="users-grid">
        @forelse($users as $user)
            <div class="user-card {{ $user->role }}">
                <div class="user-header">
                    <div class="user-avatar">
                        @if($user->photo)
                            @php
                                if (str_contains($user->photo, 'amazonaws.com') || str_contains($user->photo, 'http')) {
                                    $photoUrl = $user->photo;
                                } else {
                                    $photoPath = ltrim($user->photo, '/');
                                    $bucket = config('filesystems.disks.s3.bucket') ?? config('filesystems.disks.profile_photos.bucket');
                                    $region = config('filesystems.disks.s3.region') ?? config('filesystems.disks.profile_photos.region');
                                    $photoUrl = 'https://' . $bucket . '.s3.' . $region . '.amazonaws.com/' . $photoPath;
                                }
                            @endphp
                            <img src="{{ $photoUrl }}" alt="{{ $user->name }}" class="avatar-image" 
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="avatar-placeholder" style="display: none;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr($user->lastname ?? '', 0, 1)) }}
                            </div>
                        @else
                            <div class="avatar-placeholder">
                                {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr($user->lastname ?? '', 0, 1)) }}
                            </div>
                        @endif
                        
                        <div class="role-badge role-{{ $user->role }}">
                            @if($user->role === 'admin')
                                <i class="fas fa-shield-alt"></i>
                            @elseif($user->role === 'profi')
                                <i class="fas fa-star"></i>
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        </div>
                    </div>

                    <div class="user-info">
                        <h3 class="user-name">{{ $user->name }} {{ $user->lastname ?? '' }}</h3>
                        <p class="user-email">{{ $user->email }}</p>
                        <span class="user-role role-{{ $user->role }}">
                            {{ ucfirst($user->role) }}
                            @if($user->role === 'profi' && $user->specialization)
                                - {{ $user->specialization }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="user-details">
                    @if($user->location)
                        <div class="detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $user->location }}</span>
                        </div>
                    @endif
                    
                    @if($user->phone)
                        <div class="detail-item">
                            <i class="fas fa-phone"></i>
                            <span>{{ $user->phone }}</span>
                        </div>
                    @endif

                    <div class="detail-item">
                        <i class="fas fa-calendar"></i>
                        <span>Joined {{ $user->created_at->format('M d, Y') }}</span>
                    </div>

                    @if($user->bio)
                        <div class="user-bio">
                            <p>{{ Str::limit($user->bio, 100) }}</p>
                        </div>
                    @endif
                </div>

                <div class="user-actions">
                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-eye"></i> View Profile
                    </a>
                    
                    @if($user->role === 'profi')
                        <button type="button" 
                                class="btn btn-sm btn-warning user-demote-btn"
                                data-user-id="{{ $user->id }}"
                                data-user-name="{{ $user->name }}">
                            <i class="fas fa-arrow-down"></i> Demote
                        </button>
                    @endif
                    
                    @if($user->id !== Auth::id())
                        <button type="button" 
                                class="btn btn-sm btn-danger user-delete-btn"
                                data-user-id="{{ $user->id }}"
                                data-user-name="{{ $user->name }}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="no-users">
                <div class="no-users-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                    </svg>
                </div>
                <h3 class="no-users-title">No Users Found</h3>
                <p class="no-users-text">No users match your search criteria. Try adjusting your filters.</p>
            </div>
        @endforelse
    </div>

    <!-- Q&A Style Pagination -->
    @if($users->hasPages())
        <div class="qa-pagination-container">
            <div class="pagination">
                {{-- Previous Page Link --}}
                @if ($users->onFirstPage())
                    <button class="pagination-btn prev-btn" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15,18 9,12 15,6"></polyline>
                        </svg>
                        Previous
                    </button>
                @else
                    <a href="{{ $users->appends(request()->query())->previousPageUrl() }}" class="pagination-btn prev-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15,18 9,12 15,6"></polyline>
                        </svg>
                        Previous
                    </a>
                @endif

                {{-- Pagination Elements --}}
                <div class="pagination-numbers">
                    @php
                        $start = max($users->currentPage() - 2, 1);
                        $end = min($start + 4, $users->lastPage());
                        $start = max($end - 4, 1);
                    @endphp

                    @for ($i = $start; $i <= $end; $i++)
                        @if ($i == $users->currentPage())
                            <span class="page-number active">{{ $i }}</span>
                        @else
                            <a href="{{ $users->appends(request()->query())->url($i) }}" class="page-number">{{ $i }}</a>
                        @endif
                    @endfor
                </div>

                {{-- Next Page Link --}}
                @if ($users->hasMorePages())
                    <a href="{{ $users->appends(request()->query())->nextPageUrl() }}" class="pagination-btn next-btn">
                        Next
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9,18 15,12 9,6"></polyline>
                        </svg>
                    </a>
                @else
                    <button class="pagination-btn next-btn" disabled>
                        Next
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9,18 15,12 9,6"></polyline>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Hidden Forms for Actions -->
@foreach($users as $user)
    <form id="delete-form-{{ $user->id }}" method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    
    @if($user->role === 'profi')
        <form id="demote-form-{{ $user->id }}" method="POST" action="{{ route('admin.users.demote', $user) }}" style="display: none;">
            @csrf
            @method('PATCH')
        </form>
    @endif
@endforeach
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection