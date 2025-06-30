/**
 * Delete System for Jobs and Services
 * Simple, reliable delete functionality with SweetAlert confirmations
 */

console.log('Delete system loading...');

// Global delete functions
window.deleteJob = deleteJob;
window.deleteService = deleteService;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing delete system...');
    initializeDeleteSystem();
});

// Also initialize on window load as fallback
window.addEventListener('load', function() {
    console.log('Window loaded - fallback initialization...');
    initializeDeleteSystem();
});

function initializeDeleteSystem() {
    console.log('Setting up delete system...');
    
    // Remove any existing listeners first
    document.removeEventListener('click', handleDeleteClicks);
    
    // Add click listener for delete buttons
    document.addEventListener('click', handleDeleteClicks);
    
    // Initialize session messages
    handleSessionMessages();
    
    console.log('Delete system initialized successfully');
    
    // Debug: Log all delete buttons found
    const allDeleteButtons = document.querySelectorAll('[data-job-id], [data-service-id], .job-delete-btn, .service-delete-btn, .delete-btn');
    console.log('Found delete buttons:', allDeleteButtons.length);
    allDeleteButtons.forEach((btn, index) => {
        console.log(`Button ${index}:`, btn.className, btn.getAttribute('data-job-id'), btn.getAttribute('data-service-id'));
    });
}

function handleDeleteClicks(event) {
    const target = event.target;
    const button = target.closest('button') || target;
    
    console.log('Click detected on:', target);
    console.log('Button found:', button);
    
    // Check for job delete
    if (button && (button.classList.contains('job-delete-btn') || button.getAttribute('data-job-id'))) {
        console.log('Job delete button clicked!');
        event.preventDefault();
        event.stopPropagation();
        
        const jobId = button.getAttribute('data-job-id');
        const jobTitle = button.getAttribute('data-job-title') || 'this job';
        
        console.log('Job delete - ID:', jobId, 'Title:', jobTitle);
        
        if (jobId) {
            deleteJob(jobId, jobTitle);
        } else {
            console.error('Job ID not found!');
        }
        return;
    }
    
    // Check for service delete
    if (button && (button.classList.contains('service-delete-btn') || button.getAttribute('data-service-id'))) {
        console.log('Service delete button clicked!');
        event.preventDefault();
        event.stopPropagation();
        
        const serviceId = button.getAttribute('data-service-id');
        const serviceTitle = button.getAttribute('data-service-title') || 'this service';
        
        console.log('Service delete - ID:', serviceId, 'Title:', serviceTitle);
        
        if (serviceId) {
            deleteService(serviceId, serviceTitle);
        } else {
            console.error('Service ID not found!');
        }
        return;
    }
}

function deleteJob(jobId, jobTitle = 'this job') {
    console.log('deleteJob called with:', jobId, jobTitle);
    
    // Check if SweetAlert is available
    if (typeof Swal === 'undefined') {
        console.log('SweetAlert not available, using confirm');
        if (confirm(`Are you sure you want to delete ${jobTitle}? This action cannot be undone.`)) {
            submitJobDeleteForm(jobId);
        }
        return;
    }
    
    // Use SweetAlert
    Swal.fire({
        title: 'Delete Job?',
        text: `Are you sure you want to delete "${jobTitle}"? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        console.log('SweetAlert result:', result);
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait while we delete the job.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            submitJobDeleteForm(jobId);
        }
    }).catch((error) => {
        console.error('SweetAlert error:', error);
        // Fallback to confirm
        if (confirm(`Are you sure you want to delete ${jobTitle}? This action cannot be undone.`)) {
            submitJobDeleteForm(jobId);
        }
    });
}

function deleteService(serviceId, serviceTitle = 'this service') {
    console.log('deleteService called with:', serviceId, serviceTitle);
    
    // Check if SweetAlert is available
    if (typeof Swal === 'undefined') {
        console.log('SweetAlert not available, using confirm');
        if (confirm(`Are you sure you want to delete ${serviceTitle}? This action cannot be undone.`)) {
            submitServiceDeleteForm(serviceId);
        }
        return;
    }
    
    // Use SweetAlert
    Swal.fire({
        title: 'Delete Service?',
        text: `Are you sure you want to delete "${serviceTitle}"? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        console.log('SweetAlert result:', result);
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait while we delete the service.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            submitServiceDeleteForm(serviceId);
        }
    }).catch((error) => {
        console.error('SweetAlert error:', error);
        // Fallback to confirm
        if (confirm(`Are you sure you want to delete ${serviceTitle}? This action cannot be undone.`)) {
            submitServiceDeleteForm(serviceId);
        }
    });
}

function submitJobDeleteForm(jobId) {
    console.log('Submitting job delete form for ID:', jobId);
    
    const csrfToken = getCSRFToken();
    if (!csrfToken) {
        console.error('CSRF token not found!');
        showError('CSRF token not found. Please refresh the page and try again.');
        return;
    }
    
    console.log('CSRF token found:', csrfToken);
    
    // Create form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/jobs/${jobId}`;
    form.style.display = 'none';
    
    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);
    
    // Add DELETE method
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);
    
    // Append and submit
    document.body.appendChild(form);
    
    console.log('Form created:', form);
    console.log('Form action:', form.action);
    console.log('Form method:', form.method);
    
    try {
        form.submit();
        console.log('Form submitted successfully');
    } catch (error) {
        console.error('Error submitting form:', error);
        showError('An error occurred while deleting. Please try again.');
        document.body.removeChild(form);
    }
}

function submitServiceDeleteForm(serviceId) {
    console.log('Submitting service delete form for ID:', serviceId);
    
    const csrfToken = getCSRFToken();
    if (!csrfToken) {
        console.error('CSRF token not found!');
        showError('CSRF token not found. Please refresh the page and try again.');
        return;
    }
    
    console.log('CSRF token found:', csrfToken);
    
    // Create form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/services/${serviceId}`;
    form.style.display = 'none';
    
    // Add CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);
    
    // Add DELETE method
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);
    
    // Append and submit
    document.body.appendChild(form);
    
    console.log('Form created:', form);
    console.log('Form action:', form.action);
    console.log('Form method:', form.method);
    
    try {
        form.submit();
        console.log('Form submitted successfully');
    } catch (error) {
        console.error('Error submitting form:', error);
        showError('An error occurred while deleting. Please try again.');
        document.body.removeChild(form);
    }
}

function getCSRFToken() {
    // Try meta tag first
    const metaToken = document.querySelector('meta[name="csrf-token"]');
    if (metaToken) {
        return metaToken.getAttribute('content');
    }
    
    // Try existing form token
    const formToken = document.querySelector('input[name="_token"]');
    if (formToken) {
        return formToken.value;
    }
    
    console.error('CSRF token not found in meta tag or form!');
    return null;
}

function showError(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Error',
            text: message,
            icon: 'error',
            confirmButtonColor: '#dc3545'
        });
    } else {
        alert(message);
    }
}

function showSuccess(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Success',
            text: message,
            icon: 'success',
            confirmButtonColor: '#28a745',
            timer: 3000,
            timerProgressBar: true
        });
    } else {
        alert(message);
    }
}

function handleSessionMessages() {
    // Handle success messages
    const successElement = document.querySelector('[data-session-success]');
    if (successElement) {
        const message = successElement.getAttribute('data-session-success');
        if (message) {
            showSuccess(message);
        }
    }
    
    // Handle error messages
    const errorElement = document.querySelector('[data-session-error]');
    if (errorElement) {
        const message = errorElement.getAttribute('data-session-error');
        if (message) {
            showError(message);
        }
    }
}

// Debug function
window.debugDeleteSystem = function() {
    console.log('=== DELETE SYSTEM DEBUG ===');
    console.log('SweetAlert available:', typeof Swal !== 'undefined');
    console.log('CSRF token:', getCSRFToken());
    
    const jobButtons = document.querySelectorAll('.job-delete-btn, [data-job-id]');
    const serviceButtons = document.querySelectorAll('.service-delete-btn, [data-service-id]');
    
    console.log('Job delete buttons found:', jobButtons.length);
    jobButtons.forEach((btn, i) => {
        console.log(`  Job button ${i}:`, btn.className, btn.getAttribute('data-job-id'));
    });
    
    console.log('Service delete buttons found:', serviceButtons.length);
    serviceButtons.forEach((btn, i) => {
        console.log(`  Service button ${i}:`, btn.className, btn.getAttribute('data-service-id'));
    });
    
    console.log('========================');
};

console.log('Delete system loaded successfully');