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

    // MODAL FUNCTIONALITY FOR AUTHENTICATED USERS - COMPLETELY FIXED
    const modal = document.getElementById('askQuestionModal');
    const askQuestionBtn = document.getElementById('askQuestionBtn');
    const askQuestionBtnEmpty = document.getElementById('askQuestionBtnEmpty');
    const modalCancelBtn = document.getElementById('modalCancelBtn');
    const modalClose = document.querySelector('.modal-close');
    const askQuestionForm = document.getElementById('askQuestionForm');

    console.log('Modal elements found:', {
        modal: !!modal,
        askQuestionBtn: !!askQuestionBtn,
        askQuestionBtnEmpty: !!askQuestionBtnEmpty,
        modalCancelBtn: !!modalCancelBtn,
        modalClose: !!modalClose
    });

    // Function to open modal - COMPLETELY FIXED AND WORKING
    function openModal() {
        console.log('Opening modal...');
        
        if (!modal) {
            console.error('Modal element not found!');
            return;
        }

        // Reset modal state
        modal.classList.remove('show');
        modal.style.display = 'none';
        
        // Force reflow
        modal.offsetHeight;
        
        // Show modal with flex display
        modal.style.display = 'flex';
        
        // Add show class immediately for proper display
        modal.classList.add('show');
        
        // Block body scroll
        document.body.style.overflow = 'hidden';
        document.body.classList.add('modal-open');
        
        // Focus first input after a short delay
        setTimeout(() => {
            const titleInput = document.getElementById('modal-title');
            if (titleInput) {
                titleInput.focus();
            }
        }, 100);
        
        console.log('Modal opened successfully!');
    }

    // Function to close modal - FIXED
    function closeModal() {
        console.log('Closing modal...');
        
        if (!modal) return;
        
        // Animate out by removing show class
        modal.classList.remove('show');
        
        // Hide modal after animation
        setTimeout(() => {
            modal.style.display = 'none';
            
            // Restore body scroll
            document.body.style.overflow = '';
            document.body.classList.remove('modal-open');
            
            // Reset form
            if (askQuestionForm) {
                askQuestionForm.reset();
            }
            
            // Clear errors
            clearFormErrors();
        }, 300);
        
        console.log('Modal closed!');
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
            e.stopPropagation();
            console.log('Ask Question button clicked');
            openModal();
        });
    }

    if (askQuestionBtnEmpty) {
        askQuestionBtnEmpty.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Ask Question Empty button clicked');
            openModal();
        });
    }

    // Event listeners for closing modal
    if (modalCancelBtn) {
        modalCancelBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeModal();
        });
    }

    if (modalClose) {
        modalClose.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeModal();
        });
    }

    // Close modal by clicking on background
    if (modal) {
        modal.addEventListener('click', function(e) {
            // Close only if clicked exactly on modal background
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
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = 'Posting...';
                
                // Restore original text after 5 seconds if form wasn't submitted
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                }, 5000);
            }
            
            // Clear previous errors
            clearFormErrors();
        });
    }

    // Question card click handlers
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

    // Delete confirmations
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

    // Debug function - can be called from console
    window.debugModal = function() {
        console.log('=== MODAL DEBUG INFO ===');
        console.log('Modal element:', modal);
        console.log('Modal display:', modal ? window.getComputedStyle(modal).display : 'N/A');
        console.log('Modal z-index:', modal ? window.getComputedStyle(modal).zIndex : 'N/A');
        console.log('Ask Question Btn:', askQuestionBtn);
        console.log('Body classes:', document.body.className);
        
        if (modal) {
            console.log('Modal styles:', {
                display: modal.style.display,
                position: modal.style.position,
                zIndex: modal.style.zIndex,
                backgroundColor: modal.style.backgroundColor
            });
            
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                console.log('Modal content styles:', {
                    display: window.getComputedStyle(modalContent).display,
                    opacity: window.getComputedStyle(modalContent).opacity,
                    transform: window.getComputedStyle(modalContent).transform,
                    visibility: window.getComputedStyle(modalContent).visibility
                });
            }
        }
    };

    // Force open function for testing
    window.forceOpenModal = function() {
        console.log('Force opening modal...');
        if (modal) {
            openModal();
        } else {
            console.error('Modal not found!');
        }
    };
});