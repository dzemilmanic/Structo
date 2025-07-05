// Enhanced Notification System - Consistent Modal Styling
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced Search Functionality - Structo Design System
    const searchContainer = document.querySelector('.professionals-search-container');
    const searchInput = document.getElementById('professionalsSearchInput');
    const searchClearBtn = document.querySelector('.search-clear-btn');
    const searchSubmitBtn = document.querySelector('.search-submit-btn');
    const searchLoading = document.querySelector('.search-loading');
    const searchResultsInfo = document.querySelector('.search-results-info');
    const professionalsGrid = document.getElementById('professionalsGrid');
    
    let searchTimeout;
    let isSearching = false;

    // Initialize search functionality
    if (searchInput && professionalsGrid) {
        initializeSearch();
    }

    // Initialize session message handling (JOBS-STYLE)
    handleSessionMessages();

    function initializeSearch() {
        // Add event listeners
        searchInput.addEventListener('input', handleSearchInput);
        searchInput.addEventListener('keydown', handleKeyDown);
        
        if (searchClearBtn) {
            searchClearBtn.addEventListener('click', clearSearch);
        }
        
        if (searchSubmitBtn) {
            searchSubmitBtn.addEventListener('click', performSearch);
        }

        // Initialize clear button visibility
        updateClearButtonVisibility();
        
        // Add search form wrapper if it doesn't exist
        if (!searchContainer.querySelector('.search-input-wrapper')) {
            wrapSearchElements();
        }
    }

    function wrapSearchElements() {
        // Create wrapper for search input and actions
        const wrapper = document.createElement('div');
        wrapper.className = 'search-input-wrapper';
        
        // Create actions container
        const actionsContainer = document.createElement('div');
        actionsContainer.className = 'search-actions';
        
        // Create clear button if it doesn't exist
        if (!searchClearBtn) {
            const clearBtn = document.createElement('button');
            clearBtn.type = 'button';
            clearBtn.className = 'search-clear-btn';
            clearBtn.setAttribute('aria-label', 'Clear search');
            clearBtn.innerHTML = `
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            `;
            clearBtn.addEventListener('click', clearSearch);
            actionsContainer.appendChild(clearBtn);
        }
        
        // Create submit button if it doesn't exist
        if (!searchSubmitBtn) {
            const submitBtn = document.createElement('button');
            submitBtn.type = 'button';
            submitBtn.className = 'search-submit-btn';
            submitBtn.setAttribute('aria-label', 'Search professionals');
            submitBtn.innerHTML = `
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            `;
            submitBtn.addEventListener('click', performSearch);
            actionsContainer.appendChild(submitBtn);
        }
        
        // Create loading spinner if it doesn't exist
        if (!searchLoading) {
            const loading = document.createElement('div');
            loading.className = 'search-loading';
            loading.innerHTML = '<div class="loading-spinner"></div>';
            actionsContainer.appendChild(loading);
        }
        
        // Wrap the input
        searchInput.parentNode.insertBefore(wrapper, searchInput);
        wrapper.appendChild(searchInput);
        wrapper.appendChild(actionsContainer);
    }

    function handleSearchInput(e) {
        const query = e.target.value.trim();
        
        // Update clear button visibility
        updateClearButtonVisibility();
        
        // Clear previous timeout
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }
        
        // Debounce search
        searchTimeout = setTimeout(() => {
            if (query.length >= 2) {
                performSearch(query);
            } else if (query.length === 0) {
                showAllProfessionals();
                hideResultsInfo();
            }
        }, 300);
    }

    function handleKeyDown(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
        } else if (e.key === 'Escape') {
            clearSearch();
        }
    }

    function updateClearButtonVisibility() {
        const clearBtn = document.querySelector('.search-clear-btn');
        if (clearBtn) {
            if (searchInput.value.trim().length > 0) {
                clearBtn.classList.add('visible');
            } else {
                clearBtn.classList.remove('visible');
            }
        }
    }

    function clearSearch() {
        searchInput.value = '';
        searchInput.focus();
        updateClearButtonVisibility();
        showAllProfessionals();
        hideResultsInfo();
        
        // Add clear animation
        searchInput.style.transform = 'scale(0.98)';
        setTimeout(() => {
            searchInput.style.transform = '';
        }, 150);
    }

    function performSearch(query = null) {
        const searchQuery = query || searchInput.value.trim().toLowerCase();
        
        if (!searchQuery) {
            showAllProfessionals();
            hideResultsInfo();
            return;
        }

        // Show loading state
        showLoading();
        
        // Simulate search delay for better UX
        setTimeout(() => {
            const professionals = document.querySelectorAll('.profi-card');
            let visibleCount = 0;
            
            professionals.forEach(card => {
                const searchData = card.getAttribute('data-search') || '';
                const isMatch = searchData.includes(searchQuery);
                
                if (isMatch) {
                    showCard(card);
                    visibleCount++;
                } else {
                    hideCard(card);
                }
            });
            
            // Hide loading and show results
            hideLoading();
            showResultsInfo(visibleCount, searchQuery);
            
            // Scroll to results if on mobile
            if (window.innerWidth <= 768 && visibleCount > 0) {
                professionalsGrid.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }
        }, 200);
    }

    function showAllProfessionals() {
        const professionals = document.querySelectorAll('.profi-card');
        professionals.forEach(card => showCard(card));
    }

    function showCard(card) {
        card.style.display = '';
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        // Trigger animation
        requestAnimationFrame(() => {
            card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        });
    }

    function hideCard(card) {
        card.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
        card.style.opacity = '0';
        card.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            card.style.display = 'none';
        }, 200);
    }

    function showLoading() {
        isSearching = true;
        const loading = document.querySelector('.search-loading');
        const submitBtn = document.querySelector('.search-submit-btn');
        
        if (loading) {
            loading.classList.add('visible');
        }
        
        if (submitBtn) {
            submitBtn.style.opacity = '0.6';
            submitBtn.style.pointerEvents = 'none';
        }
    }

    function hideLoading() {
        isSearching = false;
        const loading = document.querySelector('.search-loading');
        const submitBtn = document.querySelector('.search-submit-btn');
        
        if (loading) {
            loading.classList.remove('visible');
        }
        
        if (submitBtn) {
            submitBtn.style.opacity = '';
            submitBtn.style.pointerEvents = '';
        }
    }

    function showResultsInfo(count, query) {
        let resultsInfo = document.querySelector('.search-results-info');
        
        if (!resultsInfo) {
            resultsInfo = document.createElement('div');
            resultsInfo.className = 'search-results-info';
            professionalsGrid.parentNode.insertBefore(resultsInfo, professionalsGrid);
        }
        
        const resultText = count === 1 ? 'professional' : 'professionals';
        resultsInfo.innerHTML = `
            Found <span class="results-count">${count}</span> ${resultText} 
            ${query ? `matching "<strong>${query}</strong>"` : ''}
            ${count === 0 ? '- try adjusting your search terms' : ''}
        `;
        
        resultsInfo.classList.add('visible');
    }

    function hideResultsInfo() {
        const resultsInfo = document.querySelector('.search-results-info');
        if (resultsInfo) {
            resultsInfo.classList.remove('visible');
        }
    }

    // Add search suggestions functionality
    function initializeSearchSuggestions() {
        const suggestions = [
            { text: 'Interior Design', icon: 'home' },
            { text: 'Architecture', icon: 'building' },
            { text: 'Construction', icon: 'hammer' },
            { text: 'Electrical', icon: 'zap' },
            { text: 'Plumbing', icon: 'droplet' },
            { text: 'Landscaping', icon: 'tree' }
        ];

        let suggestionsContainer = document.querySelector('.search-suggestions');
        
        searchInput.addEventListener('focus', () => {
            if (!searchInput.value.trim() && !suggestionsContainer) {
                createSuggestionsContainer(suggestions);
            }
        });

        document.addEventListener('click', (e) => {
            if (!searchContainer.contains(e.target)) {
                hideSuggestions();
            }
        });
    }

    function createSuggestionsContainer(suggestions) {
        const container = document.createElement('div');
        container.className = 'search-suggestions';
        
        suggestions.forEach(suggestion => {
            const item = document.createElement('div');
            item.className = 'suggestion-item';
            item.innerHTML = `
                <svg class="suggestion-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span class="suggestion-text">${suggestion.text}</span>
            `;
            
            item.addEventListener('click', () => {
                searchInput.value = suggestion.text;
                performSearch(suggestion.text.toLowerCase());
                hideSuggestions();
            });
            
            container.appendChild(item);
        });
        
        searchContainer.appendChild(container);
        
        // Show with animation
        requestAnimationFrame(() => {
            container.classList.add('visible');
        });
    }

    function hideSuggestions() {
        const suggestions = document.querySelector('.search-suggestions');
        if (suggestions) {
            suggestions.classList.remove('visible');
            setTimeout(() => {
                suggestions.remove();
            }, 300);
        }
    }

    // Initialize suggestions
    initializeSearchSuggestions();

    // Add keyboard navigation for accessibility
    function addKeyboardNavigation() {
        let currentSuggestionIndex = -1;
        
        searchInput.addEventListener('keydown', (e) => {
            const suggestions = document.querySelectorAll('.suggestion-item');
            
            if (suggestions.length === 0) return;
            
            switch (e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    currentSuggestionIndex = Math.min(currentSuggestionIndex + 1, suggestions.length - 1);
                    updateSuggestionHighlight(suggestions);
                    break;
                    
                case 'ArrowUp':
                    e.preventDefault();
                    currentSuggestionIndex = Math.max(currentSuggestionIndex - 1, -1);
                    updateSuggestionHighlight(suggestions);
                    break;
                    
                case 'Enter':
                    if (currentSuggestionIndex >= 0) {
                        e.preventDefault();
                        suggestions[currentSuggestionIndex].click();
                    }
                    break;
                    
                case 'Escape':
                    hideSuggestions();
                    currentSuggestionIndex = -1;
                    break;
            }
        });
        
        function updateSuggestionHighlight(suggestions) {
            suggestions.forEach((suggestion, index) => {
                if (index === currentSuggestionIndex) {
                    suggestion.style.background = 'linear-gradient(135deg, rgba(255, 107, 53, 0.1), rgba(255, 140, 95, 0.05))';
                    suggestion.style.color = 'var(--primary-color)';
                } else {
                    suggestion.style.background = '';
                    suggestion.style.color = '';
                }
            });
        }
    }

    addKeyboardNavigation();

    // Prevent search from interfering with profile links
    document.addEventListener('click', function(e) {
        // Check if clicked element is a profile link or inside a profile link
        const profileLink = e.target.closest('a[href*="users.show"]');
        if (profileLink) {
            // Allow normal navigation - don't prevent default
            return;
        }
        
        // Check if clicked element is inside a profi-card but not a link
        const profiCard = e.target.closest('.profi-card');
        if (profiCard && !e.target.closest('a')) {
            // Only handle card clicks that aren't on links
            e.preventDefault();
            e.stopPropagation();
        }
    });

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
        console.log('📢 Showing success message:', message);
        
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
        console.log('⚠️ Showing error message:', message);
        
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
        console.log('ℹ️ Showing info message:', message);
        
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
        console.log('⚠️ Showing warning message:', message);
        
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
        console.log('🔍 Checking for users session messages...');
        
        // Handle success messages
        const successElement = document.querySelector('[data-session-success]');
        if (successElement) {
            const message = successElement.getAttribute('data-session-success');
            if (message) {
                console.log('✅ Found session success message:', message);
                showSuccess(message);
            }
        }
        
        // Handle error messages
        const errorElement = document.querySelector('[data-session-error]');
        if (errorElement) {
            const message = errorElement.getAttribute('data-session-error');
            if (message) {
                console.log('❌ Found session error message:', message);
                showError(message);
            }
        }
        
        // Handle info messages
        const infoElement = document.querySelector('[data-session-info]');
        if (infoElement) {
            const message = infoElement.getAttribute('data-session-info');
            if (message) {
                console.log('ℹ️ Found session info message:', message);
                showInfo(message);
            }
        }
        
        // Handle warning messages
        const warningElement = document.querySelector('[data-session-warning]');
        if (warningElement) {
            const message = warningElement.getAttribute('data-session-warning');
            if (message) {
                console.log('⚠️ Found session warning message:', message);
                showWarning(message);
            }
        }
    }

    // Make functions globally available for request handling
    window.showSuccess = showSuccess;
    window.showError = showError;
    window.showInfo = showInfo;
    window.showWarning = showWarning;
    window.createModalConfig = createModalConfig;
});

// Global variables - REDUCED FILE SIZE LIMITS
let selectedFiles = [];
let maxFiles = 3; // Reduced from 5 to 3
let maxFileSize = 2 * 1024 * 1024; // Reduced from 10MB to 2MB
let allowedTypes = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'image/jpeg',
    'image/jpg',
    'image/png',
    'image/gif',
    'image/webp'
];

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeProfessionalRequest();
    initializeProfessionalsSearch();
});

function initializeProfessionalRequest() {
    const becomeProfessionalBtn = document.getElementById('becomeProfessionalBtn');
    
    if (becomeProfessionalBtn) {
        becomeProfessionalBtn.addEventListener('click', function() {
            showProfessionalRequestModal();
        });
    }
}

function initializeProfessionalsSearch() {
    const searchInput = document.getElementById('professionalsSearchInput');
    const professionalsGrid = document.getElementById('professionalsGrid');
    
    if (searchInput && professionalsGrid) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const cards = professionalsGrid.querySelectorAll('.profi-card');
            
            cards.forEach(card => {
                const searchData = card.getAttribute('data-search') || '';
                if (searchData.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
}

function showProfessionalRequestModal() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Add backdrop blur effect
    document.body.classList.add('modal-backdrop-blur');
    
    Swal.fire({
        title: 'Become a Professional',
        html: createModalHTML(csrfToken),
        width: '700px',
        padding: '0',
        showCancelButton: true,
        confirmButtonText: `
            <svg class="swal-btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            Send Request
        `,
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        backdrop: true,
        allowOutsideClick: true,
        customClass: {
            popup: 'swal2-popup-professional',
            title: 'swal2-title-professional',
            confirmButton: 'swal2-confirm-professional',
            cancelButton: 'swal2-cancel-professional',
            htmlContainer: 'swal2-html-professional'
        },
        didOpen: () => {
            initializeFileUpload();
        },
        willClose: () => {
            // Remove backdrop blur effect when modal closes
            document.body.classList.remove('modal-backdrop-blur');
        },
        preConfirm: () => {
            return validateAndGetFormData();
        }
    }).then((result) => {
        if (result.isConfirmed) {
            submitProfessionalRequest(result.value);
        }
    });
}

function createModalHTML(csrfToken) {
    return `
        <div class="professional-modal-header">
            <div class="modal-header-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h2>Become a Professional</h2>
            <p>Join our verified professionals and unlock exclusive features</p>
        </div>

        <form id="professionalRequestForm" enctype="multipart/form-data" class="professional-modal-form">
            <input type="hidden" name="_token" value="${csrfToken}">
            
            <div class="form-group">
                <label for="swal-specialization" class="form-label">
                    <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6z"/>
                    </svg>
                    Specialization *
                </label>
                <input type="text" name="specialization" id="swal-specialization" class="form-input" 
                       placeholder="e.g., Civil engineer, Architect" required>
                <small class="form-text">Describe your professional expertise and main area of specialization</small>
            </div>

            <div class="form-group">
                <label for="swal-files" class="form-label">
                    <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Professional Documents (Optional)
                </label>
                
                <div class="file-upload-container">
                    <div class="file-drop-zone" id="fileDropZone">
                        <svg class="file-upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="file-upload-text">
                            <strong>Click to upload</strong> or drag and drop files here
                        </p>
                        <p class="file-upload-subtext">
                            PDF, Word documents, or images (Max: ${maxFiles} files, 2MB each)
                        </p>
                        <input type="file" id="swal-files" name="files[]" multiple 
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.webp" 
                               class="file-input-hidden">
                    </div>
                    
                    <div class="selected-files-container" id="selectedFilesContainer" style="display: none;">
                        <h6 class="selected-files-title">Selected Files:</h6>
                        <div class="selected-files-list" id="selectedFilesList"></div>
                    </div>
                </div>
                
                <small class="form-text">
                    Upload certificates, diplomas, portfolio samples, or other proof of your professional qualifications
                </small>
            </div>

            <div class="professional-benefits">
                <h6 class="benefits-title">
                    <svg class="benefits-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    Professional Benefits
                </h6>
                <ul class="benefits-list">
                    <li>
                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Verified professional badge
                    </li>
                    <li>
                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Enhanced profile visibility
                    </li>
                    <li>
                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Access to professional features
                    </li>
                    <li>
                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Priority in search results
                    </li>
                </ul>
            </div>
        </form>
    `;
}

function initializeFileUpload() {
    const fileInput = document.getElementById('swal-files');
    const dropZone = document.getElementById('fileDropZone');
    
    // Reset selected files
    selectedFiles = [];
    
    // File input change event
    fileInput.addEventListener('change', handleFileSelect);
    
    // Drag and drop events
    dropZone.addEventListener('click', () => fileInput.click());
    dropZone.addEventListener('dragover', handleDragOver);
    dropZone.addEventListener('dragleave', handleDragLeave);
    dropZone.addEventListener('drop', handleFileDrop);
}

function handleFileSelect(event) {
    const files = Array.from(event.target.files);
    processSelectedFiles(files);
}

function handleDragOver(event) {
    event.preventDefault();
    event.stopPropagation();
    event.currentTarget.classList.add('drag-over');
}

function handleDragLeave(event) {
    event.preventDefault();
    event.stopPropagation();
    event.currentTarget.classList.remove('drag-over');
}

function handleFileDrop(event) {
    event.preventDefault();
    event.stopPropagation();
    event.currentTarget.classList.remove('drag-over');
    
    const files = Array.from(event.dataTransfer.files);
    processSelectedFiles(files);
}

function processSelectedFiles(files) {
    const errors = [];
    const validFiles = [];
    
    // Check total file count
    if (selectedFiles.length + files.length > maxFiles) {
        errors.push(`Maximum ${maxFiles} files allowed. You can select ${maxFiles - selectedFiles.length} more files.`);
        return;
    }
    
    files.forEach(file => {
        // Check file type
        if (!allowedTypes.includes(file.type)) {
            errors.push(`${file.name}: Invalid file type. Only PDF, Word documents, and images are allowed.`);
            return;
        }
        
        // Check file size
        if (file.size > maxFileSize) {
            errors.push(`${file.name}: File size exceeds 2MB limit.`);
            return;
        }
        
        // Check for duplicates
        if (selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
            errors.push(`${file.name}: File already selected.`);
            return;
        }
        
        validFiles.push(file);
    });
    
    if (errors.length > 0) {
        Swal.showValidationMessage(errors.join('<br>'));
        return;
    }
    
    // Add valid files to selection
    selectedFiles.push(...validFiles);
    updateFilesList();
}

function updateFilesList() {
    const container = document.getElementById('selectedFilesContainer');
    const list = document.getElementById('selectedFilesList');
    
    if (selectedFiles.length === 0) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'block';
    list.innerHTML = '';
    
    selectedFiles.forEach((file, index) => {
        const fileItem = document.createElement('div');
        fileItem.className = 'selected-file-item';
        fileItem.innerHTML = `
            <div class="file-info">
                <svg class="file-icon ${getFileIconClass(file.type)}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${getFileIconPath(file.type)}
                </svg>
                <div class="file-details">
                    <span class="file-name">${file.name}</span>
                    <span class="file-size">${formatFileSize(file.size)}</span>
                </div>
            </div>
            <button type="button" class="remove-file-btn" onclick="removeFile(${index})">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        list.appendChild(fileItem);
    });
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    updateFilesList();
}

function getFileIconClass(mimeType) {
    if (mimeType.includes('pdf')) return 'file-pdf';
    if (mimeType.includes('word') || mimeType.includes('document')) return 'file-word';
    if (mimeType.includes('image')) return 'file-image';
    return 'file-generic';
}

function getFileIconPath(mimeType) {
    if (mimeType.includes('pdf')) {
        return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>';
    }
    if (mimeType.includes('word') || mimeType.includes('document')) {
        return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>';
    }
    if (mimeType.includes('image')) {
        return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>';
    }
    return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function validateAndGetFormData() {
    const specialization = document.getElementById('swal-specialization').value;
    
    if (!specialization.trim()) {
        Swal.showValidationMessage('Please enter your specialization');
        return false;
    }
    
    return {
        specialization: specialization,
        files: selectedFiles
    };
}

function submitProfessionalRequest(data) {
    // Show loading with JOBS-STYLE consistent styling
    const loadingConfig = window.createModalConfig('loading', {
        title: 'Submitting Request...',
        text: 'Please wait while we process your professional request.',
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    Swal.fire(loadingConfig);

    // Create FormData
    const formData = new FormData();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formData.append('_token', csrfToken);
    formData.append('specialization', data.specialization);
    
    // Append files
    data.files.forEach((file, index) => {
        formData.append(`files[${index}]`, file);
    });

    // Get route URL
    const routeUrl = '/profi-requests';

    // Submit form with improved error handling
    fetch(routeUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        // Check if response is ok
        if (!response.ok) {
            // Handle specific HTTP status codes
            if (response.status === 413) {
                throw new Error('File size too large. Please reduce file size and try again.');
            } else if (response.status === 422) {
                throw new Error('Validation error. Please check your files and try again.');
            } else if (response.status >= 500) {
                throw new Error('Server error occurred. Please try again later.');
            } else {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
        }
        
        // Check content type
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Server returned non-JSON response');
        }
        
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success with JOBS-STYLE consistent styling
            window.showSuccess(data.message || 'Your professional request has been submitted successfully and is pending review.');
            
            // Reload page after success modal closes
            setTimeout(() => {
                window.location.reload();
            }, 3000);
        } else {
            throw new Error(data.message || 'An error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        let errorMessage = 'Failed to submit your request. Please try again.';
        
        // Handle specific error types
        if (error.message.includes('File size too large')) {
            errorMessage = 'One or more files are too large. Please use files smaller than 2MB each.';
        } else if (error.message.includes('Validation error')) {
            errorMessage = 'Please check your files and ensure they are valid PDF, Word, or image files.';
        } else if (error.message.includes('Server error')) {
            errorMessage = 'Server error occurred. Please try again later or contact support.';
        } else if (error.message.includes('non-JSON response')) {
            errorMessage = 'Server configuration error. Please contact support.';
        } else if (error.message) {
            errorMessage = error.message;
        }
        
        // Show error with JOBS-STYLE consistent styling
        window.showError(errorMessage);
    });
}

// Make functions globally available
window.removeFile = removeFile;