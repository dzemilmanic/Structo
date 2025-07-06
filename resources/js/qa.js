class QuestionDeleteHandler {
    constructor() {
        this.init();
    }

    init() {
        // Handle delete button clicks using event delegation
        document.addEventListener('click', (e) => {
            // Check if the clicked element is a delete button or inside a delete form
            const deleteForm = e.target.closest('.delete-question-form');
            const deleteBtn = e.target.closest('.delete-btn');
            
            if (deleteForm && deleteBtn) {
                e.preventDefault();
                e.stopPropagation();
                
                // Get question title from the page
                const questionTitle = document.querySelector('.question-detail-title')?.textContent || 'this question';
                
                this.showDeleteConfirmation(deleteForm, questionTitle);
            }
        });
    }

    showDeleteConfirmation(form, questionTitle) {
        // Check if SweetAlert is available
        if (typeof Swal !== 'undefined') {
            this.showSweetAlertDeleteConfirmation(form, questionTitle);
        } else {
            // Fallback to native confirm
            if (confirm(`Are you sure you want to delete "${questionTitle}"? This question and all its answers will be permanently deleted. This action cannot be undone!`)) {
                form.submit();
            }
        }
    }

    showSweetAlertDeleteConfirmation(form, questionTitle) {
        // Clean SweetAlert without any icons - just text and buttons
        Swal.fire({
            title: 'Delete Question?',
            text: `Are you sure you want to delete "${questionTitle}"? This question and all its answers will be permanently deleted. This action cannot be undone!`,
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete Question',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true,
            allowOutsideClick: true,
            allowEscapeKey: true,
            buttonsStyling: true,
            // CRITICAL: Remove the icon completely
            icon: false,
            iconHtml: '',
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state without icon
                Swal.fire({
                    title: 'Deleting Question...',
                    text: 'Please wait while we delete the question.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    icon: false, // No icon for loading either
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the form
                form.submit();
            }
        }).catch((error) => {
            console.error('SweetAlert error:', error);
            // Fallback to native confirm if SweetAlert fails
            if (confirm(`Are you sure you want to delete "${questionTitle}"? This question and all its answers will be permanently deleted. This action cannot be undone!`)) {
                form.submit();
            }
        });
    }
}

/**
 * Answer Delete Confirmation Handler - Clean Dialog Without Icons
 * Shows a clean, professional confirmation dialog for answer deletion
 */
class AnswerDeleteHandler {
    constructor() {
        this.init();
    }

    init() {
        // Handle delete button clicks using event delegation
        document.addEventListener('click', (e) => {
            // Check if the clicked element is a delete button or inside a delete form
            const deleteForm = e.target.closest('.delete-answer-form');
            const deleteBtn = e.target.closest('.delete-btn');
            
            if (deleteForm && deleteBtn) {
                e.preventDefault();
                e.stopPropagation();
                
                // Get answer author from the closest answer card
                const answerCard = e.target.closest('.answer-card');
                const answerAuthor = answerCard?.querySelector('.answer-author')?.textContent || 'this answer';
                
                this.showDeleteConfirmation(deleteForm, answerAuthor);
            }
        });
    }

    showDeleteConfirmation(form, answerAuthor) {
        // Check if SweetAlert is available
        if (typeof Swal !== 'undefined') {
            this.showSweetAlertDeleteConfirmation(form, answerAuthor);
        } else {
            // Fallback to native confirm
            if (confirm(`Are you sure you want to delete the answer by ${answerAuthor}? This action cannot be undone and will permanently remove the answer.`)) {
                form.submit();
            }
        }
    }

    showSweetAlertDeleteConfirmation(form, answerAuthor) {
        // Clean SweetAlert without any icons - just text and buttons
        Swal.fire({
            title: 'Delete Answer?',
            text: `Are you sure you want to delete the answer by ${answerAuthor}? This action cannot be undone and will permanently remove the answer.`,
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete Answer',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true,
            allowOutsideClick: true,
            allowEscapeKey: true,
            buttonsStyling: true,
            // CRITICAL: Remove the icon completely
            icon: false,
            iconHtml: '',
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state without icon
                Swal.fire({
                    title: 'Deleting Answer...',
                    text: 'Please wait while we delete the answer.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    icon: false, // No icon for loading either
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the form
                form.submit();
            }
        }).catch((error) => {
            console.error('SweetAlert error:', error);
            // Fallback to native confirm if SweetAlert fails
            if (confirm(`Are you sure you want to delete the answer by ${answerAuthor}? This action cannot be undone and will permanently remove the answer.`)) {
                form.submit();
            }
        });
    }
}

// ===== JOBS-STYLE SESSION MESSAGE HANDLING =====

/**
 * Unified modal styling configuration (SAME AS JOBS.JS)
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
    
    // Success modals
    success: {
        confirmButtonColor: '#28a745',
        timer: 3000,
        timerProgressBar: true,
        showCancelButton: false
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
 * Create consistent modal configuration (SAME AS JOBS.JS)
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

/**
 * Show success message with consistent styling (SAME AS JOBS.JS)
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
 * Show error message with consistent styling (SAME AS JOBS.JS)
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
 * Show info message with consistent styling (SAME AS JOBS.JS)
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
 * Show warning message with consistent styling (SAME AS JOBS.JS)
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
 * Session Message Handler (SAME AS JOBS.JS)
 */
function handleSessionMessages() {
    //console.log('🔍 Checking for Q&A session messages...');
    
    // Handle success messages
    const successElement = document.querySelector('[data-session-success]');
    if (successElement) {
        const message = successElement.getAttribute('data-session-success');
        if (message) {
            //console.log('✅ Found session success message:', message);
            showSuccess(message);
        }
    }
    
    // Handle error messages
    const errorElement = document.querySelector('[data-session-error]');
    if (errorElement) {
        const message = errorElement.getAttribute('data-session-error');
        if (message) {
            //console.log('❌ Found session error message:', message);
            showError(message);
        }
    }
    
    // Handle info messages
    const infoElement = document.querySelector('[data-session-info]');
    if (infoElement) {
        const message = infoElement.getAttribute('data-session-info');
        if (message) {
            //console.log('ℹ️ Found session info message:', message);
            showInfo(message);
        }
    }
    
    // Handle warning messages
    const warningElement = document.querySelector('[data-session-warning]');
    if (warningElement) {
        const message = warningElement.getAttribute('data-session-warning');
        if (message) {
            //console.log('⚠️ Found session warning message:', message);
            showWarning(message);
        }
    }
}

// Global instances
let questionDeleteHandler;
let answerDeleteHandler;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize delete handlers
    questionDeleteHandler = new QuestionDeleteHandler();
    answerDeleteHandler = new AnswerDeleteHandler();
    
    // Initialize session message handling (JOBS-STYLE)
    handleSessionMessages();

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

    // // Add character count for textareas
    // const textareas = document.querySelectorAll('textarea');
    // if (textareas.length > 0) {
    //     textareas.forEach(textarea => {
    //         const counter = document.createElement('div');
    //         counter.className = 'form-text text-right';
    //         counter.textContent = `${textarea.value.length} characters`;
    //         textarea.parentNode.appendChild(counter);

    //         textarea.addEventListener('input', function() {
    //             counter.textContent = `${this.value.length} characters`;
    //         });
    //     });
    // }

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
            icon: false, // No icon for consistency
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

    // Function to open modal
    function openModal() {
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
    }

    // Function to close modal
    function closeModal() {
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
            openModal();
        });
    }

    if (askQuestionBtnEmpty) {
        askQuestionBtnEmpty.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
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

    //console.log('Q&A JS with clean delete confirmations and jobs-style notifications initialized successfully');
});