/**
 * Services Carousel
 * Handles service categories sliding and navigation
 */
class ServicesCarousel {
    constructor() {
        this.slider = document.getElementById('servicesGrid');
        this.prevBtn = document.getElementById('servicesPrevBtn');
        this.nextBtn = document.getElementById('servicesNextBtn');
        
        if (!this.slider) return;
        
        this.currentIndex = 0;
        this.itemsToShow = this.getItemsToShow();
        this.totalItems = this.slider.children.length;
        this.maxIndex = Math.max(0, this.totalItems - this.itemsToShow);
        
        this.init();
    }
    
    getItemsToShow() {
        const width = window.innerWidth;
        if (width <= 768) return 1;
        if (width <= 1024) return 2;
        if (width <= 1200) return 3;
        return 4;
    }
    
    init() {
        this.updateButtons();
        this.setupEventListeners();
        this.setupTouchEvents();
        
        window.addEventListener('resize', () => {
            this.itemsToShow = this.getItemsToShow();
            this.maxIndex = Math.max(0, this.totalItems - this.itemsToShow);
            this.currentIndex = Math.min(this.currentIndex, this.maxIndex);
            this.updateSlider();
            this.updateButtons();
        });
    }
    
    setupEventListeners() {
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.prev());
        }
        
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.next());
        }
    }
    
    setupTouchEvents() {
        let startX = 0;
        let currentX = 0;
        let isDragging = false;
        
        this.slider.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
        });
        
        this.slider.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            currentX = e.touches[0].clientX;
        });
        
        this.slider.addEventListener('touchend', () => {
            if (!isDragging) return;
            
            const diff = startX - currentX;
            const threshold = 50;
            
            if (Math.abs(diff) > threshold) {
                if (diff > 0) {
                    this.next();
                } else {
                    this.prev();
                }
            }
            
            isDragging = false;
        });
    }
    
    prev() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
            this.updateSlider();
            this.updateButtons();
        }
    }
    
    next() {
        if (this.currentIndex < this.maxIndex) {
            this.currentIndex++;
            this.updateSlider();
            this.updateButtons();
        }
    }
    
    updateSlider() {
        const cardWidth = 250 + 30; // card width + gap
        const translateX = -this.currentIndex * cardWidth;
        this.slider.style.transform = `translateX(${translateX}px)`;
    }
    
    updateButtons() {
        if (this.prevBtn) {
            this.prevBtn.disabled = this.currentIndex === 0;
        }
        
        if (this.nextBtn) {
            this.nextBtn.disabled = this.currentIndex >= this.maxIndex;
        }
    }
}

/**
 * Testimonials Carousel
 * Handles testimonial sliding, pagination, and navigation for mobile
 */
class TestimonialsCarousel {
    constructor() {
        this.sliderContainer = document.getElementById('testimonialsSlider');
        this.prevBtn = document.getElementById('testimonialsPrevBtn');
        this.nextBtn = document.getElementById('testimonialsNextBtn');
        this.dots = document.querySelectorAll('#testimonialsDots .dot');
        
        if (!this.sliderContainer) return;
        
        this.currentIndex = 0;
        this.totalTestimonials = this.sliderContainer.children.length;
        this.isMobile = window.innerWidth <= 768;
        
        this.init();
    }
    
    init() {
        this.setupLayout();
        this.updateButtons();
        this.setupEventListeners();
        this.setupTouchEvents();
        
        window.addEventListener('resize', () => {
            const wasMobile = this.isMobile;
            this.isMobile = window.innerWidth <= 768;
            
            if (wasMobile !== this.isMobile) {
                this.setupLayout();
                this.currentIndex = 0;
                this.updateSlider();
                this.updateButtons();
                this.updateDots();
            }
        });
    }
    
    setupLayout() {
        if (this.isMobile) {
            // Mobile: Show one testimonial at a time
            this.sliderContainer.style.display = 'flex';
            Array.from(this.sliderContainer.children).forEach(testimonial => {
                testimonial.style.flex = '0 0 100%';
                testimonial.style.minWidth = '100%';
            });
        } else {
            // Desktop: Show two testimonials side by side
            this.sliderContainer.style.display = 'grid';
            this.sliderContainer.style.gridTemplateColumns = 'repeat(2, 1fr)';
            this.sliderContainer.style.gap = '30px';
            Array.from(this.sliderContainer.children).forEach(testimonial => {
                testimonial.style.flex = 'none';
                testimonial.style.minWidth = 'auto';
            });
        }
    }
    
    setupEventListeners() {
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.prev());
        }
        
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.next());
        }
        
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => this.goToSlide(index));
        });
    }
    
    setupTouchEvents() {
        if (!this.isMobile) return;
        
        let startX = 0;
        let currentX = 0;
        let isDragging = false;
        
        this.sliderContainer.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
        });
        
        this.sliderContainer.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            currentX = e.touches[0].clientX;
        });
        
        this.sliderContainer.addEventListener('touchend', () => {
            if (!isDragging) return;
            
            const diff = startX - currentX;
            const threshold = 50;
            
            if (Math.abs(diff) > threshold) {
                if (diff > 0) {
                    this.next();
                } else {
                    this.prev();
                }
            }
            
            isDragging = false;
        });
    }
    
    prev() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
            this.updateSlider();
            this.updateButtons();
            this.updateDots();
        }
    }
    
    next() {
        const maxIndex = this.isMobile ? this.totalTestimonials - 1 : Math.ceil(this.totalTestimonials / 2) - 1;
        if (this.currentIndex < maxIndex) {
            this.currentIndex++;
            this.updateSlider();
            this.updateButtons();
            this.updateDots();
        }
    }
    
    goToSlide(index) {
        this.currentIndex = index;
        this.updateSlider();
        this.updateButtons();
        this.updateDots();
    }
    
    updateSlider() {
        if (this.isMobile) {
            const translateX = -this.currentIndex * 100;
            this.sliderContainer.style.transform = `translateX(${translateX}%)`;
        } else {
            // For desktop, we don't need to transform since we're using grid
            this.sliderContainer.style.transform = 'none';
        }
    }
    
    updateButtons() {
        const maxIndex = this.isMobile ? this.totalTestimonials - 1 : Math.ceil(this.totalTestimonials / 2) - 1;
        
        if (this.prevBtn) {
            this.prevBtn.disabled = this.currentIndex === 0;
        }
        
        if (this.nextBtn) {
            this.nextBtn.disabled = this.currentIndex >= maxIndex;
        }
    }
    
    updateDots() {
        this.dots.forEach((dot, index) => {
            if (index === this.currentIndex) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
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
 * Initialize all components when DOM is ready
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize services carousel
    new ServicesCarousel();
    
    // Initialize testimonials carousel
    new TestimonialsCarousel();
    
    // Initialize testimonial form
    new TestimonialForm();
    
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

// Delete Testimonial Confirmation Script
document.addEventListener('DOMContentLoaded', function() {
    // Add event listener to all delete forms
    const deleteForms = document.querySelectorAll('.delete-testimonial-form');
    
    deleteForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent immediate form submission
            
            // Show confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "This testimonial will be permanently deleted. This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'swal2-popup-custom',
                    confirmButton: 'swal2-confirm-custom',
                    cancelButton: 'swal2-cancel-custom'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, submit the form
                    form.submit();
                }
            });
        });
    });
});