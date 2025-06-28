// ===== MODAL MANAGEMENT SYSTEM - FIXED VERSION =====

// Global variables
let activeModal = null;
let isInitialized = false;

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
});

// Fallback initialization
window.addEventListener("load", function () {
    if (!isInitialized) {
        initializeModals();
    }
});

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

    if (typeof Swal === "undefined") {
        if (confirm("Are you sure you want to delete this job?")) {
            submitDeleteForm("jobs", jobId);
        }
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to undo this action!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, Confirm!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
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

    if (typeof Swal === "undefined") {
        if (confirm("Are you sure you want to delete this service?")) {
            submitDeleteForm("services", serviceId);
        }
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to undo this action!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, Confirm!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: "Deleting...",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
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
            icon: "error",
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
            icon: "error",
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
            icon: "error",
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
            icon: "error",
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
    "Jobs.js loaded successfully - all functions are now available globally"
);