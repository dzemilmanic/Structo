/**
 * Testimonials Carousel
 * Handles testimonial sliding, pagination, and navigation
 */
class TestimonialsCarousel {
    constructor() {
        // DOM elements
        this.sliderContainer = document.querySelector('.testimonials-slider');
        this.prevBtn = document.querySelector('.prev-btn');
        this.nextBtn = document.querySelector('.next-btn');
        this.dots = document.querySelectorAll('.dot');
        
        // Only initialize if we have testimonials and slider
        if (!this.sliderContainer) return;
        
        // State
        this.currentPage = 0;
        this.totalPages = this.dots.length;
        this.itemsPerPage = 2; // Always show exactly 2 testimonials per page
        this.isAnimating = false;
        
        // Make the slider layout properly - IMPORTANT FIX
        this.setupSliderLayout();
        
        // Initialize
        this.init();
    }
    
    setupSliderLayout() {
        // Fix: Set proper grid template columns for the slider
        // This makes the testimonials display in a 1x2 grid on each page
        if (window.innerWidth <= 768) {
            // On mobile, stack vertically (one per row)
            this.sliderContainer.style.display = 'grid';
            this.sliderContainer.style.gridTemplateColumns = '1fr';
            this.sliderContainer.style.gridTemplateRows = 'repeat(auto-fill, minmax(200px, auto))';
        } else {
            // On desktop, show two side by side
            this.sliderContainer.style.display = 'grid';
            this.sliderContainer.style.gridTemplateColumns = 'repeat(2, 1fr)';
            this.sliderContainer.style.gridAutoFlow = 'row';
        }
        
        // Add page wrappers around each set of testimonials
        const testimonials = Array.from(this.sliderContainer.querySelectorAll('.testimonial'));
        if (testimonials.length === 0) return;
        
        // Clear the container first
        const originalContent = this.sliderContainer.innerHTML;
        this.sliderContainer.innerHTML = '';
        
        // Group testimonials by pages
        for (let i = 0; i < testimonials.length; i += this.itemsPerPage) {
            const pageWrapper = document.createElement('div');
            pageWrapper.className = 'testimonial-page';
            pageWrapper.style.display = 'grid';
            pageWrapper.style.gridTemplateColumns = window.innerWidth <= 768 ? '1fr' : 'repeat(2, 1fr)';
            pageWrapper.style.gap = '30px';
            pageWrapper.style.width = '100%';
            
            const pageTestimonials = testimonials.slice(i, i + this.itemsPerPage);
            pageTestimonials.forEach(testimonial => {
                pageWrapper.appendChild(testimonial.cloneNode(true));
            });
            
            this.sliderContainer.appendChild(pageWrapper);
        }
        
        // Update layout styles for the slider container
        this.sliderContainer.style.display = 'flex';
        this.sliderContainer.style.width = '100%';
        this.sliderContainer.style.transition = 'transform 0.5s ease';
    }
    
    init() {
        if (this.dots.length === 0) return;
        
        // Set initial position and state
        this.updateButtonStates();
        this.setupEventListeners();
        this.goToPage(0, false);
        
        // Listen for window resize to adjust layout
        window.addEventListener('resize', () => {
            this.setupSliderLayout();
            this.goToPage(this.currentPage, false);
        });
    }
    
    setupEventListeners() {
        // Navigation buttons
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => {
                if (this.currentPage > 0 && !this.isAnimating) {
                    this.goToPage(this.currentPage - 1);
                }
            });
        }
        
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => {
                if (this.currentPage < this.totalPages - 1 && !this.isAnimating) {
                    this.goToPage(this.currentPage + 1);
                }
            });
        }
        
        // Pagination dots
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                if (this.currentPage !== index && !this.isAnimating) {
                    this.goToPage(index);
                }
            });
        });
    }
    
    goToPage(pageIndex, animate = true) {
        if (pageIndex < 0 || pageIndex >= this.totalPages) return;
        
        // Update current page
        this.currentPage = pageIndex;
        
        // Update active dot
        this.updateActiveDot();
        
        // Update button states
        this.updateButtonStates();
        
        // Move slider
        this.slideToCurrentPage(animate);
    }
    
    slideToCurrentPage(animate) {
        if (!this.sliderContainer) return;
        
        const pageWidth = 100; // 100%
        const translateX = -pageWidth * this.currentPage + '%';
        
        if (animate) {
            this.isAnimating = true;
            this.sliderContainer.style.transition = 'transform 0.5s ease';
            this.sliderContainer.style.transform = `translateX(${translateX})`;
            
            // Reset animating flag after transition completes
            setTimeout(() => {
                this.isAnimating = false;
            }, 500);
        } else {
            this.sliderContainer.style.transition = 'none';
            this.sliderContainer.style.transform = `translateX(${translateX})`;
            
            // Force reflow to ensure transition is disabled
            this.sliderContainer.offsetHeight;
            
            // Re-enable transitions for future animations
            setTimeout(() => {
                this.sliderContainer.style.transition = 'transform 0.5s ease';
            }, 50);
        }
    }
    
    updateActiveDot() {
        this.dots.forEach((dot, index) => {
            if (index === this.currentPage) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    }
    
    updateButtonStates() {
        if (this.prevBtn) {
            this.prevBtn.disabled = this.currentPage === 0;
            this.prevBtn.classList.toggle('disabled', this.currentPage === 0);
        }
        
        if (this.nextBtn) {
            this.nextBtn.disabled = this.currentPage === this.totalPages - 1;
            this.nextBtn.classList.toggle('disabled', this.currentPage === this.totalPages - 1);
        }
    }
}

/**
 * Testimonial Form Toggle
 * Handles the testimonial form show/hide functionality
 */
class TestimonialForm {
    constructor() {
        this.toggleBtn = document.getElementById('toggleFormBtn');
        this.formWrapper = document.getElementById('testimonialFormWrapper');
        this.cancelBtn = document.querySelector('.cancel-btn');
        
        this.init();
    }
    
    init() {
        if (!this.toggleBtn || !this.formWrapper) return;
        
        this.toggleBtn.addEventListener('click', () => {
            this.showForm();
        });
        
        if (this.cancelBtn) {
            this.cancelBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.hideForm();
            });
        }
    }
    
    showForm() {
        this.formWrapper.classList.add('active');
        this.toggleBtn.style.display = 'none';
    }
    
    hideForm() {
        this.formWrapper.classList.remove('active');
        this.toggleBtn.style.display = 'block';
    }
}

/**
 * Initialize all testimonial components when DOM is ready
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize testimonials carousel
    new TestimonialsCarousel();
    
    // Initialize testimonial form
    new TestimonialForm();
});
/**
 * Initialize all testimonial components when DOM is ready
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize testimonials carousel
    new TestimonialsCarousel();
    
    // Initialize testimonial form
    new TestimonialForm();
});
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mainNav = document.querySelector('.main-nav');
    
    if (mobileMenuToggle && mainNav) {
        mobileMenuToggle.addEventListener('click', function() {
            mainNav.classList.toggle('active');
            
            // Toggle hamburger to X
            const spans = this.querySelectorAll('span');
            if (mainNav.classList.contains('active')) {
                spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
                spans[1].style.opacity = '0';
                spans[2].style.transform = 'rotate(-45deg) translate(7px, -8px)';
            } else {
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
            }
        });
    }
    
    // Mobile dropdown toggle
    const dropdownItems = document.querySelectorAll('.nav-item-dropdown');
    
    dropdownItems.forEach(item => {
        const link = item.querySelector('.nav-link');
        
        if (window.innerWidth <= 768 && link) {
            link.addEventListener('click', function(e) {
                // Only prevent default if we're on mobile
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    item.classList.toggle('active');
                }
            });
        }
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('.dropdown-menu a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const headerHeight = document.querySelector('.site-header').offsetHeight;
                const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY;
                
                window.scrollTo({
                    top: targetPosition - headerHeight,
                    behavior: 'smooth'
                });
                
                // Close mobile menu if open
                if (mainNav.classList.contains('active')) {
                    mainNav.classList.remove('active');
                    mobileMenuToggle.querySelectorAll('span').forEach(span => {
                        span.style.transform = 'none';
                        span.style.opacity = '1';
                    });
                }
                
                // Close dropdown on mobile
                if (window.innerWidth <= 768) {
                    dropdownItems.forEach(item => item.classList.remove('active'));
                }
            }
        });
    });
    
    // Header scroll effect
    const header = document.querySelector('.site-header');
    
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.style.padding = '5px 0';
                header.style.backgroundColor = 'rgba(255, 255, 255, 0.98)';
                header.style.boxShadow = '0 2px 15px rgba(0, 0, 0, 0.1)';
            } else {
                header.style.padding = '10px 0';
                header.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
            }
        });
    }
    
    // Projects filter functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');
    
    if (filterButtons.length > 0 && projectCards.length > 0) {
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update active filter button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Get filter value
                const filter = this.getAttribute('data-filter');
                
                // Filter projects
                projectCards.forEach(card => {
                    if (filter === 'all' || card.getAttribute('data-category') === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }
});