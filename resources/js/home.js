document.addEventListener('DOMContentLoaded', function() {
    // Projects filtering
    const filterButtons = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');
    
    // Set up filter buttons
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Get filter value
            const filterValue = this.getAttribute('data-filter');
            
            // Filter projects
            projectCards.forEach(card => {
                if (filterValue === 'all' || card.getAttribute('data-category') === filterValue) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'scale(1)';
                    }, 50);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80, // Adjust for header height
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Placeholder for image loading
    function loadImages() {
        // Professional profile images (replace with actual image loading logic)
        const professionalImages = document.querySelectorAll('.professional-image');
        professionalImages.forEach((img, index) => {
            // Use placeholder images or load from API
            img.style.backgroundImage = `url('https://randomuser.me/api/portraits/${index % 2 ? 'women' : 'men'}/${index + 1}.jpg')`;
        });
        
        // Project images
        const projectImages = document.querySelectorAll('.project-image');
        const projectCategories = ['architectural', 'interior', 'construction', 'landscape'];
        projectImages.forEach((img, index) => {
            // Use placeholder images or load from database
            const category = projectCategories[index % projectCategories.length];
            img.style.backgroundImage = `url('/images/projects/${category}-${index + 1}.jpg')`;
        });
        
        // Author images
        const authorImages = document.querySelectorAll('.author-image');
        authorImages.forEach((img, index) => {
            img.style.backgroundImage = `url('https://randomuser.me/api/portraits/${index % 2 ? 'women' : 'men'}/${index + 10}.jpg')`;
        });
    }
    
    // Initialize image loading
    loadImages();
    
    // Scroll animation for elements
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.service-card, .step, .professional-card, .project-card, .testimonial');
        
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementPosition < windowHeight * 0.85) {
                element.classList.add('animate-in');
            }
        });
    };
    
    // Add scroll animation class to elements
    document.querySelectorAll('.service-card, .step, .professional-card, .project-card, .testimonial').forEach(element => {
        element.classList.add('scroll-animation');
    });
    
    // Listen for scroll to trigger animations
    window.addEventListener('scroll', animateOnScroll);
    
    // Trigger on initial load
    animateOnScroll();
});