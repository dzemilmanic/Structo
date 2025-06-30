/**
 * Jobs Management JavaScript - Enhanced with Delete Confirmations and Session Messages
 * Handles modal management, delete confirmations, and session notifications
 */

/**
 * Job Delete Confirmation Handler - Clean Dialog Without Icons
 * Shows a clean, professional confirmation dialog for job deletion
 */
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
        // Check if SweetAlert is available
        if (typeof Swal !== 'undefined') {
            this.showSweetAlertDeleteConfirmation(jobId, jobTitle);
        } else {
            // Fallback to native confirm
            if (confirm(`Are you sure you want to delete "${jobTitle}"? This action cannot be undone and will permanently remove the job and all its requests.`)) {
                this.submitDeleteForm(jobId);
            }
        }
    }

    showSweetAlertDeleteConfirmation(jobId, jobTitle) {
        // Clean SweetAlert without any icons - just text and buttons
        Swal.fire({
            title: 'Delete Job?',
            text: `Are you sure you want to delete "${jobTitle}"? This action cannot be undone and will permanently remove the job and all its requests.`,
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete Job',
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
                    title: 'Deleting Job...',
                    text: 'Please wait while we delete the job.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    icon: false, // No icon for loading either
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the delete form
                this.submitDeleteForm(jobId);
            }
        }).catch((error) => {
            console.error('SweetAlert error:', error);
            // Fallback to native confirm if SweetAlert fails
            if (confirm(`Are you sure you want to delete "${jobTitle}"? This action cannot be undone and will permanently remove the job and all its requests.`)) {
                this.submitDeleteForm(jobId);
            }
        });
    }

    submitDeleteForm(jobId) {
        const csrfToken = this.getFreshCSRFToken();
        if (!csrfToken) {
            Swal.fire({
                title: 'Error',
                text: 'CSRF token not found. Please refresh the page and try again.',
                icon: false,
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
            return;
        }

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

        // Add method spoofing for DELETE
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
            Swal.fire({
                title: 'Error',
                text: 'An error occurred while deleting. Please try again.',
                icon: false,
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
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

/**
 * Service Delete Confirmation Handler - Clean Dialog Without Icons
 * Shows a clean, professional confirmation dialog for service deletion
 */
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
        // Check if SweetAlert is available
        if (typeof Swal !== 'undefined') {
            this.showSweetAlertDeleteConfirmation(serviceId, serviceTitle);
        } else {
            // Fallback to native confirm
            if (confirm(`Are you sure you want to delete "${serviceTitle}"? This action cannot be undone and will permanently remove the service and all its requests.`)) {
                this.submitDeleteForm(serviceId);
            }
        }
    }

    showSweetAlertDeleteConfirmation(serviceId, serviceTitle) {
        // Clean SweetAlert without any icons - just text and buttons
        Swal.fire({
            title: 'Delete Service?',
            text: `Are you sure you want to delete "${serviceTitle}"? This action cannot be undone and will permanently remove the service and all its requests.`,
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete Service',
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
                    title: 'Deleting Service...',
                    text: 'Please wait while we delete the service.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    icon: false, // No icon for loading either
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the delete form
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
            Swal.fire({
                title: 'Error',
                text: 'CSRF token not found. Please refresh the page and try again.',
                icon: false,
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
            return;
        }

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

        // Add method spoofing for DELETE
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
            Swal.fire({
                title: 'Error',
                text: 'An error occurred while deleting. Please try again.',
                icon: false,
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
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

// Global variables
let activeModal = null;
let isInitialized = false;
let modalManager;
let jobDeleteHandler;
let serviceDeleteHandler;
let sessionMessageHandler;

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
window.deleteJob = deleteJob;
window.editService = editService;
window.deleteService = deleteService;
window.requestService = requestService;
window.completeJob = completeJob;
window.openModal = openModal;
window.closeModal = closeModal;
window.toggleFilters = toggleFilters;

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    initializeModals();
    initializeFormValidation();
    initializeTextareas();
    initializeJobCardClicks();
    initializeServiceCardClicks();
    
    // Initialize handlers
    modalManager = new ModalManager();
    jobDeleteHandler = new JobDeleteHandler();
    serviceDeleteHandler = new ServiceDeleteHandler();
    sessionMessageHandler = new SessionMessageHandler();
    
    console.log('All handlers initialized successfully');
});

// Fallback initialization
window.addEventListener("load", function () {
    if (!isInitialized) {
        initializeModals();
    }
});

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
    console.log('Opening job detail modal with data:', jobData);
    
    // Populate modal with job data
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

    // Handle client name link
    const clientLink = modal.querySelector('.modal-job-client-link');
    if (clientLink && jobData.clientName) {
        clientLink.textContent = jobData.clientName;
        clientLink.style.display = 'inline';
        // You can add actual user ID here if available
        // clientLink.href = `/users/${userId}`;
    }

    // Handle assigned professional link
    const assignedLink = modal.querySelector('.modal-job-assigned-link');
    const assignedSection = modal.querySelector('.modal-job-assigned-section');
    if (assignedLink && jobData.assignedProfessional) {
        assignedLink.textContent = jobData.assignedProfessional;
        assignedLink.style.display = 'inline';
        assignedSection.style.display = 'block';
        // You can add actual user ID here if available
        // assignedLink.href = `/users/${professionalId}`;
    } else if (assignedSection) {
        assignedSection.style.display = 'none';
    }

    openModal('jobDetailModal');
}

function closeJobDetailModal() {
    console.log('Closing job detail modal');
    closeModal('jobDetailModal');
}

function openServiceDetailModal(serviceData) {
    console.log('Opening service detail modal with data:', serviceData);
    
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
        // You can add actual user ID here if available
        // professionalLink.href = `/users/${professionalId}`;
    }

    openModal('serviceDetailModal');
}

function closeServiceDetailModal() {
    console.log('Closing service detail modal');
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
    console.log("Toggling filters:", filterId);
    
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
    console.log("Initializing modals...");

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
    console.log("Modals initialized successfully");
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
    console.log("Opening modal:", modalId);

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

    console.log("Modal opened:", modalId);
}

function closeModal(modalId) {
    console.log("Closing modal:", modalId);

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

    console.log("Modal closed:", modalId);
}

// ===== GLOBAL MODAL FUNCTIONS =====

function openJobModal() {
    console.log("openJobModal called");
    openModal("jobModal");
}

function closeJobModal() {
    console.log("closeJobModal called");
    closeModal("jobModal");
}

function openServiceModal() {
    console.log("openServiceModal called");
    openModal("serviceModal");
}

function closeServiceModal() {
    console.log("closeServiceModal called");
    closeModal("serviceModal");
}

function openServiceRequestModal() {
    console.log("openServiceRequestModal called");
    openModal("serviceRequestModal");
}

function closeServiceRequestModal() {
    console.log("closeServiceRequestModal called");
    closeModal("serviceRequestModal");
}

function openJobRequestModal(jobId) {
    console.log("openJobRequestModal called with jobId:", jobId);
    
    // Set form action
    const form = document.getElementById('jobRequestForm');
    if (form) {
        form.action = `/jobs/${jobId}/request`;
        console.log("Form action set to:", form.action);
    } else {
        console.error("Job request form not found!");
    }
    
    openModal('jobRequestModal');
}

function closeJobRequestModal() {
    console.log("closeJobRequestModal called");
    closeModal('jobRequestModal');
}

// ===== JOB MANAGEMENT FUNCTIONS =====

function editJob(jobId) {
    console.log("Editing job:", jobId);
    window.location.href = `/jobs/${jobId}/edit`;
}

function deleteJob(jobId) {
    console.log("Deleting job:", jobId);

    // Find the job title from the card for better UX
    const jobCard = document.querySelector(`[onclick*="${jobId}"], button[data-job-id="${jobId}"]`)?.closest('.job-card');
    const jobTitle = jobCard?.querySelector('.job-header h3')?.textContent?.trim() || 'this job';

    if (typeof Swal === "undefined") {
        if (confirm(`Are you sure you want to delete ${jobTitle}?`)) {
            submitDeleteForm("jobs", jobId);
        }
        return;
    }

    // Use the JobDeleteHandler style
    Swal.fire({
        title: 'Delete Job?',
        text: `Are you sure you want to delete "${jobTitle}"? This action cannot be undone and will permanently remove the job and all its requests.`,
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete Job',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        focusCancel: true,
        allowOutsideClick: true,
        allowEscapeKey: true,
        buttonsStyling: true,
        icon: false,
        iconHtml: ''
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Deleting Job...',
                text: 'Please wait while we delete the job.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                icon: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            submitDeleteForm("jobs", jobId);
        }
    });
}

// ===== SERVICE MANAGEMENT FUNCTIONS =====

function editService(serviceId) {
    console.log("Editing service:", serviceId);
    window.location.href = `/services/${serviceId}/edit`;
}

function deleteService(serviceId) {
    console.log("Deleting service:", serviceId);

    // Find the service title from the card for better UX
    const serviceCard = document.querySelector(`[onclick*="${serviceId}"], button[data-service-id="${serviceId}"]`)?.closest('.service-card');
    const serviceTitle = serviceCard?.querySelector('.service-header h3')?.textContent?.trim() || 'this service';

    if (typeof Swal === "undefined") {
        if (confirm(`Are you sure you want to delete ${serviceTitle}?`)) {
            submitDeleteForm("services", serviceId);
        }
        return;
    }

    // Use the ServiceDeleteHandler style
    Swal.fire({
        title: 'Delete Service?',
        text: `Are you sure you want to delete "${serviceTitle}"? This action cannot be undone and will permanently remove the service and all its requests.`,
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete Service',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        focusCancel: true,
        allowOutsideClick: true,
        allowEscapeKey: true,
        buttonsStyling: true,
        icon: false,
        iconHtml: ''
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Deleting Service...',
                text: 'Please wait while we delete the service.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                icon: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            submitDeleteForm("services", serviceId);
        }
    });
}

function requestService(serviceId) {
    console.log("Requesting service:", serviceId);

    // Set form action
    const form = document.getElementById("serviceRequestForm");
    if (form) {
        form.action = `/services/${serviceId}/request`;
    }

    openServiceRequestModal();
}

function completeJob(jobId) {
    console.log("Completing job:", jobId);

    if (typeof Swal === "undefined") {
        if (confirm("Are you sure you want to mark this job as complete?")) {
            submitForm("POST", `/jobs/${jobId}/complete`);
        }
        return;
    }

    Swal.fire({
        title: "Mark as completed?",
        text: "Are you sure you want to mark this job as complete?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, confirm!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            submitForm("POST", `/jobs/${jobId}/complete`);
        }
    });
}

// ===== HELPER FUNCTIONS =====

// Function to get fresh CSRF token
function getFreshCSRFToken() {
    const metaToken = document.querySelector('meta[name="csrf-token"]');
    if (metaToken) {
        return metaToken.getAttribute("content");
    }

    // Try to get from any existing form
    const existingForm = document.querySelector('form input[name="_token"]');
    if (existingForm) {
        return existingForm.value;
    }

    console.error("CSRF token not found!");
    return null;
}

function submitDeleteForm(type, id) {
    console.log(`Submitting delete form for ${type} with ID: ${id}`);

    // Get fresh CSRF token
    const csrfToken = getFreshCSRFToken();
    if (!csrfToken) {
        Swal.fire({
            title: "Error!",
            text: "CSRF token not found. Please refresh the page and try again.",
            icon: false,
            confirmButtonColor: "#d33",
        });
        return;
    }

    // Create form element
    const form = document.createElement("form");
    form.method = "POST";
    form.action = `/${type}/${id}`;
    form.style.display = "none";

    // Add CSRF token
    const csrfInput = document.createElement("input");
    csrfInput.type = "hidden";
    csrfInput.name = "_token";
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);
    console.log("CSRF token added:", csrfToken);

    // Add method spoofing for DELETE
    const methodInput = document.createElement("input");
    methodInput.type = "hidden";
    methodInput.name = "_method";
    methodInput.value = "DELETE";
    form.appendChild(methodInput);
    console.log("Method spoofing added: DELETE");

    // Append form to body and submit
    document.body.appendChild(form);
    console.log("Form created and appended:", form);
    console.log("Form action:", form.action);
    console.log("Form method:", form.method);

    // Submit the form
    try {
        form.submit();
    } catch (error) {
        console.error("Error submitting form:", error);
        Swal.fire({
            title: "Error!",
            text: "An error occurred while deleting. Please try again.",
            icon: false,
            confirmButtonColor: "#d33",
        });
    }
}

function submitForm(method, action) {
    const csrfToken = getFreshCSRFToken();
    if (!csrfToken) {
        Swal.fire({
            title: "Error!",
            text: "CSRF token not found. Please refresh the page and try again.",
            icon: false,
            confirmButtonColor: "#d33",
        });
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
                showErrorMessage(
                    "Please fill in all required fields correctly."
                );

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

function showSuccessMessage(message) {
    if (typeof Swal !== "undefined") {
        Swal.fire({
            title: "Success!",
            text: message,
            icon: "success",
            confirmButtonColor: "#28a745",
            timer: 3000,
            timerProgressBar: true,
        });
    } else {
        alert(message);
    }
}

function showErrorMessage(message) {
    if (typeof Swal !== "undefined") {
        Swal.fire({
            title: "Error!",
            text: message,
            icon: false,
            confirmButtonColor: "#dc3545",
        });
    } else {
        alert(message);
    }
}

// ===== DEBUG FUNCTION =====

function debugModals() {
    console.log("=== MODAL DEBUG ===");
    const modals = document.querySelectorAll(".modal");
    modals.forEach((modal, index) => {
        console.log(`Modal ${index + 1} (${modal.id}):`);
        console.log("  Display:", window.getComputedStyle(modal).display);
        console.log("  Classes:", modal.className);
        console.log("  Active Modal:", activeModal);
    });
    console.log("==================");
}

// Make debug function available globally
window.debugModals = debugModals;

// Log that the script has loaded
console.log(
    "Jobs.js loaded successfully - all functions are now available globally with enhanced delete confirmations and session messages"
);