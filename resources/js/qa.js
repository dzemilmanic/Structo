document.addEventListener('DOMContentLoaded', function() {
    // Handle search form submission
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = document.querySelector('.search-input');
            if (searchInput.value.trim() === '') {
                e.preventDefault();
                searchInput.focus();
            }
        });
    }

    // Handle sort selection changes
    const sortSelect = document.querySelector('.sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('sort', this.value);
            window.location.href = currentUrl.toString();
        });
    }

    // Smooth scroll to answers section when clicking on answer count
    const answerLinks = document.querySelectorAll('.answer-link');
    if (answerLinks.length > 0) {
        answerLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    // Add character count for textareas
    const textareas = document.querySelectorAll('textarea');
    if (textareas.length > 0) {
        textareas.forEach(textarea => {
            const counter = document.createElement('div');
            counter.className = 'form-text text-right';
            counter.textContent = `${textarea.value.length} characters`;
            textarea.parentNode.appendChild(counter);

            textarea.addEventListener('input', function() {
                counter.textContent = `${this.value.length} characters`;
            });
        });
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 500);
            }, 5000);
        });
    }

    // Authentication check function
    function showLoginAlert() {
        Swal.fire({
            title: 'Login Required',
            text: 'You must be logged in to ask a question.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#FF6B35',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Go to Login',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/login';
            }
        });
    }

    // Guest user buttons - show login alert
    const askQuestionBtnGuest = document.getElementById('askQuestionBtnGuest');
    const askQuestionBtnEmptyGuest = document.getElementById('askQuestionBtnEmptyGuest');

    if (askQuestionBtnGuest) {
        askQuestionBtnGuest.addEventListener('click', function(e) {
            e.preventDefault();
            showLoginAlert();
        });
    }

    if (askQuestionBtnEmptyGuest) {
        askQuestionBtnEmptyGuest.addEventListener('click', function(e) {
            e.preventDefault();
            showLoginAlert();
        });
    }

    // MODAL FUNCTIONALITY FOR AUTHENTICATED USERS
    const modal = document.getElementById('askQuestionModal');
    const askQuestionBtn = document.getElementById('askQuestionBtn');
    const askQuestionBtnEmpty = document.getElementById('askQuestionBtnEmpty');
    const modalCancelBtn = document.getElementById('modalCancelBtn');
    const modalClose = document.querySelector('.modal-close');
    const askQuestionForm = document.getElementById('askQuestionForm');

    // Function to open modal - improved
    function openModal() {
        console.log('Opening modal...');
        
        if (modal) {
            // Set high z-index
            modal.style.zIndex = '99999';
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
            modal.style.display = 'flex';
            modal.style.alignItems = 'center';
            modal.style.justifyContent = 'center';
            modal.style.padding = '20px';
            modal.style.boxSizing = 'border-box';
            
            // Add animation classes
            modal.classList.add('show');
            modal.classList.add('modal-visible');
            
            // Block scroll on body
            document.body.style.overflow = 'hidden';
            document.body.classList.add('modal-open');
            
            // Set styles for modal-content
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.style.transform = 'scale(1)';
                modalContent.style.opacity = '1';
                modalContent.classList.add('modal-content-visible');
            }
            
            // Focus first input after short pause
            setTimeout(() => {
                const titleInput = document.getElementById('modal-title');
                if (titleInput) {
                    titleInput.focus();
                }
            }, 300);
            
            console.log('Modal is open!');
        } else {
            console.error('Modal element not found!');
        }
    }

    // Function to close modal - improved
    function closeModal() {
        console.log('Closing modal...');
        
        if (modal) {
            // Remove classes
            modal.classList.remove('show');
            modal.classList.remove('modal-visible');
            
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.classList.remove('modal-content-visible');
            }
            
            // Animate closing
            setTimeout(() => {
                modal.style.display = 'none';
                
                // Restore scroll on body
                document.body.style.overflow = '';
                document.body.classList.remove('modal-open');
            }, 300);
            
            // Reset form
            if (askQuestionForm) {
                askQuestionForm.reset();
            }
            
            // Clear errors
            clearFormErrors();
            
            console.log('Modal is closed!');
        }
    }

    // Function to clear form errors
    function clearFormErrors() {
        const titleError = document.getElementById('title-error');
        const contentError = document.getElementById('content-error');
        const titleInput = document.getElementById('modal-title');
        const contentInput = document.getElementById('modal-content');
        
        if (titleError) titleError.style.display = 'none';
        if (contentError) contentError.style.display = 'none';
        if (titleInput) titleInput.classList.remove('is-invalid');
        if (contentInput) contentInput.classList.remove('is-invalid');
    }

    // Event listeners for opening modal (authenticated users only)
    if (askQuestionBtn) {
        askQuestionBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openModal();
        });
    }

    if (askQuestionBtnEmpty) {
        askQuestionBtnEmpty.addEventListener('click', function(e) {
            e.preventDefault();
            openModal();
        });
    }

    // Event listeners for closing modal
    if (modalCancelBtn) {
        modalCancelBtn.addEventListener('click', function(e) {
            e.preventDefault();
            closeModal();
        });
    }

    if (modalClose) {
        modalClose.addEventListener('click', function(e) {
            e.preventDefault();
            closeModal();
        });
    }

    // Close modal by clicking on background
    if (modal) {
        modal.addEventListener('click', function(e) {
            // Close only if clicked exactly on modal (background), not its child elements
            if (e.target === modal) {
                closeModal();
            }
        });
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && modal.classList.contains('show')) {
            closeModal();
        }
    });

    // Handle form submission with loading state
    if (askQuestionForm) {
        askQuestionForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Posting...';
                
                // Restore original text after 5 seconds if form wasn't submitted
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Ask a question';
                    }
                }, 5000);
            }
            
            // Clear previous errors
            clearFormErrors();
        });
    }

    // Debugging - check if modal exists
    console.log('Modal element:', modal);
    console.log('Ask Question Button:', askQuestionBtn);
    console.log('Ask Question Button Empty:', askQuestionBtnEmpty);
});

// Additional event listeners
document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll(".question-card");

    cards.forEach(card => {
        card.addEventListener("click", function(e) {
            // Don't redirect if clicking on a link inside the card
            if (e.target.tagName === 'A' || e.target.closest('a')) {
                return;
            }
            
            const questionUrl = card.getAttribute("data-url");
            if (questionUrl) {
                window.location.href = questionUrl;
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.delete-testimonial-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            if (!confirm('Are you sure you want to delete this testimonial?')) {
                e.preventDefault();
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Delete question confirmation
    const deleteQuestionForms = document.querySelectorAll('.delete-question-form');
    
    deleteQuestionForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Are you sure?',
                text: "This question and all its answers will be permanently deleted. This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Delete answer confirmation
    const deleteAnswerForms = document.querySelectorAll('.delete-answer-form');
    
    deleteAnswerForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Are you sure?',
                text: "This answer will be permanently deleted. This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});

// Additional security function for modal
window.forceOpenModal = function() {
    const modal = document.getElementById('askQuestionModal');
    if (modal) {
        modal.style.cssText = `
            display: flex !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background-color: rgba(0, 0, 0, 0.8) !important;
            z-index: 99999 !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 20px !important;
        `;
        
        const modalContent = modal.querySelector('.modal-content');
        if (modalContent) {
            modalContent.style.cssText = `
                transform: scale(1) !important;
                opacity: 1 !important;
            `;
        }
        
        document.body.style.overflow = 'hidden';
        console.log('Modal is forcefully opened!');
    }
};

// Test function - you can call it from console
window.testModal = function() {
    console.log('Testing modal...');
    const modal = document.getElementById('askQuestionModal');
    console.log('Modal:', modal);
    
    if (modal) {
        window.forceOpenModal();
        setTimeout(() => {
            console.log('Modal computed style:', window.getComputedStyle(modal));
        }, 1000);
    }
};