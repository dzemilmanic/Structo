<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Structo') - Construction & Architecture Services</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Global SweetAlert CSS -->
    @vite(['resources/css/sweetalert-global.css'])
    
    <!-- Styles -->
    @vite(['resources/js/app.js', 'resources/js/home.js', 'resources/css/app.css'])
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Additional styles specific to pages -->
    @yield('styles')
</head>
<body>
    <!-- Header/Navigation -->
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <div class="site-brand">
                    <a href="/">
                        <span class="logo-icon">BC</span>
                        <span class="logo-text">Structo</span>
                    </a>
                </div>
                
                <nav class="main-nav">
                    <ul class="nav-list">
                        <li class="nav-item {{ request()->is('/') ? 'nav-item-dropdown' : '' }}">
                            <a href="/" class="nav-link">Home</a>
                            @if(request()->is('/'))
                            <ul class="dropdown-menu">
                                <li><a href="#services" class="dropdown-item">Services</a></li>
                                <li><a href="#how-it-works" class="dropdown-item">How It Works</a></li>
                                <li><a href="#professionals" class="dropdown-item">Professionals</a></li>
                                <li><a href="#projects" class="dropdown-item">Projects</a></li>
                            </ul>
                            @endif
                        </li>
                        @auth
                            @if(Auth::user()->role === 'admin')
                                <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">All Users</a></li>
                            @else
                                <li class="nav-item"><a href="/users" class="nav-link">Users</a></li>
                            @endif
                        @else
                            <li class="nav-item"><a href="/users" class="nav-link">Users</a></li>
                        @endauth
                        <li class="nav-item"><a href="/jobs" class="nav-link">Jobs</a></li>
                        <li class="nav-item"><a href="{{ route('questions.index') }}" class="nav-link {{ request()->routeIs('questions.*') ? 'active' : '' }}">Questions</a></li>
                        <li class="nav-item"><a href="/news" class="nav-link {{ request()->is('news*') ? 'active' : '' }}">News</a></li>
                        @auth
                            @if(Auth::user()->role === 'admin')
                                <li class="nav-item"><a href="/admin/profi-requests" class="nav-link {{ request()->is('admin/profi-requests*') ? 'active' : '' }}">Role Requests</a></li>
                            @endif
                        @endauth
                      </ul>
                </nav>
                
                <div class="header-actions">
                    @auth
                        <div class="user-dropdown">
                            <div class="user-info">
                                <div class="user-avatar">
                                    @if(Auth::user()->photo)
                                        @php
                                            // Check if photo is already a full URL or just a path
                                            if (str_contains(Auth::user()->photo, 'amazonaws.com') || str_contains(Auth::user()->photo, 'http')) {
                                                $photoUrl = Auth::user()->photo;
                                            } else {
                                                // Generate full S3 URL from path
                                                // Remove leading slash if present
                                                $photoPath = ltrim(Auth::user()->photo, '/');
                                                $bucket = config('filesystems.disks.s3.bucket') ?? config('filesystems.disks.profile_photos.bucket');
                                                $region = config('filesystems.disks.s3.region') ?? config('filesystems.disks.profile_photos.region');
                                                $photoUrl = 'https://' . $bucket . '.s3.' . $region . '.amazonaws.com/' . $photoPath;
                                            }
                                        @endphp
                                        <img src="{{ $photoUrl }}" alt="Profile Photo" class="avatar-image" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="avatar-placeholder" style="display: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="avatar-placeholder">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <span class="user-name">{{ Auth::user()->name }}</span>
                                <div class="dropdown-arrow"></div>
                            </div>
                            <div class="dropdown-menu">
                                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="dropdown-form">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <polyline points="16,17 21,12 16,7"></polyline>
                                            <line x1="21" y1="12" x2="9" y2="12"></line>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
                    @endauth
                    <button class="mobile-menu-toggle" aria-label="Toggle menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div style="padding-top: 80px;">
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="container py-6">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="min-h-screen">
           @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-columns">
                <div class="footer-column">
                    <div class="footer-logo">
                        <span class="logo-icon">BC</span>
                        <span class="logo-text">Structo</span>
                    </div>
                    <p>Connecting construction and architectural professionals with clients for successful building projects.</p>
                </div>
                
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="/">Home</a></li>
                        @auth
                            @if(Auth::user()->role === 'admin')
                                <li><a href="{{ route('admin.users.index') }}">All Users</a></li>
                            @else
                                <li><a href="/users">Users</a></li>
                            @endif
                        @else
                            <li><a href="/users">Users</a></li>
                        @endauth
                        <li><a href="/jobs">Jobs</a></li>
                        <li><a href="{{ route('questions.index') }}">Questions</a></li>
                        <li><a href="/news">News</a></li>
                        <li><a href="/about">About Us</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Services</h3>
                    <ul class="footer-links">
                        <li><a href="/services/architectural">Architectural Design</a></li>
                        <li><a href="/services/construction">Construction</a></li>
                        <li><a href="/services/interior">Interior Design</a></li>
                        <li><a href="/services/landscape">Landscaping</a></li>
                        <li><a href="/services">View All Services</a></li>
                    </ul>
                </div>
                
                <div class="footer-column">
                    <h3>Contact Us</h3>
                    <address>
                        <p>123 Building Street<br>London, UK</p>
                        <p>Email: <a href="mailto:dzemilmanic@hotmail.com">info@structo.com</a></p>
                        <p>Phone: <a href="tel:+381637248930">+381 63 7248 930</a></p>
                    </address>
                    <div class="social-links">
                        <a href="https://www.facebook.com/dzemil.manic" target="blank" class="social-link">FB</a>
                        <a href="https://github.com/dzemilmanic" target="blank" class="social-link">GH</a>
                        <a href="https://www.instagram.com/dzemilmanic/" target="blank" class="social-link">IG</a>
                        <a href="https://orcid.org/my-orcid?orcid=0009-0008-9867-0905" target="blank" class="social-link">OR</a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Structo. All rights reserved.</p>
                <ul class="footer-bottom-links">
                    <li><a href="/privacy">Privacy Policy</a></li>
                    <li><a href="/terms">Terms of Service</a></li>
                    <li><a href="/cookies">Cookie Policy</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/home.js') }}"></script>
    @yield('scripts')
</body>
</html>