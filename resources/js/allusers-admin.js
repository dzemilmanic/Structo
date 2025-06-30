/**
 * Admin Users Management JavaScript
 * Handles delete confirmations and demote confirmations for user management
 */

/**
 * FIXED: User Delete Confirmation Handler - Clean Dialog Without Icons
 * Shows a clean, professional confirmation dialog for user deletion
 */
class UserDeleteHandler {
    constructor() {
        this.init();
    }

    init() {
        // Handle delete button clicks using event delegation
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('user-delete-btn') || 
                e.target.closest('.user-delete-btn')) {
                e.preventDefault();
                
                // Get the button or closest button element
                const button = e.target.classList.contains('user-delete-btn') 
                    ? e.target 
                    : e.target.closest('.user-delete-btn');
                
                const userId = button.getAttribute('data-user-id');
                const userName = button.getAttribute('data-user-name');
                
                this.showDeleteConfirmation(userId, userName);
            }
        });

        // Handle demote button clicks using event delegation
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('user-demote-btn') || 
                e.target.closest('.user-demote-btn')) {
                e.preventDefault();
                
                // Get the button or closest button element
                const button = e.target.classList.contains('user-demote-btn') 
                    ? e.target 
                    : e.target.closest('.user-demote-btn');
                
                const userId = button.getAttribute('data-user-id');
                const userName = button.getAttribute('data-user-name');
                
                this.showDemoteConfirmation(userId, userName);
            }
        });
    }

    showDeleteConfirmation(userId, userName) {
        // Check if SweetAlert is available
        if (typeof Swal !== 'undefined') {
            this.showSweetAlertDeleteConfirmation(userId, userName);
        } else {
            // Fallback to native confirm
            if (confirm(`Are you sure you want to delete "${userName}"? This action cannot be undone and will permanently remove all their data.`)) {
                this.submitDeleteForm(userId);
            }
        }
    }

    showDemoteConfirmation(userId, userName) {
        // Check if SweetAlert is available
        if (typeof Swal !== 'undefined') {
            this.showSweetAlertDemoteConfirmation(userId, userName);
        } else {
            // Fallback to native confirm
            if (confirm(`Are you sure you want to demote "${userName}" from professional to regular user? This will remove their professional status and specialization.`)) {
                this.submitDemoteForm(userId);
            }
        }
    }

    showSweetAlertDeleteConfirmation(userId, userName) {
        // Clean SweetAlert without any icons - just text and buttons
        Swal.fire({
            title: 'Delete User?',
            text: `Are you sure you want to delete "${userName}"? This action cannot be undone and will permanently remove all their data.`,
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete User',
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
                    title: 'Deleting User...',
                    text: 'Please wait while we delete the user.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    icon: false, // No icon for loading either
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the delete form
                this.submitDeleteForm(userId);
            }
        }).catch((error) => {
            console.error('SweetAlert error:', error);
            // Fallback to native confirm if SweetAlert fails
            if (confirm(`Are you sure you want to delete "${userName}"? This action cannot be undone and will permanently remove all their data.`)) {
                this.submitDeleteForm(userId);
            }
        });
    }

    showSweetAlertDemoteConfirmation(userId, userName) {
        // Clean SweetAlert for demote action
        Swal.fire({
            title: 'Demote Professional?',
            text: `Are you sure you want to demote "${userName}" from professional to regular user? This will remove their professional status and specialization.`,
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Demote',
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
                    title: 'Demoting User...',
                    text: 'Please wait while we update the user role.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    icon: false, // No icon for loading either
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the demote form
                this.submitDemoteForm(userId);
            }
        }).catch((error) => {
            console.error('SweetAlert error:', error);
            // Fallback to native confirm if SweetAlert fails
            if (confirm(`Are you sure you want to demote "${userName}" from professional to regular user? This will remove their professional status and specialization.`)) {
                this.submitDemoteForm(userId);
            }
        });
    }

    submitDeleteForm(userId) {
        const form = document.getElementById(`delete-form-${userId}`);
        if (form) {
            form.submit();
        } else {
            console.error(`Delete form not found for user ID: ${userId}`);
            Swal.fire({
                title: 'Error',
                text: 'Delete form not found. Please refresh the page and try again.',
                icon: false,
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        }
    }

    submitDemoteForm(userId) {
        const form = document.getElementById(`demote-form-${userId}`);
        if (form) {
            form.submit();
        } else {
            console.error(`Demote form not found for user ID: ${userId}`);
            Swal.fire({
                title: 'Error',
                text: 'Demote form not found. Please refresh the page and try again.',
                icon: false,
                confirmButtonText: 'OK',
                confirmButtonColor: '#ffc107'
            });
        }
    }
}

/**
 * Session Message Handler
 * Displays success/error messages using SweetAlert toasts
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
            this.showSuccessToast(successMessage);
        }

        // Error messages
        const errorMessage = this.getSessionMessage('error');
        if (errorMessage) {
            this.showErrorToast(errorMessage);
        }
    }

    getSessionMessage(type) {
        // This will be populated by the Blade template
        const element = document.querySelector(`[data-session-${type}]`);
        return element ? element.getAttribute(`data-session-${type}`) : null;
    }

    showSuccessToast(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: message,
                timer: 5000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                showCloseButton: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        }
    }

    showErrorToast(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message,
                timer: 8000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                showCloseButton: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        }
    }
}

/**
 * Initialize all components when DOM is ready
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize user delete handler
    new UserDeleteHandler();

    // Initialize session message handler
    new SessionMessageHandler();

    // Auto-focus search input if present
    const searchInput = document.querySelector('.search-input');
    if (searchInput && window.innerWidth > 768) {
        // Only auto-focus on desktop to avoid mobile keyboard popup
        searchInput.focus();
    }

    // Add loading states to forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButtons = form.querySelectorAll('button[type="submit"]');
            submitButtons.forEach(btn => {
                btn.disabled = true;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                
                // Re-enable after 10 seconds as fallback
                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }, 10000);
            });
        });
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});