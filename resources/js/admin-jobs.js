const modalStyles = {
    
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

function createModalConfig(type, options = {}) {
    const baseConfig = { ...modalStyles.clean };
    const typeConfig = modalStyles[type] || {};
    
    return {
        ...baseConfig,
        ...typeConfig,
        ...options
    };
}



function showLoadingModal(title, text) {
    if (typeof Swal === 'undefined') {
        return;
    }
    
    const config = createModalConfig('loading', {
        title: title,
        text: text,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    Swal.fire(config);
}


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


class CategoryDeleteHandler {
    constructor() {
        this.init();
    }

    init() {
        // Handle delete button clicks using event delegation
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('category-delete-btn') || 
                e.target.closest('.category-delete-btn')) {
                e.preventDefault();
                
                // Get the button or closest button element
                const button = e.target.classList.contains('category-delete-btn') 
                    ? e.target 
                    : e.target.closest('.category-delete-btn');
                
                const categoryId = button.getAttribute('data-category-id');
                const categoryName = button.getAttribute('data-category-name');
                
                this.showDeleteConfirmation(categoryId, categoryName);
            }
        });
    }

    showDeleteConfirmation(categoryId, categoryName) {
        // Check if SweetAlert is available
        if (typeof Swal !== 'undefined') {
            this.showSweetAlertDeleteConfirmation(categoryId, categoryName);
        } else {
            // Fallback to native confirm
            if (confirm(`Are you sure you want to delete "${categoryName}"? This action cannot be undone and will permanently remove the category.`)) {
                this.submitDeleteForm(categoryId);
            }
        }
    }

    showSweetAlertDeleteConfirmation(categoryId, categoryName) {
        
        const config = createModalConfig('confirmation', {
            title: 'Delete Category?',
            text: `Are you sure you want to delete "${categoryName}"? This action cannot be undone and will permanently remove the category.`,
            confirmButtonText: 'Yes, Delete Category',
            cancelButtonText: 'Cancel'
        });
        
        Swal.fire(config).then((result) => {
            if (result.isConfirmed) {
                
                showLoadingModal('Deleting Category...', 'Please wait while we delete the category.');
                this.submitDeleteForm(categoryId);
            }
        }).catch((error) => {
            console.error('SweetAlert error:', error);
            
            if (confirm(`Are you sure you want to delete "${categoryName}"? This action cannot be undone and will permanently remove the category.`)) {
                this.submitDeleteForm(categoryId);
            }
        });
    }

    submitDeleteForm(categoryId) {
        const csrfToken = this.getFreshCSRFToken();
        if (!csrfToken) {
            showError('CSRF token not found. Please refresh the page and try again.');
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/categories/${categoryId}`;
        form.style.display = 'none';

        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);

        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);

        try {
            form.submit();
        } catch (error) {
            console.error('Error submitting form:', error);
            showError('An error occurred while deleting. Please try again.');
        }
    }

    getFreshCSRFToken() {
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        if (metaToken) {
            return metaToken.getAttribute('content');
        }

        const existingForm = document.querySelector('form input[name="_token"]');
        if (existingForm) {
            return existingForm.value;
        }

        console.error('CSRF token not found!');
        return null;
    }
}


class JobDeleteHandler {
    constructor() {
        this.init();
    }

    init() {
        // Handle delete button clicks using event delegation
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('job-delete-btn') || 
                e.target.closest('.job-delete-btn')) {
                e.preventDefault();
                
                // Get the button or closest button element
                const button = e.target.classList.contains('job-delete-btn') 
                    ? e.target 
                    : e.target.closest('.job-delete-btn');
                
                const jobId = button.getAttribute('data-job-id');
                const jobTitle = button.getAttribute('data-job-title');
                
                this.showDeleteConfirmation(jobId, jobTitle);
            }
        });
    }

    showDeleteConfirmation(jobId, jobTitle) {
        if (typeof Swal !== 'undefined') {
            this.showSweetAlertDeleteConfirmation(jobId, jobTitle);
        } else {
            
            if (confirm(`Are you sure you want to delete "${jobTitle}"? This action cannot be undone and will permanently remove the job and all its requests.`)) {
                this.submitDeleteForm(jobId);
            }
        }
    }

    showSweetAlertDeleteConfirmation(jobId, jobTitle) {
        
        const config = createModalConfig('confirmation', {
            title: 'Delete Job?',
            text: `Are you sure you want to delete "${jobTitle}"? This action cannot be undone and will permanently remove the job and all its requests.`,
            confirmButtonText: 'Yes, Delete Job',
            cancelButtonText: 'Cancel'
        });
        
        Swal.fire(config).then((result) => {
            if (result.isConfirmed) {
                
                showLoadingModal('Deleting Job...', 'Please wait while we delete the job.');
                this.submitDeleteForm(jobId);
            }
        }).catch((error) => {
            console.error('SweetAlert error:', error);
            
            if (confirm(`Are you sure you want to delete "${jobTitle}"? This action cannot be undone and will permanently remove the job and all its requests.`)) {
                this.submitDeleteForm(jobId);
            }
        });
    }

    submitDeleteForm(jobId) {
        const csrfToken = this.getFreshCSRFToken();
        if (!csrfToken) {
            showError('CSRF token not found. Please refresh the page and try again.');
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/jobs/${jobId}`;
        form.style.display = 'none';

       
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);

       
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);

        try {
            form.submit();
        } catch (error) {
            console.error('Error submitting form:', error);
            showError('An error occurred while deleting. Please try again.');
        }
    }

    getFreshCSRFToken() {
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        if (metaToken) {
            return metaToken.getAttribute('content');
        }

        const existingForm = document.querySelector('form input[name="_token"]');
        if (existingForm) {
            return existingForm.value;
        }

        console.error('CSRF token not found!');
        return null;
    }
}


class ServiceDeleteHandler {
    constructor() {
        this.init();
    }

    init() {
        // Handle delete button clicks using event delegation
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('service-delete-btn') || 
                e.target.closest('.service-delete-btn')) {
                e.preventDefault();
                
                // Get the button or closest button element
                const button = e.target.classList.contains('service-delete-btn') 
                    ? e.target 
                    : e.target.closest('.service-delete-btn');
                
                const serviceId = button.getAttribute('data-service-id');
                const serviceTitle = button.getAttribute('data-service-title');
                
                this.showDeleteConfirmation(serviceId, serviceTitle);
            }
        });
    }

    showDeleteConfirmation(serviceId, serviceTitle) {
        if (typeof Swal !== 'undefined') {
            this.showSweetAlertDeleteConfirmation(serviceId, serviceTitle);
        } else {
            if (confirm(`Are you sure you want to delete "${serviceTitle}"? This action cannot be undone and will permanently remove the service and all its requests.`)) {
                this.submitDeleteForm(serviceId);
            }
        }
    }

    showSweetAlertDeleteConfirmation(serviceId, serviceTitle) {
        
        const config = createModalConfig('confirmation', {
            title: 'Delete Service?',
            text: `Are you sure you want to delete "${serviceTitle}"? This action cannot be undone and will permanently remove the service and all its requests.`,
            confirmButtonText: 'Yes, Delete Service',
            cancelButtonText: 'Cancel'
        });
        
        Swal.fire(config).then((result) => {
            if (result.isConfirmed) {
                // Show consistent loading state
                showLoadingModal('Deleting Service...', 'Please wait while we delete the service.');
                this.submitDeleteForm(serviceId);
            }
        }).catch((error) => {
            console.error('SweetAlert error:', error);
            // Fallback to native confirm if SweetAlert fails
            if (confirm(`Are you sure you want to delete "${serviceTitle}"? This action cannot be undone and will permanently remove the service and all its requests.`)) {
                this.submitDeleteForm(serviceId);
            }
        });
    }

    submitDeleteForm(serviceId) {
        const csrfToken = this.getFreshCSRFToken();
        if (!csrfToken) {
            showError('CSRF token not found. Please refresh the page and try again.');
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/services/${serviceId}`;
        form.style.display = 'none';

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);

        try {
            form.submit();
        } catch (error) {
            console.error('Error submitting form:', error);
            showError('An error occurred while deleting. Please try again.');
        }
    }

    getFreshCSRFToken() {
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        if (metaToken) {
            return metaToken.getAttribute('content');
        }

        const existingForm = document.querySelector('form input[name="_token"]');
        if (existingForm) {
            return existingForm.value;
        }

        console.error('CSRF token not found!');
        return null;
    }
}


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
            showSuccess(successMessage);
        }

        // Error messages
        const errorMessage = this.getSessionMessage('error');
        if (errorMessage) {
            showError(errorMessage);
        }
    }

    getSessionMessage(type) {
        // This will be populated by the Blade template
        const element = document.querySelector(`[data-session-${type}]`);
        return element ? element.getAttribute(`data-session-${type}`) : null;
    }
}

/**
 * Modal Management
 * Handles opening and closing of modals
 */
class ModalManager {
    constructor() {
        this.activeModal = null;
        this.init();
    }

    init() {
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Close modal when clicking outside
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal') && e.target.style.display === 'flex') {
                this.closeModal(e.target.id);
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.activeModal) {
                this.closeModal(this.activeModal);
            }
        });

        // Setup close buttons
        const closeButtons = document.querySelectorAll('.modal-close');
        closeButtons.forEach((button) => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const modal = button.closest('.modal');
                if (modal) {
                    this.closeModal(modal.id);
                }
            });
        });
    }

    openModal(modalId) {
        // Close any open modal first
        if (this.activeModal) {
            this.closeModal(this.activeModal);
        }

        const modal = document.getElementById(modalId);
        if (!modal) {
            console.error('Modal not found:', modalId);
            return;
        }

        // Show modal
        modal.style.display = 'flex';
        modal.classList.add('modal-open');
        modal.setAttribute('aria-hidden', 'false');

        // Prevent body scroll
        document.body.style.overflow = 'hidden';
        document.body.classList.add('modal-open');

        this.activeModal = modalId;

        // Focus first input
        setTimeout(() => {
            const firstInput = modal.querySelector('input, textarea, select');
            if (firstInput) {
                firstInput.focus();
            }
        }, 100);
    }

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) {
            console.error('Modal not found:', modalId);
            return;
        }

        // Hide modal
        modal.style.display = 'none';
        modal.classList.remove('modal-open');
        modal.setAttribute('aria-hidden', 'true');

        // Reset body scroll
        document.body.style.overflow = '';
        document.body.classList.remove('modal-open');

        if (this.activeModal === modalId) {
            this.activeModal = null;
        }

        // Clear form if exists
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
            // Remove error states
            const errorFields = form.querySelectorAll('.error');
            errorFields.forEach((field) => {
                field.classList.remove('error');
                field.style.borderColor = '';
            });
        }
    }
}

// Global instances
let modalManager;
let categoryDeleteHandler;
let jobDeleteHandler;
let serviceDeleteHandler;
let sessionMessageHandler;

// Global functions for backward compatibility
window.openCategoryModal = function() {
    if (modalManager) {
        modalManager.openModal('categoryModal');
    }
};

window.closeCategoryModal = function() {
    if (modalManager) {
        modalManager.closeModal('categoryModal');
    }
};

window.editCategory = function(categoryId) {
    // Find category data
    const category = window.categoriesData?.find(cat => cat.id === categoryId);
    if (!category) {
        console.error('Category not found:', categoryId);
        return;
    }

    // Fill form with category data
    const form = document.getElementById('categoryForm');
    const title = document.getElementById('categoryModalTitle');
    const method = document.getElementById('categoryMethod');

    if (form) {
        form.action = '/admin/categories/' + categoryId;
    }

    if (title) {
        title.textContent = 'Edit Category';
    }

    if (method) {
        method.value = 'PUT';
    }

    // Fill form fields
    const nameField = document.getElementById('category_name');
    const descField = document.getElementById('category_description');
    const activeField = document.getElementById('category_is_active');

    if (nameField) nameField.value = category.name || '';
    if (descField) descField.value = category.description || '';
    if (activeField) activeField.checked = category.is_active;

    if (modalManager) {
        modalManager.openModal('categoryModal');
    }
};

window.toggleFilters = function(filterId) {
    const filtersContainer = document.getElementById(filterId);
    if (!filtersContainer) {
        console.error('Filters container not found:', filterId);
        return;
    }

    if (filtersContainer.style.display === 'none' || filtersContainer.style.display === '') {
        filtersContainer.style.display = 'block';
        filtersContainer.classList.add('filters-open');
    } else {
        filtersContainer.style.display = 'none';
        filtersContainer.classList.remove('filters-open');
    }
};

window.openJobDetailModal = function(jobData) {
    const modal = document.getElementById('jobDetailModal');
    if (!modal) {
        console.error('Job detail modal not found');
        return;
    }

    // Update modal content
    updateElementText(modal, '.modal-job-title', jobData.title);
    updateElementText(modal, '.modal-job-description', jobData.description);
    updateElementText(modal, '.modal-job-status', jobData.status);
    updateElementText(modal, '.modal-job-client', jobData.clientName);
    updateElementText(modal, '.modal-job-category', jobData.category);
    updateElementText(modal, '.modal-job-budget', jobData.budget);
    updateElementText(modal, '.modal-job-location', jobData.location);
    updateElementText(modal, '.modal-job-deadline', jobData.deadline);
    updateElementText(modal, '.modal-job-assigned', jobData.assignedProfessional);

    // Handle assigned professional section
    const assignedSection = modal.querySelector('.modal-job-assigned-section');
    if (jobData.assignedProfessional) {
        assignedSection.style.display = 'block';
    } else {
        assignedSection.style.display = 'none';
    }

    if (modalManager) {
        modalManager.openModal('jobDetailModal');
    }
};

window.closeJobDetailModal = function() {
    if (modalManager) {
        modalManager.closeModal('jobDetailModal');
    }
};

window.openServiceDetailModal = function(serviceData) {
    const modal = document.getElementById('serviceDetailModal');
    if (!modal) {
        console.error('Service detail modal not found');
        return;
    }

    // Update modal content
    updateElementText(modal, '.modal-service-title', serviceData.title);
    updateElementText(modal, '.modal-service-description', serviceData.description);
    updateElementText(modal, '.modal-service-status', serviceData.status);
    updateElementText(modal, '.modal-service-professional', serviceData.professionalName);
    updateElementText(modal, '.modal-service-specialization', serviceData.specialization);
    updateElementText(modal, '.modal-service-category', serviceData.category);
    updateElementText(modal, '.modal-service-price', serviceData.price);
    updateElementText(modal, '.modal-service-area', serviceData.serviceArea);

    if (modalManager) {
        modalManager.openModal('serviceDetailModal');
    }
};

window.closeServiceDetailModal = function() {
    if (modalManager) {
        modalManager.closeModal('serviceDetailModal');
    }
};

function updateElementText(container, selector, text) {
    const element = container.querySelector(selector);
    if (element && text) {
        element.textContent = text;
        element.style.display = 'block';
    } else if (element) {
        element.style.display = 'none';
    }
}

// Make message functions available globally
window.showSuccess = showSuccess;
window.showError = showError;
window.showInfo = showInfo;
window.showWarning = showWarning;
window.showLoadingModal = showLoadingModal;

/**
 * Initialize all components when DOM is ready
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize modal manager
    modalManager = new ModalManager();

    // Initialize delete handlers
    categoryDeleteHandler = new CategoryDeleteHandler();
    jobDeleteHandler = new JobDeleteHandler();
    serviceDeleteHandler = new ServiceDeleteHandler();

    // Initialize session message handler
    sessionMessageHandler = new SessionMessageHandler();

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

    console.log('Admin Jobs JS initialized successfully with consistent modal styling');
});