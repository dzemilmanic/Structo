@extends('layouts.app')

@section('title', 'Home - Structo')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Connect with Top Construction & Architectural Professionals</h1>
            <p>Find verified experts for your building projects or showcase your professional services</p>
            <div class="hero-buttons">
                <a href="users" class="btn btn-primary">Find Professionals</a>
                <a href="#" class="btn btn-outline">List Your Services</a>
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
            <div class="services-grid">
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
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <div class="section-header">
                <h2>How It Works</h2>
                <p>Simple steps to connect with the right professionals</p>
            </div>
            <div class="steps-container">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Search Services</h3>
                    <p>Browse through various categories to find the service you need</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Compare Professionals</h3>
                    <p>Review profiles, portfolios, and client ratings to make informed decisions</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Request Quotes</h3>
                    <p>Contact professionals directly and receive detailed quotes</p>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <h3>Hire & Collaborate</h3>
                    <p>Work with your chosen professional to bring your project to life</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Professionals Section -->
    <section class="professionals" id="professionals">
        <div class="container">
            <div class="section-header">
                <h2>Featured Professionals</h2>
                <p>Top-rated experts in construction and architecture</p>
            </div>
            <div class="professionals-grid">
                <div class="professional-card">
                    <div class="professional-image"></div>
                    <div class="professional-info">
                        <h3>Robert Johnson</h3>
                        <p class="professional-title">Architectural Designer</p>
                        <div class="rating">
                            <span class="stars">★★★★★</span>
                            <span class="count">(48 reviews)</span>
                        </div>
                        <p class="professional-location">London, UK</p>
                        <a href="#" class="btn btn-outline btn-sm">View Profile</a>
                    </div>
                </div>
                <div class="professional-card">
                    <div class="professional-image"></div>
                    <div class="professional-info">
                        <h3>Sarah Williams</h3>
                        <p class="professional-title">Interior Designer</p>
                        <div class="rating">
                            <span class="stars">★★★★★</span>
                            <span class="count">(36 reviews)</span>
                        </div>
                        <p class="professional-location">Manchester, UK</p>
                        <a href="#" class="btn btn-outline btn-sm">View Profile</a>
                    </div>
                </div>
                <div class="professional-card">
                    <div class="professional-image"></div>
                    <div class="professional-info">
                        <h3>David Miller</h3>
                        <p class="professional-title">Construction Manager</p>
                        <div class="rating">
                            <span class="stars">★★★★☆</span>
                            <span class="count">(29 reviews)</span>
                        </div>
                        <p class="professional-location">Birmingham, UK</p>
                        <a href="#" class="btn btn-outline btn-sm">View Profile</a>
                    </div>
                </div>
                <div class="professional-card">
                    <div class="professional-image"></div>
                    <div class="professional-info">
                        <h3>Emma Thompson</h3>
                        <p class="professional-title">Landscape Architect</p>
                        <div class="rating">
                            <span class="stars">★★★★☆</span>
                            <span class="count">(22 reviews)</span>
                        </div>
                        <p class="professional-location">Edinburgh, UK</p>
                        <a href="#" class="btn btn-outline btn-sm">View Profile</a>
                    </div>
                </div>
            </div>
            <div class="view-all">
                <a href="users" class="btn btn-primary">View All Professionals</a>
            </div>
        </div>
    </section>

    <!-- Projects Showcase -->
    <section class="projects" id="projects">
        <div class="container">
            <div class="section-header">
                <h2>Featured Projects</h2>
                <p>Explore completed works from our top professionals</p>
            </div>
            <div class="project-filters">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="architectural">Architectural</button>
                <button class="filter-btn" data-filter="interior">Interior</button>
                <button class="filter-btn" data-filter="construction">Construction</button>
                <button class="filter-btn" data-filter="landscape">Landscape</button>
            </div>
            <div class="projects-grid">
                <div class="project-card" data-category="architectural">
                    <div class="project-image"></div>
                    <div class="project-info">
                        <h3>Modern Office Complex</h3>
                        <p>A contemporary office building with sustainable features</p>
                        <div class="project-meta">
                            <span class="project-location">London</span>
                            <span class="project-type">Commercial</span>
                        </div>
                    </div>
                </div>
                <div class="project-card" data-category="interior">
                    <div class="project-image"></div>
                    <div class="project-info">
                        <h3>Luxury Apartment Redesign</h3>
                        <p>Complete interior transformation of a penthouse apartment</p>
                        <div class="project-meta">
                            <span class="project-location">Manchester</span>
                            <span class="project-type">Residential</span>
                        </div>
                    </div>
                </div>
                <div class="project-card" data-category="construction">
                    <div class="project-image"></div>
                    <div class="project-info">
                        <h3>Community Center</h3>
                        <p>Multi-purpose community facility with modern amenities</p>
                        <div class="project-meta">
                            <span class="project-location">Birmingham</span>
                            <span class="project-type">Public</span>
                        </div>
                    </div>
                </div>
                <div class="project-card" data-category="landscape">
                    <div class="project-image"></div>
                    <div class="project-info">
                        <h3>Urban Park Redesign</h3>
                        <p>Transformation of urban space into a vibrant community park</p>
                        <div class="project-meta">
                            <span class="project-location">Edinburgh</span>
                            <span class="project-type">Public</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="view-all">
                <a href="#" class="btn btn-primary">View All Projects</a>
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
                        <div class="testimonials-slider">
                            {{-- The JavaScript will restructure this into pages with 2 testimonials each --}}
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
                                            <form action="{{ route('testimonials.destroy', $testimonial) }}" method="POST" class="delete-testimonial-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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
                
                @if(count($testimonials) > 2)
                    <div class="testimonials-navigation">
                        <button class="nav-btn prev-btn" aria-label="Previous testimonials"><</button>
                        <div class="pagination-dots">
                            @for($i = 0; $i < ceil(count($testimonials) / 2); $i++)
                                <span class="dot {{ $i == 0 ? 'active' : '' }}" data-index="{{ $i }}"></span>
                            @endfor
                        </div>
                        <button class="nav-btn next-btn" aria-label="Next testimonials">></button>
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
                    <a href="#" class="btn btn-outline-light">List Your Services</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <!-- Add testimonials JS via Vite -->
    @vite('resources/js/home.js')
    <script>
    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 3000
        });
    @endif
    </script>
@endsection