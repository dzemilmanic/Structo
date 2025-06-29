document.addEventListener('DOMContentLoaded', function() {
    // Live search functionality
    const searchInput = document.querySelector('.search-input');
    const searchForm = document.querySelector('.search-form');
    let searchTimeout;
    let isSearching = false;

    if (searchInput && searchForm) {
        // Add live search functionality
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.trim();
            
            // Clear previous timeout
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }
            
            // Debounce search - wait 300ms after user stops typing
            searchTimeout = setTimeout(() => {
                if (query.length >= 2 || query.length === 0) {
                    performLiveSearch(query);
                }
            }, 300);
        });

        // Handle form submission (for Enter key)
        searchForm.addEventListener('submit', function(e) {
            const query = searchInput.value.trim();
            if (query === '') {
                e.preventDefault();
                searchInput.focus();
                return;
            }
            // Allow normal form submission for non-empty queries
        });
    }

    function performLiveSearch(query) {
        if (isSearching) return;
        
        isSearching = true;
        
        // Show loading state
        showSearchLoading();
        
        // Build URL with search parameters
        const currentUrl = new URL(window.location.href);
        if (query) {
            currentUrl.searchParams.set('search', query);
        } else {
            currentUrl.searchParams.delete('search');
        }
        
        // Keep current sort parameter
        const currentSort = document.getElementById('sortSelect')?.value;
        if (currentSort) {
            currentUrl.searchParams.set('sort', currentSort);
        }
        
        // Perform AJAX request
        fetch(currentUrl.toString(), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse the response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Update the questions list
            const newQuestionList = doc.querySelector('.question-list');
            const currentQuestionList = document.querySelector('.question-list');
            
            if (newQuestionList && currentQuestionList) {
                currentQuestionList.innerHTML = newQuestionList.innerHTML;
                
                // Re-attach event listeners to new question cards
                attachQuestionCardListeners();
            }
            
            // Update results info
            const newResultsInfo = doc.querySelector('.results-info');
            const currentResultsInfo = document.querySelector('.results-info');
            
            if (newResultsInfo && currentResultsInfo) {
                currentResultsInfo.innerHTML = newResultsInfo.innerHTML;
            }
            
            // Update URL without page reload
            window.history.pushState({}, '', currentUrl.toString());
            
            hideSearchLoading();
            isSearching = false;
        })
        .catch(error => {
            console.error('Search error:', error);
            hideSearchLoading();
            isSearching = false;
        });
    }

    function showSearchLoading() {
        // Add loading indicator to search button
        const searchBtn = document.querySelector('.search-btn');
        if (searchBtn) {
            searchBtn.style.opacity = '0.6';
            searchBtn.style.pointerEvents = 'none';
            
            // Add spinner
            const originalContent = searchBtn.innerHTML;
            searchBtn.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation: spin 1s linear infinite;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 6v6l4 2"></path>
                </svg>
            `;
            
            // Store original content for restoration
            searchBtn.dataset.originalContent = originalContent;
        }
        
        // Add loading class to question list
        const questionList = document.querySelector('.question-list');
        if (questionList) {
            questionList.style.opacity = '0.7';
            questionList.style.pointerEvents = 'none';
        }
    }

    function hideSearchLoading() {
        // Restore search button
        const searchBtn = document.querySelector('.search-btn');
        if (searchBtn && searchBtn.dataset.originalContent) {
            searchBtn.innerHTML = searchBtn.dataset.originalContent;
            searchBtn.style.opacity = '';
            searchBtn.style.pointerEvents = '';
            delete searchBtn.dataset.originalContent;
        }
        
        // Restore question list
        const questionList = document.querySelector('.question-list');
        if (questionList) {
            questionList.style.opacity = '';
            questionList.style.pointerEvents = '';
        }
    }

    function attachQuestionCardListeners() {
        // Re-attach click handlers to question cards
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
    }

    // Initial attachment of question card listeners
    attachQuestionCardListeners();

    // Handle sort selection changes
    const sortSelect = document.querySelector('.sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('sort', this.value);
            
            // Keep search parameter if it exists
            const currentSearch = searchInput?.value?.trim();
            if (currentSearch) {
                currentUrl.searchParams.set('search', currentSearch);
            }
            
            window.location.href = currentUrl.toString();
        });
    }

    // Add CSS for spinner animation
    if (!document.querySelector('#qa-search-styles')) {
        const style = document.createElement('style');
        style.id = 'qa-search-styles';
        style.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            
            .search-input:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
            }
            
            .question-list {
                transition: opacity 0.2s ease;
            }
            
            .search-btn {
                transition: opacity 0.2s ease;
            }
        `;
        document.head.appendChild(style);
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