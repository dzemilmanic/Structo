/**
 * Admin Professional Requests JavaScript
 * Handles approve/reject confirmations with clean dialogs without icons
 */

/**
 * Request Approve Confirmation Handler - Clean Dialog Without Icons
 * Shows a clean, professional confirmation dialog for request approval
 */
class RequestApproveHandler {
    constructor() {
        this.init();
    }

    init() {
        // Handle approve button clicks using event delegation
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('approve-btn') || 
                e.target.closest('.approve-btn')) {
                e.preventDefault();
                
                // Get the button or closest button element
                const button = e.target.classList.contains('approve-btn') 
                    ? e.target 
                    : e.target.closest('.approve-btn');
                
                const requestId = button.getAttribute('data-request-id');
                const userName = button.getAttribute('data-user-name');
                
                this.showApproveConfirmation(requestId, userName);
            }
        });
    }

    showApproveConfirmation(requestId, userName) {
        // Check if SweetAlert is available
        if (typeof Swal !== 'undefined') {
            this.showSweetAlertApproveConfirmation(requestId, userName);
        } else {
            // Fallback to native confirm
            if (confirm(`Are you sure you want to approve ${userName}'s professional request? This will upgrade their account to professional status.`)) {
                this.submitApproveForm(requestId);
            }
        }
    }

    showSweetAlertApproveConfirmation(requestId, userName) {
        // Clean SweetAlert without any icons - just text and buttons
        Swal.fire({
            title: 'Approve Professional Request?',
            text: `Are you sure you want to approve ${userName}'s professional request? This will upgrade their account to professional status.`,
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Approve Request',
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
                    title: 'Approving Request...',
                    text: 'Please wait while we process the professional request approval.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    icon: false, // No icon for loading either
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the approve form
                this.submitApproveForm(requestId);
            }
        }).catch((error) => {
            console.error('SweetAlert error:', error);
            // Fallback to native confirm if SweetAlert fails
            if (confirm(`Are you sure you want to approve ${userName}'s professional request? This will upgrade their account to professional status.`)) {
                this.submitApproveForm(requestId);
            }
        });
    }

    submitApproveForm(requestId) {
        const form = document.getElementById(`approve-form-${requestId}`);
        if (form) {
            form.submit();
        } else {
            console.error('Approve form not found for request ID:', requestId);
            Swal.fire({
                title: 'Error',
                text: 'Form not found. Please refresh the page and try again.',
                icon: false,
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        }
    }
}

/**
 * Request Reject Confirmation Handler - Clean Dialog Without Icons
 * Shows a clean, professional confirmation dialog for request rejection
 */
class RequestRejectHandler {
    constructor() {
        this.init();
    }

    init() {
        // Handle reject button clicks using event delegation
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('reject-btn') || 
                e.target.closest('.reject-btn')) {
                e.preventDefault();
                
                // Get the button or closest button element
                const button = e.target.classList.contains('reject-btn') 
                    ? e.target 
                    : e.target.closest('.reject-btn');
                
                const requestId = button.getAttribute('data-request-id');
                const userName = button.getAttribute('data-user-name');
                
                this.showRejectConfirmation(requestId, userName);
            }
        });
    }

    showRejectConfirmation(requestId, userName) {
        // Check if SweetAlert is available
        if (typeof Swal !== 'undefined') {
            this.showSweetAlertRejectConfirmation(requestId, userName);
        } else {
            // Fallback to native confirm
            if (confirm(`Are you sure you want to reject ${userName}'s professional request? This action cannot be undone and all uploaded files will be permanently deleted.`)) {
                this.submitRejectForm(requestId);
            }
        }
    }

    showSweetAlertRejectConfirmation(requestId, userName) {
        // Clean SweetAlert without any icons - just text and buttons
        Swal.fire({
            title: 'Reject Professional Request?',
            text: `Are you sure you want to reject ${userName}'s professional request? This action cannot be undone and all uploaded files will be permanently deleted.`,
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Reject Request',
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
                    title: 'Rejecting Request...',
                    text: 'Please wait while we process the request rejection.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    icon: false, // No icon for loading either
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the reject form
                this.submitRejectForm(requestId);
            }
        }).catch((error) => {
            console.error('SweetAlert error:', error);
            // Fallback to native confirm if SweetAlert fails
            if (confirm(`Are you sure you want to reject ${userName}'s professional request? This action cannot be undone and all uploaded files will be permanently deleted.`)) {
                this.submitRejectForm(requestId);
            }
        });
    }

    submitRejectForm(requestId) {
        const form = document.getElementById(`reject-form-${requestId}`);
        if (form) {
            form.submit();
        } else {
            console.error('Reject form not found for request ID:', requestId);
            Swal.fire({
                title: 'Error',
                text: 'Form not found. Please refresh the page and try again.',
                icon: false,
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
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
 * Image Modal Handler
 * Handles opening and displaying image previews
 */
class ImageModalHandler {
    constructor() {
        this.init();
    }

    init() {
        // Handle image preview button clicks
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('view-btn') || 
                e.target.closest('.view-btn')) {
                e.preventDefault();
                
                const button = e.target.classList.contains('view-btn') 
                    ? e.target 
                    : e.target.closest('.view-btn');
                
                const imageSrc = button.getAttribute('data-image-src');
                const fileName = button.getAttribute('data-file-name');
                
                this.openImageModal(imageSrc, fileName);
            }
        });
    }

    openImageModal(imageSrc, fileName) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const modalLabel = document.getElementById('imageModalLabel');
        
        if (modal && modalImage) {
            modalImage.src = imageSrc;
            if (modalLabel) {
                modalLabel.textContent = fileName || 'Document Preview';
            }
            
            // Use Bootstrap modal if available, otherwise show with custom styling
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                new bootstrap.Modal(modal).show();
            } else {
                // Fallback modal display
                modal.style.display = 'block';
                modal.classList.add('show');
                document.body.classList.add('modal-open');
                
                // Close modal when clicking outside
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        this.closeImageModal();
                    }
                });
            }
        }
    }

    closeImageModal() {
        const modal = document.getElementById('imageModal');
        if (modal) {
            modal.style.display = 'none';
            modal.classList.remove('show');
            document.body.classList.remove('modal-open');
        }
    }
}

// Global instances
let requestApproveHandler;
let requestRejectHandler;
let sessionMessageHandler;
let imageModalHandler;

// Global functions for backward compatibility
window.confirmApprove = function(requestId, userName) {
    if (requestApproveHandler) {
        requestApproveHandler.showApproveConfirmation(requestId, userName);
    }
};

window.confirmReject = function(requestId, userName) {
    if (requestRejectHandler) {
        requestRejectHandler.showRejectConfirmation(requestId, userName);
    }
};

window.openImageModal = function(imageSrc, fileName) {
    if (imageModalHandler) {
        imageModalHandler.openImageModal(imageSrc, fileName);
    }
};

/**
 * Initialize all components when DOM is ready
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize handlers
    requestApproveHandler = new RequestApproveHandler();
    requestRejectHandler = new RequestRejectHandler();
    sessionMessageHandler = new SessionMessageHandler();
    imageModalHandler = new ImageModalHandler();

    // Add loading states to forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButtons = form.querySelectorAll('button[type="submit"]');
            submitButtons.forEach(btn => {
                btn.disabled = true;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<svg class="animate-spin" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg> Processing...';
                
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

    console.log('Admin Requests JS with clean confirmations initialized successfully');
});