// Profile editing functionality with consistent modal styling (SAME AS USERS.JS)
document.addEventListener('DOMContentLoaded', function() {
    // Initialize session message handling first
    handleSessionMessages();
    
    // Get all edit toggle buttons
    const editButtons = document.querySelectorAll('.edit-toggle');
    const cancelButtons = document.querySelectorAll('.cancel-edit');
    
    // Add event listeners to edit buttons
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const fieldName = this.getAttribute('data-field');
            
            // Hide all other edit forms first
            hideAllEditForms();
            
            // Show the specific edit form for this field
            const editForm = document.getElementById(`edit-form-${fieldName}`);
            const displayElement = document.getElementById(`display-${fieldName}`);
            
            if (editForm) {
                editForm.style.display = 'block';
                editForm.classList.add('show');
                
                // Hide the display element if it exists
                if (displayElement && displayElement.closest('.profile-display')) {
                    displayElement.closest('.profile-display').style.display = 'none';
                }
                
                // For photo section, hide the entire photo section display
                if (fieldName === 'photo') {
                    const photoContainer = document.querySelector('.profile-photo-container');
                    if (photoContainer) {
                        photoContainer.style.display = 'none';
                    }
                }
                
                // Focus on the first input field
                const firstInput = editForm.querySelector('input:not([type="hidden"]), textarea');
                if (firstInput) {
                    setTimeout(() => firstInput.focus(), 100);
                }
            }
        });
    });
    
    // Add event listeners to cancel buttons
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            const fieldName = this.getAttribute('data-field');
            
            // Show confirmation dialog for cancel with consistent styling
            const config = createModalConfig('confirmation', {
                title: 'Cancel Changes?',
                text: 'Are you sure you want to cancel your changes?',
                confirmButtonText: 'Yes, cancel',
                cancelButtonText: 'No, continue'
            });
            
            if (typeof Swal !== 'undefined') {
                Swal.fire(config).then((result) => {
                    if (result.isConfirmed) {
                        hideEditForm(fieldName);
                        showInfo('Changes have been cancelled.', 'Cancelled');
                    }
                });
            } else {
                if (confirm('Are you sure you want to cancel your changes?')) {
                    hideEditForm(fieldName);
                }
            }
        });
    });
    
    // Function to hide all edit forms
    function hideAllEditForms() {
        const allEditForms = document.querySelectorAll('.profile-edit-form');
        allEditForms.forEach(form => {
            form.style.display = 'none';
            form.classList.remove('show');
        });
        
        // Show all display elements
        const allDisplays = document.querySelectorAll('.profile-display');
        allDisplays.forEach(display => {
            display.style.display = 'flex';
        });
        
        // Show photo container
        const photoContainer = document.querySelector('.profile-photo-container');
        if (photoContainer) {
            photoContainer.style.display = 'flex';
        }
    }
    
    // Function to hide specific edit form
    function hideEditForm(fieldName) {
        const editForm = document.getElementById(`edit-form-${fieldName}`);
        const displayElement = document.getElementById(`display-${fieldName}`);
        
        if (editForm) {
            editForm.style.display = 'none';
            editForm.classList.remove('show');
            
            // Show the display element
            if (displayElement && displayElement.closest('.profile-display')) {
                displayElement.closest('.profile-display').style.display = 'flex';
            }
            
            // For photo section, show the photo container
            if (fieldName === 'photo') {
                const photoContainer = document.querySelector('.profile-photo-container');
                if (photoContainer) {
                    photoContainer.style.display = 'flex';
                }
            }
            
            // Reset form to original values
            const inputs = editForm.querySelectorAll('input:not([type="hidden"]), textarea');
            inputs.forEach(input => {
                if (input.hasAttribute('data-original-value')) {
                    input.value = input.getAttribute('data-original-value') || '';
                }
            });
        }
    }
    
    // Handle form submissions with loading states and consistent modal styling
    const forms = document.querySelectorAll('.profile-edit-form form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            // Show loading state
            submitButton.textContent = 'Updating...';
            submitButton.disabled = true;
            
            // Show loading alert with consistent styling
            const loadingConfig = createModalConfig('loading', {
                title: 'Updating Profile...',
                text: 'Please wait while we update your information.',
                didOpen: () => {
                    if (typeof Swal !== 'undefined') {
                        Swal.showLoading();
                    }
                }
            });
            
            if (typeof Swal !== 'undefined') {
                Swal.fire(loadingConfig);
            }
            
            // Create FormData and submit via fetch
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token')
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.text();
                }
                throw new Error('Network response was not ok');
            })
            .then(data => {
                // Close loading alert
                if (typeof Swal !== 'undefined') {
                    Swal.close();
                }
                
                // Show success message with consistent styling
                showSuccess('Profile updated successfully.');
                
                // Reload page after success modal closes
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            })
            .catch(error => {
                // Close loading alert
                if (typeof Swal !== 'undefined') {
                    Swal.close();
                }
                
                // Reset button state
                submitButton.textContent = originalText;
                submitButton.disabled = false;
                
                // Show error message with consistent styling
                showError('An error occurred while updating your profile. Please try again.');
            });
        });
    });
    
    // Handle escape key to cancel editing
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const activeEditForm = document.querySelector('.profile-edit-form.show');
            if (activeEditForm) {
                const fieldName = activeEditForm.id.replace('edit-form-', '');
                
                const config = createModalConfig('confirmation', {
                    title: 'Cancel Changes?',
                    text: 'You pressed Escape. Do you want to cancel your changes?',
                    confirmButtonText: 'Yes, cancel',
                    cancelButtonText: 'No, continue'
                });
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire(config).then((result) => {
                        if (result.isConfirmed) {
                            hideEditForm(fieldName);
                        }
                    });
                } else {
                    if (confirm('You pressed Escape. Do you want to cancel your changes?')) {
                        hideEditForm(fieldName);
                    }
                }
            }
        }
    });
    
    // Handle photo preview with consistent modal styling
    const photoInput = document.getElementById('photo');
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Basic file validation
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                const maxSize = 2 * 1024 * 1024; // 2MB
                
                if (!allowedTypes.includes(file.type)) {
                    showError('Please select a valid image file (JPEG, PNG, JPG, GIF)', 'Invalid File Type');
                    this.value = '';
                    return;
                }
                
                if (file.size > maxSize) {
                    showError('File size must be less than 2MB', 'File Too Large');
                    this.value = '';
                    return;
                }
                
                // Show success message for valid file
                showInfo(`Selected image: ${file.name}`, 'File Selected');
            }
        });
    }
    
    // Handle textarea auto-resize for bio
    const bioTextarea = document.getElementById('bio');
    if (bioTextarea) {
        function resizeTextarea() {
            bioTextarea.style.height = 'auto';
            bioTextarea.style.height = (bioTextarea.scrollHeight) + 'px';
        }
        
        bioTextarea.addEventListener('input', resizeTextarea);
        bioTextarea.addEventListener('focus', resizeTextarea);
        
        // Initial resize
        resizeTextarea();
    }
    
    // Update display values after successful update
    function updateDisplayValue(fieldName, newValue) {
        const displayElement = document.getElementById(`display-${fieldName}`);
        if (displayElement) {
            if (newValue && newValue.trim() !== '') {
                if (fieldName === 'portfolio_url') {
                    // Special handling for portfolio URL to show as link
                    displayElement.innerHTML = `
                        <a href="${newValue}" target="_blank" rel="noopener noreferrer" class="portfolio-link">
                            ${newValue}
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; vertical-align: middle;">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                <polyline points="15,3 21,3 21,9"></polyline>
                                <line x1="10" y1="14" x2="21" y2="3"></line>
                            </svg>
                        </a>
                    `;
                } else {
                    displayElement.textContent = newValue;
                }
                displayElement.classList.remove('empty');
            } else {
                const placeholders = {
                    'specialization': 'Not specified',
                    'bio': 'Tell us about yourself...',
                    'phone': 'Not specified',
                    'location': 'Not specified',
                    'portfolio_url': 'Not specified'
                };
                displayElement.textContent = placeholders[fieldName] || 'Not specified';
                displayElement.classList.add('empty');
            }
        }
    }

    // ===== CONSISTENT MODAL STYLING SYSTEM (SAME AS USERS.JS) =====

    /**
     * Unified modal styling configuration (SAME AS USERS.JS)
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
     * Create consistent modal configuration (SAME AS USERS.JS)
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
     * Show success message with consistent styling (SAME AS USERS.JS)
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
     * Show error message with consistent styling (SAME AS USERS.JS)
     */
    function showError(message, title = 'Error') {
        console.log('⚠️ Showing error message:', message);
        
        if (typeof Swal === 'undefined') {
            alert(message);
            return;
        }
        
        const config = createModalConfig('error', {
            title: title,
            text: message
        });
        
        Swal.fire(config);
    }

    /**
     * Show info message with consistent styling (SAME AS USERS.JS)
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
     * Show warning message with consistent styling (SAME AS USERS.JS)
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
     * Session Message Handler (SAME AS USERS.JS)
     */
    function handleSessionMessages() {
        console.log('🔍 Checking for profile session messages...');
        
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

    // Make functions globally available for external use
    window.showSuccess = showSuccess;
    window.showError = showError;
    window.showInfo = showInfo;
    window.showWarning = showWarning;
    window.createModalConfig = createModalConfig;
});


