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
    //('ℹ️ Showing info message:', message);
    
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
    //console.log('🔍 Checking for admin requests session messages...');
    
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

/**
 * Image Modal Handler - SIMPLE VERSION WITH FIXED BACKDROP CLICK
 * Handles opening and displaying image previews with proper close functionality
 */
class ImageModalHandler {
    constructor() {
        this.modal = null;
        this.init();
    }

    init() {
        // Handle image preview button clicks
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('view-btn') || 
                e.target.closest('.view-btn')) {
                e.preventDefault();
                e.stopPropagation();
                
                const button = e.target.classList.contains('view-btn') 
                    ? e.target 
                    : e.target.closest('.view-btn');
                
                const imageSrc = button.getAttribute('data-image-src');
                const fileName = button.getAttribute('data-file-name');
                
                this.openImageModal(imageSrc, fileName);
            }
        });

        // Handle download button clicks for images - FORCE DOWNLOAD
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('download-btn') || 
                e.target.closest('.download-btn')) {
                
                const link = e.target.classList.contains('download-btn') 
                    ? e.target 
                    : e.target.closest('.download-btn');
                
                // Check if this is an image file
                const href = link.getAttribute('href');
                if (href && this.isImageFile(href)) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.forceDownloadImage(href);
                }
                // For non-image files, let the default behavior work (opens in new tab)
            }
        });
    }

    isImageFile(url) {
        const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.bmp', '.svg'];
        const urlLower = url.toLowerCase();
        return imageExtensions.some(ext => urlLower.includes(ext));
    }

    forceDownloadImage(imageUrl) {
        // Create a temporary link element to force download
        const link = document.createElement('a');
        link.href = imageUrl;
        
        // Extract filename from URL or use default
        const urlParts = imageUrl.split('/');
        const filename = urlParts[urlParts.length - 1] || 'image.jpg';
        
        link.download = filename;
        link.target = '_blank';
        
        // Append to body, click, and remove
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    openImageModal(imageSrc, fileName) {
        this.modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const modalLabel = document.getElementById('imageModalLabel');
        
        if (this.modal && modalImage) {
            modalImage.src = imageSrc;
            if (modalLabel) {
                modalLabel.textContent = fileName || 'Document Preview';
            }
            
            // Use Bootstrap modal if available
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const bsModal = new bootstrap.Modal(this.modal);
                bsModal.show();
                
                // Handle close events
                this.modal.addEventListener('hidden.bs.modal', () => {
                    this.cleanupModal();
                });
            } else {
                // Fallback modal display
                this.showFallbackModal();
            }
        }
    }

    showFallbackModal() {
        this.modal.style.display = 'block';
        this.modal.classList.add('show');
        document.body.classList.add('modal-open');
        
        // Add backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.id = 'imageModalBackdrop';
        document.body.appendChild(backdrop);
        
        // Handle close button clicks
        const closeButtons = this.modal.querySelectorAll('[data-bs-dismiss="modal"], .btn-close');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.closeImageModal();
            });
        });
        
        // FIXED: Handle backdrop clicks properly
        this.modal.addEventListener('click', (e) => {
            // Only close if clicking directly on the modal backdrop (not on content)
            if (e.target === this.modal) {
                e.preventDefault();
                e.stopPropagation();
                this.closeImageModal();
            }
        });
        
        // Handle modal content clicks (prevent closing when clicking inside)
        const modalContent = this.modal.querySelector('.modal-content');
        if (modalContent) {
            modalContent.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        }
        
        // Handle escape key
        const escapeHandler = (e) => {
            if (e.key === 'Escape') {
                this.closeImageModal();
                document.removeEventListener('keydown', escapeHandler);
            }
        };
        document.addEventListener('keydown', escapeHandler);
    }

    closeImageModal() {
        if (this.modal) {
            this.modal.style.display = 'none';
            this.modal.classList.remove('show');
            document.body.classList.remove('modal-open');
            
            // Remove backdrop
            const backdrop = document.getElementById('imageModalBackdrop');
            if (backdrop) {
                backdrop.remove();
            }
            
            this.cleanupModal();
        }
    }

    cleanupModal() {
        if (this.modal) {
            const modalImage = document.getElementById('modalImage');
            if (modalImage) {
                modalImage.src = '';
            }
        }
    }
}

// Global instances
let requestApproveHandler;
let requestRejectHandler;
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
    imageModalHandler = new ImageModalHandler();
    
    // Initialize session message handling (JOBS-STYLE)
    handleSessionMessages();

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

    //console.log('Admin Requests JS with clean confirmations and jobs-style notifications initialized successfully');
});