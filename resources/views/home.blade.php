@extends('layouts.app')
@vite(['resources/css/home.css', 'resources/js/home.js'])
@section('title', 'Home - Structo')

@section('content')
<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Connect with Top Construction & Architectural Professionals</h1>
        <p>Find verified experts for your building projects or showcase your professional services</p>
        <div class="hero-buttons">
            <a href="users" class="btn btn-primary">Find Professionals</a>
            <a href="jobs" class="btn btn-outline">List Your Services</a>
        </div>
    </div>
    <div class="hero-overlay"></div>
</section>

<!-- Services Section -->
<section class="services" id="services">
    <div class="container">
        <div class="section-header">
            <h2>Our Services</h2>
            <p>Connecting quality professionals with clients who value expertise</p>
        </div>
        
        <div class="services-container">
            <div class="services-scroll-wrapper">
                <div class="services-grid" id="servicesGrid">
                    @forelse($serviceCategories as $category)
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="icon-{{ strtolower(str_replace(' ', '-', $category->name)) }}"></i>
                            </div>
                            <h3>{{ $category->name }}</h3>
                            <p>{{ $category->description ?: 'Professional ' . strtolower($category->name) . ' services for your projects' }}</p>
                            <a href="/users?service_category={{ $category->slug }}" class="service-link">Find {{ $category->name }} Professionals</a>
                        </div>
                    @empty
                        <!-- Fallback static services if no categories exist -->
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="icon-architecture"></i>
                            </div>
                            <h3>Architectural Design</h3>
                            <p>Professional architectural services for residential and commercial projects</p>
                            <a href="#" class="service-link">Find Architects</a>
                        </div>
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="icon-construction"></i>
                            </div>
                            <h3>Construction</h3>
                            <p>Experienced builders and contractors for projects of any scale</p>
                            <a href="#" class="service-link">Find Builders</a>
                        </div>
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="icon-interior"></i>
                            </div>
                            <h3>Interior Design</h3>
                            <p>Transform your spaces with professional interior design expertise</p>
                            <a href="#" class="service-link">Find Designers</a>
                        </div>
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="icon-landscape"></i>
                            </div>
                            <h3>Landscaping</h3>
                            <p>Create beautiful outdoor spaces with landscaping professionals</p>
                            <a href="#" class="service-link">Find Landscapers</a>
                        </div>
                    @endforelse
                </div>
            </div>
            
            @if($serviceCategories->count() > 4)
                <div class="services-navigation">
                    <button class="nav-btn prev-btn" id="servicesPrevBtn" aria-label="Previous services">
                        &#8249;
                    </button>
                    <button class="nav-btn next-btn" id="servicesNextBtn" aria-label="Next services">
                        &#8250;
                    </button>
                </div>
            @endif
        </div>
    </div>
</section>
<!-- How It Works Section -->
<section class="how-it-works" id="how-it-works">
    <div class="container">
        <div class="section-header">
            <h2>How It Works</h2>
            <p>Simple steps to connect clients with professionals</p>
        </div>
        <div class="steps-container">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Post Job or Browse Services</h3>
                <p>Post your project for professionals to bid on, or browse available services from verified professionals</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>Receive or Send Proposals</h3>
                <p>Get proposals from interested professionals, or send requests to service providers with your requirements</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Accept & Start Working</h3>
                <p>Review proposals and profiles, then accept the best offer to begin your collaboration</p>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <h3>Complete & Mark Finished</h3>
                <p>Work together to complete the project, then mark it as finished when all requirements are met</p>
            </div>
        </div>
    </div>
</section>



<!-- Updated Testimonials Section with User Photos -->
<section class="testimonials" id="testimonials">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="section-header">
            <h2>Client Testimonials</h2>
            <p>What our clients say about the professionals on our platform</p>
        </div>
        
        <div class="testimonials-container">
            <div class="testimonials-wrapper">
                @if(count($testimonials) > 0)
                    <div class="testimonials-slider" id="testimonialsSlider">
                        @foreach($testimonials as $testimonial)
                            <div class="testimonial">
                                <div class="testimonial-content">
                                    <p>"{{ $testimonial->content }}"</p>
                                </div>
                                <div class="testimonial-author">
                                    <div class="testimonial-avatar-container">
                                        @if($testimonial->user->photo)
                                            @php
                                                // Check if photo is already a full URL or just a path
                                                if (str_contains($testimonial->user->photo, 'amazonaws.com') || str_contains($testimonial->user->photo, 'http')) {
                                                    $photoUrl = $testimonial->user->photo;
                                                } else {
                                                    // Generate full S3 URL from path
                                                    // Remove leading slash if present
                                                    $photoPath = ltrim($testimonial->user->photo, '/');
                                                    $bucket = config('filesystems.disks.s3.bucket') ?? config('filesystems.disks.profile_photos.bucket');
                                                    $region = config('filesystems.disks.s3.region') ?? config('filesystems.disks.profile_photos.region');
                                                    $photoUrl = 'https://' . $bucket . '.s3.' . $region . '.amazonaws.com/' . $photoPath;
                                                }
                                            @endphp
                                            <img src="{{ $photoUrl }}" alt="Profile Photo" class="testimonial-user-image" onerror="this.src='{{ asset('images/default-avatar.png') }}'; this.onerror=null;">
                                        @else
                                            <div class="testimonial-avatar-placeholder">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="12" cy="7" r="4"></circle>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="author-info">
                                        <h4>{{ $testimonial->user->name }}</h4>
                                        <p>{{ $testimonial->title }}</p>
                                    </div>
                                </div>

                                @auth
                                    @if(auth()->user()->isAdmin())
                                        <form action="{{ route('testimonials.destroy', $testimonial) }}" method="POST" class="testimonial-delete-form" data-testimonial-id="{{ $testimonial->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="testimonial-delete-btn">Delete</button>
                                        </form>
                                    @endif
                                @endauth

                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="no-testimonials">No testimonials yet. Be the first to share your experience!</p>
                @endif
            </div>
            
            @if(count($testimonials) > 1)
                <div class="testimonials-navigation">
                    <button class="nav-btn prev-btn" id="testimonialsPrevBtn" aria-label="Previous testimonials">
                        &#8249;
                    </button>
                    <div class="pagination-dots" id="testimonialsDots">
                        <!-- Dots will be dynamically created by JavaScript -->
                    </div>
                    <button class="nav-btn next-btn" id="testimonialsNextBtn" aria-label="Next testimonials">
                        &#8250;
                    </button>
                </div>
            @endif
        </div>
        
        @auth
            <div class="testimonial-form-container">
                <button id="toggleFormBtn" class="btn btn-primary">Add Your Testimonial</button>
                <div id="testimonialFormWrapper" class="form-wrapper">
                    <form method="POST" action="{{ url('/testimonials') }}" class="testimonial-form">
                        @csrf
                        <div class="form-group">
                            <label for="content">Your Experience</label>
                            <textarea name="content" id="content" placeholder="Share your experience..." required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="title">Your Role or City (Optional)</label>
                            <input type="text" name="title" id="title" placeholder="e.g., Homeowner from London">
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-outline cancel-btn">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</section>

<!-- CTA Section -->
<section class="cta">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Start Your Project?</h2>
            <p>Connect with top professionals in construction and architecture today</p>
            <div class="cta-buttons">
                <a href="users" class="btn btn-light">Find Professionals</a>
                <a href="jobs" class="btn btn-outline-light">List Your Services</a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Success message toast notification
    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    @endif
</script>
@endsection