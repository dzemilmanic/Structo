
// Global variables
let activeModal = null;
let isInitialized = false;
let modalManager;

// Ensure all functions are available globally immediately
window.openJobModal = openJobModal;
window.closeJobModal = closeJobModal;
window.openServiceModal = openServiceModal;
window.closeServiceModal = closeServiceModal;
window.openServiceRequestModal = openServiceRequestModal;
window.closeServiceRequestModal = closeServiceRequestModal;
window.openJobRequestModal = openJobRequestModal;
window.closeJobRequestModal = closeJobRequestModal;
window.openJobDetailModal = openJobDetailModal;
window.closeJobDetailModal = closeJobDetailModal;
window.openServiceDetailModal = openServiceDetailModal;
window.closeServiceDetailModal = closeServiceDetailModal;
window.editJob = editJob;
window.editService = editService;
window.deleteJob = deleteJob;
window.deleteService = deleteService;
window.requestService = requestService;
window.completeJob = completeJob;
window.openModal = openModal;
window.closeModal = closeModal;
window.toggleFilters = toggleFilters;

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    //console.log('DOM loaded - initializing jobs system...');
    
    initializeModals();
    initializeFormValidation();
    initializeTextareas();
    initializeJobCardClicks();
    initializeServiceCardClicks();
    initializeDeleteSystem();
    handleSessionMessages();
    
    // Initialize modal manager
    modalManager = new ModalManager();
    
    //console.log('Jobs.js initialized successfully');
});

// Fallback initialization
window.addEventListener("load", function () {
    if (!isInitialized) {
        //console.log('Window loaded - fallback initialization...');
        initializeModals();
        initializeDeleteSystem();
    }
});

// ===== CONSISTENT MODAL STYLING =====

/**
 * Unified modal styling configuration
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
 * Create consistent modal configuration
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

// ===== DELETE SYSTEM =====

function initializeDeleteSystem() {
    //console.log('Setting up delete system...');
    
    // Remove any existing listeners first to prevent duplicates
    document.removeEventListener('click', handleDeleteClicks);
    
    // Add click listener for delete buttons using event delegation
    document.addEventListener('click', handleDeleteClicks);
    
    //console.log('Delete system initialized successfully');
    
    // Debug: Log all delete buttons found after a short delay
    setTimeout(() => {
        debugDeleteButtons();
    }, 1000);
}

function debugDeleteButtons() {
    //console.log('=== DELETE BUTTONS DEBUG ===');
    
    const jobDeleteButtons = document.querySelectorAll('.job-delete-btn');
    const serviceDeleteButtons = document.querySelectorAll('.service-delete-btn');
    const allDataButtons = document.querySelectorAll('[data-job-id], [data-service-id]');
    
    //console.log('Job delete buttons (.job-delete-btn):', jobDeleteButtons.length);
    jobDeleteButtons.forEach((btn, index) => {
        console.log(`  Job button ${index}:`, {
            element: btn,
            className: btn.className,
            jobId: btn.getAttribute('data-job-id'),
            jobTitle: btn.getAttribute('data-job-title'),
            onclick: btn.getAttribute('onclick')
        });
    });
    
    //console.log('Service delete buttons (.service-delete-btn):', serviceDeleteButtons.length);
    serviceDeleteButtons.forEach((btn, index) => {
        console.log(`  Service button ${index}:`, {
            element: btn,
            className: btn.className,
            serviceId: btn.getAttribute('data-service-id'),
            serviceTitle: btn.getAttribute('data-service-title'),
            onclick: btn.getAttribute('onclick')
        });
    });
    
    //console.log('All data attribute buttons:', allDataButtons.length);
    allDataButtons.forEach((btn, index) => {
        console.log(`  Data button ${index}:`, {
            element: btn,
            className: btn.className,
            jobId: btn.getAttribute('data-job-id'),
            serviceId: btn.getAttribute('data-service-id')
        });
    });
    
    //console.log('CSRF token available:', getCSRFToken() ? 'YES' : 'NO');
    //console.log('SweetAlert available:', typeof Swal !== 'undefined' ? 'YES' : 'NO');
    //console.log('========================');
}

function handleDeleteClicks(event) {
    const target = event.target;
    const button = target.closest('button') || target;
    
    //console.log('=== CLICK EVENT DEBUG ===');
    //console.log('Target element:', target);
    //console.log('Target classes:', target.className);
    //console.log('Button element:', button);
    //console.log('Button classes:', button ? button.className : 'N/A');
    
    // Check for job delete
    if (button && (button.classList.contains('job-delete-btn') || button.getAttribute('data-job-id'))) {
        //console.log('✅ Job delete button detected!');
        event.preventDefault();
        event.stopPropagation();
        
        const jobId = button.getAttribute('data-job-id');
        const jobTitle = button.getAttribute('data-job-title') || 'this job';
        
        //console.log('Job delete - ID:', jobId, 'Title:', jobTitle);
        
        if (jobId) {
            deleteJob(jobId, jobTitle);
        } else {
            console.error('❌ Job ID not found!');
        }
        return;
    }
    
    // Check for service delete
    if (button && (button.classList.contains('service-delete-btn') || button.getAttribute('data-service-id'))) {
        //console.log('✅ Service delete button detected!');
        event.preventDefault();
        event.stopPropagation();
        
        const serviceId = button.getAttribute('data-service-id');
        const serviceTitle = button.getAttribute('data-service-title') || 'this service';
        
        //console.log('Service delete - ID:', serviceId, 'Title:', serviceTitle);
        
        if (serviceId) {
            deleteService(serviceId, serviceTitle);
        } else {
            console.error('❌ Service ID not found!');
        }
        return;
    }
    
    //console.log('❌ No delete button detected');
    //console.log('======================');
}

function deleteJob(jobId, jobTitle = 'this job') {
    //console.log('🗑️ deleteJob called with:', jobId, jobTitle);
    
    // Check if SweetAlert is available
    if (typeof Swal === 'undefined') {
        //console.log('⚠️ SweetAlert not available, using confirm');
        if (confirm(`Are you sure you want to delete ${jobTitle}? This action cannot be undone.`)) {
            submitJobDeleteForm(jobId);
        }
        return;
    }
    
    // Use consistent modal styling
    const config = createModalConfig('confirmation', {
        title: 'Delete Job?',
        text: `Are you sure you want to delete "${jobTitle}"? This action cannot be undone and will permanently remove the job and all its requests.`,
        confirmButtonText: 'Yes, Delete Job',
        cancelButtonText: 'Cancel'
    });
    
    Swal.fire(config).then((result) => {
        //console.log('SweetAlert result:', result);
        if (result.isConfirmed) {
            // Show consistent loading state
            showLoadingModal('Deleting Job...', 'Please wait while we delete the job.');
            submitJobDeleteForm(jobId);
        }
    }).catch((error) => {
        //console.error('SweetAlert error:', error);
        // Fallback to confirm
        if (confirm(`Are you sure you want to delete ${jobTitle}? This action cannot be undone.`)) {
            submitJobDeleteForm(jobId);
        }
    });
}

function deleteService(serviceId, serviceTitle = 'this service') {
    //console.log('🗑️ deleteService called with:', serviceId, serviceTitle);
    
    // Check if SweetAlert is available
    if (typeof Swal === 'undefined') {
        //console.log('⚠️ SweetAlert not available, using confirm');
        if (confirm(`Are you sure you want to delete ${serviceTitle}? This action cannot be undone.`)) {
            submitServiceDeleteForm(serviceId);
        }
        return;
    }
    
    // Use consistent modal styling
    const config = createModalConfig('confirmation', {
        title: 'Delete Service?',
        text: `Are you sure you want to delete "${serviceTitle}"? This action cannot be undone and will permanently remove the service and all its requests.`,
        confirmButtonText: 'Yes, Delete Service',
        cancelButtonText: 'Cancel'
    });
    
    Swal.fire(config).then((result) => {
        //console.log('SweetAlert result:', result);
        if (result.isConfirmed) {
            // Show consistent loading state
            showLoadingModal('Deleting Service...', 'Please wait while we delete the service.');
            submitServiceDeleteForm(serviceId);
        }
    }).catch((error) => {
        //console.error('SweetAlert error:', error);
        // Fallback to confirm
        if (confirm(`Are you sure you want to delete ${serviceTitle}? This action cannot be undone.`)) {
            submitServiceDeleteForm(serviceId);
        }
    });
}

function submitJobDeleteForm(jobId) {
    //console.log('📤 Submitting job delete form for ID:', jobId);
    
    const csrfToken = getCSRFToken();
    if (!csrfToken) {
        console.error('❌ CSRF token not found!');
        showError('CSRF token not found. Please refresh the page and try again.');
        return;
    }
    
    //console.log('✅ CSRF token found:', csrfToken.substring(0, 10) + '...');
    
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
    
    //console.log('📋 Form created:');
    //console.log('  Action:', form.action);
    //console.log('  Method:', form.method);
    //console.log('  CSRF Token:', csrfInput.value.substring(0, 10) + '...');
    //console.log('  Method Override:', methodInput.value);
    
    try {
        form.submit();
        //console.log('✅ Form submitted successfully');
    } catch (error) {
        //console.error('❌ Error submitting form:', error);
        showError('An error occurred while deleting. Please try again.');
        document.body.removeChild(form);
    }
}

function submitServiceDeleteForm(serviceId) {
    //console.log('📤 Submitting service delete form for ID:', serviceId);
    
    const csrfToken = getCSRFToken();
    if (!csrfToken) {
        //console.error('❌ CSRF token not found!');
        showError('CSRF token not found. Please refresh the page and try again.');
        return;
    }
    
    //console.log('✅ CSRF token found:', csrfToken.substring(0, 10) + '...');
    
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
    
    //console.log('📋 Form created:');
    //console.log('  Action:', form.action);
    //console.log('  Method:', form.method);
    //console.log('  CSRF Token:', csrfInput.value.substring(0, 10) + '...');
    //console.log('  Method Override:', methodInput.value);
    
    try {
        form.submit();
        //console.log('✅ Form submitted successfully');
    } catch (error) {
        //console.error('❌ Error submitting form:', error);
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

// ===== CONSISTENT MESSAGE DISPLAY FUNCTIONS =====

/**
 * Show loading modal with consistent styling
 */
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

/**
 * Show success message with consistent styling
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
 * Show error message with consistent styling
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
 * Show info message with consistent styling
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
 * Show warning message with consistent styling
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

// ===== SESSION MESSAGE HANDLING =====

function handleSessionMessages() {
    //console.log('🔍 Checking for session messages...');
    
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

// ===== MODAL MANAGEMENT SYSTEM =====

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

// ===== CARD CLICK HANDLERS =====

function initializeJobCardClicks() {
    // Add click handlers to job cards (excluding action buttons)
    document.addEventListener('click', function(e) {
        const jobCard = e.target.closest('.job-card');
        if (jobCard && !e.target.closest('.job-actions') && !e.target.closest('button') && !e.target.closest('a')) {
            const jobData = extractJobDataFromCard(jobCard);
            if (jobData) {
                openJobDetailModal(jobData);
            }
        }
    });
}

function initializeServiceCardClicks() {
    // Add click handlers to service cards (excluding action buttons)
    document.addEventListener('click', function(e) {
        const serviceCard = e.target.closest('.service-card');
        if (serviceCard && !e.target.closest('.service-actions') && !e.target.closest('button') && !e.target.closest('a')) {
            const serviceData = extractServiceDataFromCard(serviceCard);
            if (serviceData) {
                openServiceDetailModal(serviceData);
            }
        }
    });
}

function extractJobDataFromCard(jobCard) {
    try {
        const title = jobCard.querySelector('.job-header h3')?.textContent?.trim();
        
        // Get full description from the card - look for the complete text
        let description = '';
        const descElement = jobCard.querySelector('.job-description');
        if (descElement) {
            // Try to get the full text, not just the limited version
            description = descElement.textContent?.trim() || '';
            // If it's truncated, try to get the full description from data attribute or other source
            const fullDesc = descElement.getAttribute('data-full-description') || 
                            descElement.getAttribute('title') || 
                            description;
            description = fullDesc;
        }
        
        const status = jobCard.querySelector('.job-status')?.textContent?.trim();
        const clientName = jobCard.querySelector('.client-info')?.textContent?.replace('Client:', '')?.trim();
        const category = jobCard.querySelector('.category')?.textContent?.trim();
        const budget = jobCard.querySelector('.budget')?.textContent?.trim();
        const location = jobCard.querySelector('.location')?.textContent?.trim();
        const deadline = jobCard.querySelector('.deadline')?.textContent?.trim();
        const assignedProfessional = jobCard.querySelector('.assigned-professional')?.textContent?.replace('Assigned to:', '')?.trim();

        return {
            title,
            description,
            status,
            clientName,
            category,
            budget,
            location,
            deadline,
            assignedProfessional
        };
    } catch (error) {
        console.error('Error extracting job data:', error);
        return null;
    }
}

function extractServiceDataFromCard(serviceCard) {
    try {
        const title = serviceCard.querySelector('.service-header h3')?.textContent?.trim();
        
        // Get full description from the card
        let description = '';
        const descElement = serviceCard.querySelector('.service-description');
        if (descElement) {
            // Try to get the full text, not just the limited version
            description = descElement.textContent?.trim() || '';
            // If it's truncated, try to get the full description from data attribute or other source
            const fullDesc = descElement.getAttribute('data-full-description') || 
                            descElement.getAttribute('title') || 
                            description;
            description = fullDesc;
        }
        
        const status = serviceCard.querySelector('.service-status')?.textContent?.trim();
        const professionalName = serviceCard.querySelector('.professional-info strong')?.textContent?.trim() || 
                                serviceCard.querySelector('.professional-info')?.textContent?.replace('Professional:', '')?.trim();
        const specialization = serviceCard.querySelector('.specialization')?.textContent?.trim();
        const category = serviceCard.querySelector('.category')?.textContent?.trim();
        const price = serviceCard.querySelector('.price')?.textContent?.trim();
        const serviceArea = serviceCard.querySelector('.service-area')?.textContent?.trim();

        return {
            title,
            description,
            status,
            professionalName,
            specialization,
            category,
            price,
            serviceArea
        };
    } catch (error) {
        console.error('Error extracting service data:', error);
        return null;
    }
}

// ===== DETAIL MODAL FUNCTIONS =====

function openJobDetailModal(jobData) {
    //console.log('Opening job detail modal with data:', jobData);
    
    // Populate modal with job data
    const modal = document.getElementById('jobDetailModal');
    if (!modal) {
        //console.error('Job detail modal not found');
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

    // Handle client name link
    const clientLink = modal.querySelector('.modal-job-client-link');
    if (clientLink && jobData.clientName) {
        clientLink.textContent = jobData.clientName;
        clientLink.style.display = 'inline';
    }

    // Handle assigned professional link
    const assignedLink = modal.querySelector('.modal-job-assigned-link');
    const assignedSection = modal.querySelector('.modal-job-assigned-section');
    if (assignedLink && jobData.assignedProfessional) {
        assignedLink.textContent = jobData.assignedProfessional;
        assignedLink.style.display = 'inline';
        assignedSection.style.display = 'block';
    } else if (assignedSection) {
        assignedSection.style.display = 'none';
    }

    openModal('jobDetailModal');
}

function closeJobDetailModal() {
    //console.log('Closing job detail modal');
    closeModal('jobDetailModal');
}

function openServiceDetailModal(serviceData) {
    //console.log('Opening service detail modal with data:', serviceData);
    
    // Populate modal with service data
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

    // Handle professional name link
    const professionalLink = modal.querySelector('.modal-service-professional-link');
    if (professionalLink && serviceData.professionalName) {
        professionalLink.textContent = serviceData.professionalName;
        professionalLink.style.display = 'inline';
    }

    openModal('serviceDetailModal');
}

function closeServiceDetailModal() {
    //console.log('Closing service detail modal');
    closeModal('serviceDetailModal');
}

function updateElementText(container, selector, text) {
    const element = container.querySelector(selector);
    if (element && text) {
        element.textContent = text;
        element.style.display = 'block';
    } else if (element) {
        element.style.display = 'none';
    }
}

// ===== FILTER FUNCTIONS =====

function toggleFilters(filterId) {
    //console.log("Toggling filters:", filterId);
    
    const filtersContainer = document.getElementById(filterId);
    if (!filtersContainer) {
        console.error("Filters container not found:", filterId);
        return;
    }
    
    if (filtersContainer.style.display === 'none' || filtersContainer.style.display === '') {
        filtersContainer.style.display = 'block';
        filtersContainer.classList.add('filters-open');
    } else {
        filtersContainer.style.display = 'none';
        filtersContainer.classList.remove('filters-open');
    }
}

// ===== MODAL FUNCTIONS =====

function initializeModals() {
    //console.log("Initializing modals...");

    // Force hide all modals on load
    const modals = document.querySelectorAll(".modal");
    modals.forEach((modal) => {
        modal.style.display = "none";
        modal.classList.remove("modal-open");
        modal.setAttribute("aria-hidden", "true");
    });

    // Setup event listeners
    setupModalEventListeners();

    // Reset body state
    document.body.style.overflow = "";
    document.body.classList.remove("modal-open");

    isInitialized = true;
    //console.log("Modals initialized successfully");
}

function setupModalEventListeners() {
    // Close modal when clicking outside
    document.addEventListener("click", function (e) {
        if (
            e.target.classList.contains("modal") &&
            e.target.style.display === "flex"
        ) {
            closeModal(e.target.id);
        }
    });

    // Close modal with Escape key
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape" && activeModal) {
            closeModal(activeModal);
        }
    });

    // Setup close buttons
    const closeButtons = document.querySelectorAll(".modal-close");
    closeButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            const modal = this.closest(".modal");
            if (modal) {
                closeModal(modal.id);
            }
        });
    });
}

function openModal(modalId) {
    //console.log("Opening modal:", modalId);

    // Close any open modal first
    if (activeModal) {
        closeModal(activeModal);
    }

    const modal = document.getElementById(modalId);
    if (!modal) {
        console.error("Modal not found:", modalId);
        return;
    }

    // Show modal
    modal.style.display = "flex";
    modal.classList.add("modal-open");
    modal.setAttribute("aria-hidden", "false");

    // Prevent body scroll
    document.body.style.overflow = "hidden";
    document.body.classList.add("modal-open");

    activeModal = modalId;

    // Focus first input
    setTimeout(() => {
        const firstInput = modal.querySelector("input, textarea, select");
        if (firstInput) {
            firstInput.focus();
        }
    }, 100);

    //console.log("Modal opened:", modalId);
}

function closeModal(modalId) {
    //console.log("Closing modal:", modalId);

    const modal = document.getElementById(modalId);
    if (!modal) {
        console.error("Modal not found:", modalId);
        return;
    }

    // Hide modal
    modal.style.display = "none";
    modal.classList.remove("modal-open");
    modal.setAttribute("aria-hidden", "true");

    // Reset body scroll
    document.body.style.overflow = "";
    document.body.classList.remove("modal-open");

    if (activeModal === modalId) {
        activeModal = null;
    }

    // Clear form if exists
    const form = modal.querySelector("form");
    if (form) {
        form.reset();
        // Remove error states
        const errorFields = form.querySelectorAll(".error");
        errorFields.forEach((field) => {
            field.classList.remove("error");
            field.style.borderColor = "";
        });
    }

    //console.log("Modal closed:", modalId);
}

// ===== GLOBAL MODAL FUNCTIONS =====

function openJobModal() {
    //console.log("openJobModal called");
    openModal("jobModal");
}

function closeJobModal() {
    //console.log("closeJobModal called");
    closeModal("jobModal");
}

function openServiceModal() {
    //console.log("openServiceModal called");
    openModal("serviceModal");
}

function closeServiceModal() {
    //console.log("closeServiceModal called");
    closeModal("serviceModal");
}

function openServiceRequestModal() {
    //console.log("openServiceRequestModal called");
    openModal("serviceRequestModal");
}

function closeServiceRequestModal() {
    //console.log("closeServiceRequestModal called");
    closeModal("serviceRequestModal");
}

function openJobRequestModal(jobId) {
    //console.log("openJobRequestModal called with jobId:", jobId);
    
    // Set form action
    const form = document.getElementById('jobRequestForm');
    if (form) {
        form.action = `/jobs/${jobId}/request`;
        //console.log("Form action set to:", form.action);
    } else {
        //console.error("Job request form not found!");
    }
    
    openModal('jobRequestModal');
}

function closeJobRequestModal() {
    //console.log("closeJobRequestModal called");
    closeModal('jobRequestModal');
}

// ===== JOB MANAGEMENT FUNCTIONS =====

function editJob(jobId) {
    //console.log("Editing job:", jobId);
    window.location.href = `/jobs/${jobId}/edit`;
}

// ===== SERVICE MANAGEMENT FUNCTIONS =====

function editService(serviceId) {
    //console.log("Editing service:", serviceId);
    window.location.href = `/services/${serviceId}/edit`;
}

function requestService(serviceId) {
    //console.log("Requesting service:", serviceId);

    // Set form action
    const form = document.getElementById("serviceRequestForm");
    if (form) {
        form.action = `/services/${serviceId}/request`;
    }

    openServiceRequestModal();
}

function completeJob(jobId) {
    //console.log("Completing job:", jobId);

    if (typeof Swal === "undefined") {
        if (confirm("Are you sure you want to mark this job as complete?")) {
            submitForm("POST", `/jobs/${jobId}/complete`);
        }
        return;
    }

    // Use consistent modal styling
    const config = createModalConfig('confirmation', {
        title: 'Mark as Completed?',
        text: 'Are you sure you want to mark this job as complete?',
        confirmButtonText: 'Yes, Complete Job!',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#28a745'
    });

    Swal.fire(config).then((result) => {
        if (result.isConfirmed) {
            showLoadingModal('Completing Job...', 'Please wait while we update the job status.');
            submitForm("POST", `/jobs/${jobId}/complete`);
        }
    });
}

// ===== HELPER FUNCTIONS =====

function submitForm(method, action) {
    const csrfToken = getCSRFToken();
    if (!csrfToken) {
        showError('CSRF token not found. Please refresh the page and try again.');
        return;
    }

    const form = document.createElement("form");
    form.method = "POST";
    form.action = action;

    // Add CSRF token
    const csrfInput = document.createElement("input");
    csrfInput.type = "hidden";
    csrfInput.name = "_token";
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);

    // Add method spoofing for non-POST methods
    if (method !== "POST") {
        const methodInput = document.createElement("input");
        methodInput.type = "hidden";
        methodInput.name = "_method";
        methodInput.value = method;
        form.appendChild(methodInput);
    }

    document.body.appendChild(form);
    form.submit();
}

// ===== FORM VALIDATION =====

function initializeFormValidation() {
    const forms = document.querySelectorAll("form");

    forms.forEach((form) => {
        const requiredFields = form.querySelectorAll("[required]");

        // Real-time validation
        requiredFields.forEach((field) => {
            field.addEventListener("blur", function () {
                validateField(this);
            });

            field.addEventListener("input", function () {
                if (this.classList.contains("error")) {
                    validateField(this);
                }
            });
        });

        // Form submission validation
        form.addEventListener("submit", function (e) {
            let isValid = true;

            requiredFields.forEach((field) => {
                if (!validateField(field)) {
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
                showError("Please fill in all required fields correctly.");

                // Focus first invalid field
                const firstInvalid = form.querySelector(".error");
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }
        });
    });
}

function validateField(field) {
    const value = field.value.trim();
    let isValid = true;

    // Remove existing error styling
    field.classList.remove("error");
    field.style.borderColor = "";

    // Check if required field is empty
    if (field.hasAttribute("required") && !value) {
        isValid = false;
    }

    // Email validation
    if (field.type === "email" && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            isValid = false;
        }
    }

    // Number validation
    if (field.type === "number" && value) {
        const min = field.getAttribute("min");
        if (min && parseFloat(value) < parseFloat(min)) {
            isValid = false;
        }
    }

    // Apply error styling if invalid
    if (!isValid) {
        field.classList.add("error");
        field.style.borderColor = "#dc3545";
    }

    return isValid;
}

// ===== TEXTAREA AUTO-RESIZE =====

function initializeTextareas() {
    const textareas = document.querySelectorAll("textarea");
    textareas.forEach((textarea) => {
        textarea.addEventListener("input", function () {
            this.style.height = "auto";
            this.style.height = this.scrollHeight + "px";
        });
    });
}

// ===== UTILITY FUNCTIONS =====

// Legacy function names for backward compatibility
function showSuccessMessage(message) {
    showSuccess(message);
}

function showErrorMessage(message) {
    showError(message);
}

// ===== DEBUG FUNCTIONS =====

function debugModals() {
    //console.log("=== MODAL DEBUG ===");
    const modals = document.querySelectorAll(".modal");
    modals.forEach((modal, index) => {
        //console.log(`Modal ${index + 1} (${modal.id}):`);
        //console.log("  Display:", window.getComputedStyle(modal).display);
        //console.log("  Classes:", modal.className);
        //console.log("  Active Modal:", activeModal);
    });
    //console.log("==================");
}

// Debug function for delete system
window.debugDeleteSystem = function() {
    debugDeleteButtons();
};

// Make debug functions available globally
window.debugModals = debugModals;

// Make message functions available globally
window.showSuccess = showSuccess;
window.showError = showError;
window.showInfo = showInfo;
window.showWarning = showWarning;
window.showLoadingModal = showLoadingModal;

// Log that the script has loaded
//console.log("Complete Jobs.js loaded successfully - all functions including consistent modals are now available globally");