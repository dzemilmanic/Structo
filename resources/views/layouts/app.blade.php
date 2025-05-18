<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Structo') - Construction & Architecture Services</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/style.css', 'resources/js/app.js'])

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
                        <li><a href="#services">Services</a></li>
                        <li><a href="#how-it-works">How It Works</a></li>
                        <li><a href="#professionals">Professionals</a></li>
                        <li><a href="#projects">Projects</a></li>
                        <li><a href="/about">About</a></li>
                        <li><a href="/contact">Contact</a></li>
                        @auth
                            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        @endauth
                    </ul>
                </nav>
                
                <div class="header-actions">
                    @auth
                        <div class="user-dropdown">
                            <span>{{ Auth::user()->name }}</span>
                            <div class="dropdown-menu">
                                <a href="{{ route('profile.edit') }}">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
                    @endauth
                    <button class="mobile-menu-toggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

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
                        <li><a href="#services">Services</a></li>
                        <li><a href="#professionals">Find Professionals</a></li>
                        <li><a href="/register">Join as Professional</a></li>
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
                        <p>Email: <a href="mailto:info@structo.com">info@structo.com</a></p>
                        <p>Phone: <a href="tel:+442012345678">+44 20 1234 5678</a></p>
                    </address>
                    <div class="social-links">
                        <a href="#" class="social-link">FB</a>
                        <a href="#" class="social-link">TW</a>
                        <a href="#" class="social-link">IN</a>
                        <a href="#" class="social-link">IG</a>
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
    @yield('scripts')
</body>
</html>