
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

// ===== CONSISTENT MODAL STYLING =====

/**
 * Unified modal styling configuration
 */
const modalStyles = {
    // Clean, consistent styling without icons
    clean: {
        icon: false,
        iconHtml: '',
        buttonsStyling: true,
        allowOutsideClick: true,
        allowEscapeKey: true,
        showClass: {
            popup: 'animate__animated animate__fadeInDown animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp animate__faster'
        }
    },
    
    // Confirmation modals
    confirmation: {
        showCancelButton: true,
        reverseButtons: true,
        focusCancel: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    },
    
    // Success modals - VELIKI MODAL U CENTRU, NE TOAST!
    success: {
        confirmButtonColor: '#28a745',
        timer: 3000,
        timerProgressBar: true,
        showCancelButton: false,
        // UKLANJAMO toast postavke da bude veliki modal
        toast: false,
        position: 'center'
    },
    
    // Error modals
    error: {
        confirmButtonColor: '#dc3545',
        showCancelButton: false
    },
    
    // Loading modals
    loading: {
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false
    }
};

/**
 * Create consistent modal configuration
 */
function createModalConfig(type, options = {}) {
    const baseConfig = { ...modalStyles.clean };
    const typeConfig = modalStyles[type] || {};
    
    return {
        ...baseConfig,
        ...typeConfig,
        ...options
    };
}

// ===== CONSISTENT MESSAGE DISPLAY FUNCTIONS =====

/**
 * Show loading modal with consistent styling
 */
function showLoadingModal(title, text) {
    if (typeof Swal === 'undefined') {
        return;
    }
    
    const config = createModalConfig('loading', {
        title: title,
        text: text,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    Swal.fire(config);
}

/**
 * Show success message with consistent styling - VELIKI MODAL U CENTRU
 */
function showSuccess(message) {
    //console.log('📢 Showing success message:', message);
    
    if (typeof Swal === 'undefined') {
        alert(message);
        return;
    }
    
    const config = createModalConfig('success', {
        title: 'Success!',
        text: message
    });
    
    Swal.fire(config);
}

/**
 * Show error message with consistent styling
 */
function showError(message) {
    //console.log('⚠️ Showing error message:', message);
    
    if (typeof Swal === 'undefined') {
        alert(message);
        return;
    }
    
    const config = createModalConfig('error', {
        title: 'Error',
        text: message
    });
    
    Swal.fire(config);
}

/**
 * Show info message with consistent styling
 */
function showInfo(message, title = 'Information') {
    //console.log('ℹ️ Showing info message:', message);
    
    if (typeof Swal === 'undefined') {
        alert(message);
        return;
    }
    
    const config = createModalConfig('clean', {
        title: title,
        text: message,
        confirmButtonColor: '#007bff',
        showCancelButton: false
    });
    
    Swal.fire(config);
}

/**
 * Show warning message with consistent styling
 */
function showWarning(message, title = 'Warning') {
    //console.log('⚠️ Showing warning message:', message);
    
    if (typeof Swal === 'undefined') {
        alert(message);
        return;
    }
    
    const config = createModalConfig('clean', {
        title: title,
        text: message,
        confirmButtonColor: '#ffc107',
        showCancelButton: false
    });
    
    Swal.fire(config);
}

/**
 * FIXED: Testimonial Delete Confirmation - Clean Dialog Without Icons
 * Now shows a clean, professional confirmation dialog
 */
class TestimonialDeleteHandler {
    constructor() {
        this.init();
    }

    init() {
        // Handle delete button clicks
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
            // Fallback to native confirm
            if (confirm('Are you sure you want to delete this testimonial? This action cannot be undone!')) {
                form.submit();
            }
        }
    }

    showSweetAlertConfirmation(form) {
        // Use consistent modal styling
        const config = createModalConfig('confirmation', {
            title: 'Delete Testimonial?',
            text: 'Are you sure you want to delete this testimonial? This action cannot be undone and will permanently remove the testimonial.',
            confirmButtonText: 'Yes, Delete Testimonial',
            cancelButtonText: 'Cancel'
        });
        
        Swal.fire(config).then((result) => {
            if (result.isConfirmed) {
                // Show consistent loading state
                showLoadingModal('Deleting Testimonial...', 'Please wait while we delete the testimonial.');
                form.submit();
            }
        }).catch((error) => {
            console.error('SweetAlert error:', error);
            // Fallback to native confirm if SweetAlert fails
            if (confirm('Are you sure you want to delete this testimonial? This action cannot be undone!')) {
                form.submit();
            }
        });
    }
}

/**
 * Session Message Handler
 * Displays success/error messages using consistent SweetAlert styling
 */
class SessionMessageHandler {
    constructor() {
        this.init();
    }

    init() {
        // Check for session messages and display them
        this.displaySessionMessages();
    }

    displaySessionMessages() {
        // Success messages
        const successMessage = this.getSessionMessage('success');
        if (successMessage) {
            showSuccess(successMessage);
        }

        // Error messages
        const errorMessage = this.getSessionMessage('error');
        if (errorMessage) {
            showError(errorMessage);
        }
    }

    getSessionMessage(type) {
        // This will be populated by the Blade template
        const element = document.querySelector(`[data-session-${type}]`);
        return element ? element.getAttribute(`data-session-${type}`) : null;
    }
}

// Make message functions available globally
window.showSuccess = showSuccess;
window.showError = showError;
window.showInfo = showInfo;
window.showWarning = showWarning;
window.showLoadingModal = showLoadingModal;

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

    // Initialize session message handler
    new SessionMessageHandler();

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

    //console.log('Home JS initialized successfully with consistent modal styling');
});