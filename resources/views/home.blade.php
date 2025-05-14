@extends('layouts.app')

@section('title', 'Home - BuildConnect')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Connect with Top Construction & Architectural Professionals</h1>
            <p>Find verified experts for your building projects or showcase your professional services</p>
            <div class="hero-buttons">
                <a href="#" class="btn btn-primary">Find Professionals</a>
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
                <a href="#" class="btn btn-primary">View All Professionals</a>
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

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <div class="section-header">
                <h2>Client Testimonials</h2>
                <p>What our clients say about the professionals on our platform</p>
            </div>
            <div class="testimonials-slider">
                <div class="testimonial">
                    <div class="testimonial-content">
                        <p>"Finding the right architect for our home renovation was incredibly easy with this platform. The process was smooth from start to finish."</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-image"></div>
                        <div class="author-info">
                            <h4>Michael Brown</h4>
                            <p>Homeowner, London</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial">
                    <div class="testimonial-content">
                        <p>"As a contractor, this platform has helped me connect with clients who appreciate quality work. It's been a game-changer for my business."</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-image"></div>
                        <div class="author-info">
                            <h4>Jennifer Clark</h4>
                            <p>Building Contractor, Manchester</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Start Your Project?</h2>
                <p>Connect with top professionals in construction and architecture today</p>
                <div class="cta-buttons">
                    <a href="#" class="btn btn-light">Find Professionals</a>
                    <a href="#" class="btn btn-outline-light">List Your Services</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{ asset('js/home.js') }}"></script>
@endsection