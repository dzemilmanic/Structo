/*
Services Carousel
Handles service categories sliding and navigation
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
        if (width <= 768) return this.totalItems; // Show all on mobile (no carousel)
        if (width <= 1024) return 2;
        if (width <= 1200) return 3;
        return 4;
    }

    init() {
        this.updateButtons();
        this.setupEventListeners();
        this.setupTouchEvents();
        this.checkNavigationVisibility();

        window.addEventListener('resize', () => {
            const oldItemsToShow = this.itemsToShow;
            this.itemsToShow = this.getItemsToShow();
            this.maxIndex = Math.max(0, this.totalItems - this.itemsToShow);
            this.currentIndex = Math.min(this.currentIndex, this.maxIndex);
            this.updateSlider();
            this.updateButtons();
            this.checkNavigationVisibility();
        });
    }

    checkNavigationVisibility() {
        const navigation = document.querySelector('.services-navigation');
        if (!navigation) return;

        const width = window.innerWidth;
        
        // Hide navigation on mobile completely
        if (width <= 768) {
            navigation.style.display = 'none';
            return;
        }
        
        // Show navigation only if there are more items than can be displayed
        if (this.totalItems > this.itemsToShow) {
            navigation.style.display = 'flex';
        } else {
            navigation.style.display = 'none';
        }
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
            // Only enable touch on desktop/tablet
            if (window.innerWidth <= 768) return;
            
            startX = e.touches[0].clientX;
            isDragging = true;
        });
        
        this.slider.addEventListener('touchmove', (e) => {
            if (!isDragging || window.innerWidth <= 768) return;
            currentX = e.touches[0].clientX;
        });
        
        this.slider.addEventListener('touchend', () => {
            if (!isDragging || window.innerWidth <= 768) return;
            
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
        const width = window.innerWidth;

        // No transform on mobile - let CSS handle the layout
        if (width <= 768) {
            this.slider.style.transform = 'none';
            return;
        }
        
        // Better calculation for desktop sliding
        const containerWidth = this.slider.parentElement.offsetWidth;
        const gap = 30;
        const availableWidth = containerWidth - (gap * (this.itemsToShow - 1));
        const cardWidth = availableWidth / this.itemsToShow;
        const slideDistance = cardWidth + gap;
        
        const translateX = -this.currentIndex * slideDistance;
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
 * Testimonials Carousel - Enhanced for Mobile
 * Handles testimonial sliding with perfect mobile support
 */
class TestimonialsCarousel {
    constructor() {
        this.sliderContainer = document.getElementById('testimonialsSlider');
        this.prevBtn = document.getElementById('testimonialsPrevBtn');
        this.nextBtn = document.getElementById('testimonialsNextBtn');
        this.dots = [];

        if (!this.sliderContainer) return;
        
        this.currentIndex = 0;
        this.totalTestimonials = this.sliderContainer.children.length;
        this.isMobile = window.innerWidth <= 768;
        
        this.init();
    }

    init() {
        this.updateButtons();
        this.setupEventListeners();
        this.setupTouchEvents();
        this.createDynamicDots();

        window.addEventListener('resize', () => {
            const wasMobile = this.isMobile;
            this.isMobile = window.innerWidth <= 768;
            
            if (wasMobile !== this.isMobile) {
                this.currentIndex = 0;
                this.updateSlider();
                this.updateButtons();
                this.createDynamicDots();
                this.updateDots();
            }
        });
    }

    createDynamicDots() {
        const dotsContainer = document.getElementById('testimonialsDots');
        if (!dotsContainer) return;

        // Clear existing dots
        dotsContainer.innerHTML = '';
        
        // Calculate number of pages
        const totalPages = this.getTotalPages();
        
        // Create new dots
        for (let i = 0; i < totalPages; i++) {
            const dot = document.createElement('span');
            dot.className = `dot ${i === 0 ? 'active' : ''}`;
            dot.setAttribute('data-index', i);
            dot.addEventListener('click', () => this.goToSlide(i));
            dotsContainer.appendChild(dot);
        }
        
        // Update dots reference
        this.dots = dotsContainer.querySelectorAll('.dot');
    }

    getTotalPages() {
        if (this.isMobile) {
            return this.totalTestimonials;
        } else {
            return Math.max(1, this.totalTestimonials - 1); // Desktop: total - 1 because we show 2 at a time
        }
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

        this.sliderContainer.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
        });
        
        this.sliderContainer.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            currentX = e.touches[0].clientX;
            e.preventDefault(); // Prevent scrolling
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
        const maxIndex = this.getMaxIndex();
        if (this.currentIndex < maxIndex) {
            this.currentIndex++;
            this.updateSlider();
            this.updateButtons();
            this.updateDots();
        }
    }

    goToSlide(index) {
        const maxIndex = this.getMaxIndex();
        this.currentIndex = Math.min(index, maxIndex);
        this.updateSlider();
        this.updateButtons();
        this.updateDots();
    }

    getMaxIndex() {
        if (this.isMobile) {
            return this.totalTestimonials - 1;
        } else {
            // Desktop: show 2 at a time, so max index is total - 2
            return Math.max(0, this.totalTestimonials - 2);
        }
    }

    updateSlider() {
        if (this.isMobile) {
            // Mobile sliding - use container width instead of viewport width
            const wrapper = this.sliderContainer.parentElement;
            const containerWidth = wrapper.offsetWidth;
            const translateX = -this.currentIndex * containerWidth;
            this.sliderContainer.style.transform = `translateX(${translateX}px)`;
        } else {
            // Desktop: slide by 50% + gap for each pair
            const translateX = -this.currentIndex * (50 + 1.5); // 50% width + gap
            this.sliderContainer.style.transform = `translateX(${translateX}%)`;
        }
    }

    updateButtons() {
        const maxIndex = this.getMaxIndex();

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
 * Testimonial Delete Confirmation - COMPLETELY FIXED
 * Handles delete confirmation with proper cleanup
 */
class TestimonialDeleteHandler {
    constructor() {
        this.init();
    }

    init() {
        // Handle delete button clicks - prevent form submission and show confirmation
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('testimonial-delete-btn')) {
                e.preventDefault();
                const form = e.target.closest('.testimonial-delete-form');
                this.showDeleteConfirmation(form);
            }
        });
    }

    showDeleteConfirmation(form) {
        // Check if SweetAlert is available
        if (typeof Swal !== 'undefined') {
            this.showSweetAlertConfirmation(form);
        } else {
            this.showCustomConfirmation(form);
        }
    }

    showSweetAlertConfirmation(form) {
        // Close any existing SweetAlert instances first
        if (Swal.isVisible()) {
            Swal.close();
        }

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
            allowOutsideClick: true,
            allowEscapeKey: true,
            buttonsStyling: true,
            backdrop: true,
            heightAuto: false,
            customClass: {
                container: 'structo-swal-container',
                popup: 'structo-swal-popup',
                title: 'structo-swal-title',
                content: 'structo-swal-content',
                confirmButton: 'structo-swal-confirm',
                cancelButton: 'structo-swal-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while we delete the testimonial.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    heightAuto: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the form
                form.submit();
            }
        }).catch((error) => {
            console.error('SweetAlert error:', error);
            // Fallback to custom confirmation if SweetAlert fails
            this.showCustomConfirmation(form);
        });
    }

    showCustomConfirmation(form) {
        // Create custom confirmation dialog as fallback
        const dialog = document.createElement('div');
        dialog.className = 'structo-confirm-dialog';
        dialog.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999999;
            padding: 20px;
            box-sizing: border-box;
        `;

        dialog.innerHTML = `
            <div class="structo-confirm-content" style="
                background: white;
                border-radius: 20px;
                padding: 40px;
                max-width: 480px;
                width: 100%;
                text-align: center;
                box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
            ">
                <div style="
                    width: 80px;
                    height: 80px;
                    border: 4px solid #ff9800;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 25px;
                    font-size: 40px;
                    color: #ff9800;
                ">⚠</div>
                <h3 style="
                    color: #1a202c;
                    font-size: 2rem;
                    font-weight: 800;
                    margin: 0 0 20px 0;
                ">Are you sure?</h3>
                <p style="
                    color: #4a5568;
                    font-size: 1.1rem;
                    line-height: 1.6;
                    margin: 0 0 35px 0;
                ">This testimonial will be permanently deleted. This action cannot be undone!</p>
                <div style="
                    display: flex;
                    gap: 20px;
                    justify-content: center;
                ">
                    <button class="structo-confirm-btn structo-cancel" style="
                        background: linear-gradient(135deg, #6c757d, #5a6268);
                        color: white;
                        border: none;
                        border-radius: 12px;
                        padding: 18px 35px;
                        font-size: 1.1rem;
                        font-weight: 800;
                        cursor: pointer;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                        transition: all 0.3s ease;
                    ">Cancel</button>
                    <button class="structo-confirm-btn structo-confirm" style="
                        background: linear-gradient(135deg, #dc3545, #c82333);
                        color: white;
                        border: none;
                        border-radius: 12px;
                        padding: 18px 35px;
                        font-size: 1.1rem;
                        font-weight: 800;
                        cursor: pointer;
                        text-transform: uppercase;
                        letter-spacing: 1px;
                        transition: all 0.3s ease;
                    ">Yes, delete it!</button>
                </div>
            </div>
        `;
        
        document.body.appendChild(dialog);
        
        // Add event listeners
        const confirmBtn = dialog.querySelector('.structo-confirm');
        const cancelBtn = dialog.querySelector('.structo-cancel');
        
        const cleanup = () => {
            if (document.body.contains(dialog)) {
                document.body.removeChild(dialog);
            }
        };
        
        confirmBtn.addEventListener('click', () => {
            cleanup();
            form.submit();
        });
        
        cancelBtn.addEventListener('click', cleanup);
        
        // Close on outside click
        dialog.addEventListener('click', (e) => {
            if (e.target === dialog) {
                cleanup();
            }
        });
        
        // Close on escape key
        const handleEscape = (e) => {
            if (e.key === 'Escape') {
                cleanup();
                document.removeEventListener('keydown', handleEscape);
            }
        };
        
        document.addEventListener('keydown', handleEscape);
        
        // Focus cancel button by default
        setTimeout(() => cancelBtn.focus(), 100);
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

    // Initialize testimonial delete handler
    new TestimonialDeleteHandler();

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