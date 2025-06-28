// ===== ADMIN JOBS JAVASCRIPT =====

// Global variables
let activeModal = null;
let isInitialized = false;

// Ensure all functions are available globally immediately
window.openCategoryModal = openCategoryModal;
window.closeCategoryModal = closeCategoryModal;
window.editCategory = editCategory;
window.deleteCategory = deleteCategory;
window.deleteJob = deleteJob;
window.deleteService = deleteService;
window.toggleFilters = toggleFilters;
window.openJobDetailModal = openJobDetailModal;
window.closeJobDetailModal = closeJobDetailModal;
window.openServiceDetailModal = openServiceDetailModal;
window.closeServiceDetailModal = closeServiceDetailModal;

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    initializeModals();
    initializeFormValidation();
    initializeTextareas();
    console.log("Admin Jobs JS initialized successfully");
});

// Fallback initialization
window.addEventListener("load", function () {
    if (!isInitialized) {
        initializeModals();
    }
});

// ===== MODAL FUNCTIONS =====

function initializeModals() {
    console.log("Initializing admin jobs modals...");

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
    console.log("Admin jobs modals initialized successfully");
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

// ===== JOB AND SERVICE DETAIL MODAL FUNCTIONS =====

function openJobDetailModal(jobData) {
    console.log('Opening job detail modal with data:', jobData);
    
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

    openModal('jobDetailModal');
}

function closeJobDetailModal() {
    closeModal('jobDetailModal');
}

function openServiceDetailModal(serviceData) {
    console.log('Opening service detail modal with data:', serviceData);
    
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

    openModal('serviceDetailModal');
}

function closeServiceDetailModal() {
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

// ===== CATEGORY MANAGEMENT FUNCTIONS =====

function openCategoryModal() {
    console.log("openCategoryModal called");

    // Reset form for new category
    const form = document.getElementById('categoryForm');
    const title = document.getElementById('categoryModalTitle');
    const method = document.getElementById('categoryMethod');

    if (form) {
        // Set the correct action URL for creating new category
        form.action = '/admin/categories';
        form.reset();
        
        // Make sure checkbox is unchecked by default
        const activeCheckbox = document.getElementById('category_is_active');
        if (activeCheckbox) {
            activeCheckbox.checked = true; // Default to active
        }
    }

    if (title) {
        title.textContent = 'Add Category';
    }

    if (method) {
        method.value = '';
    }

    openModal('categoryModal');
}

function closeCategoryModal() {
    console.log("closeCategoryModal called");
    closeModal('categoryModal');
}

function editCategory(categoryId) {
    console.log("Editing category:", categoryId);

    // Find category data
    const category = window.categoriesData?.find(cat => cat.id === categoryId);
    if (!category) {
        console.error("Category not found:", categoryId);
        showErrorMessage("Category not found!");
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

    openModal('categoryModal');
}

function deleteCategory(categoryId) {
    console.log("Deleting category:", categoryId);

    if (typeof Swal === "undefined") {
        if (confirm("Are you sure you want to delete this category?")) {
            submitDeleteForm("/admin/categories", categoryId);
        }
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: "This will permanently delete the category. You won't be able to undo this action!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, Delete!",
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

            submitDeleteForm("/admin/categories", categoryId);
        }
    });
}

// ===== JOB AND SERVICE MANAGEMENT =====

function deleteJob(jobId) {
    console.log("Deleting job:", jobId);

    if (typeof Swal === "undefined") {
        if (confirm("Are you sure you want to delete this job?")) {
            submitDeleteForm("/admin/jobs", jobId);
        }
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: "This will permanently delete the job and all its requests!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, Delete!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            submitDeleteForm("/admin/jobs", jobId);
        }
    });
}

function deleteService(serviceId) {
    console.log("Deleting service:", serviceId);

    if (typeof Swal === "undefined") {
        if (confirm("Are you sure you want to delete this service?")) {
            submitDeleteForm("/admin/services", serviceId);
        }
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: "This will permanently delete the service and all its requests!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, Delete!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            submitDeleteForm("/admin/services", serviceId);
        }
    });
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

// ===== HELPER FUNCTIONS =====

function getFreshCSRFToken() {
    const metaToken = document.querySelector('meta[name="csrf-token"]');
    if (metaToken) {
        return metaToken.getAttribute("content");
    }

    const existingForm = document.querySelector('form input[name="_token"]');
    if (existingForm) {
        return existingForm.value;
    }

    console.error("CSRF token not found!");
    return null;
}

function submitDeleteForm(type, id) {
    console.log(`Submitting delete form for ${type} with ID: ${id}`);

    const csrfToken = getFreshCSRFToken();
    if (!csrfToken) {
        if (typeof Swal !== "undefined") {
            Swal.fire({
                title: "Error!",
                text: "CSRF token not found. Please refresh the page and try again.",
                icon: "error",
                confirmButtonColor: "#d33",
            });
        } else {
            alert("CSRF token not found. Please refresh the page and try again.");
        }
        return;
    }

    const form = document.createElement("form");
    form.method = "POST";
    form.action = `${type}/${id}`;
    form.style.display = "none";

    // Add CSRF token
    const csrfInput = document.createElement("input");
    csrfInput.type = "hidden";
    csrfInput.name = "_token";
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);

    // Add method spoofing for DELETE
    const methodInput = document.createElement("input");
    methodInput.type = "hidden";
    methodInput.name = "_method";
    methodInput.value = "DELETE";
    form.appendChild(methodInput);

    document.body.appendChild(form);

    try {
        form.submit();
    } catch (error) {
        console.error("Error submitting form:", error);
        if (typeof Swal !== "undefined") {
            Swal.fire({
                title: "Error!",
                text: "An error occurred while deleting. Please try again.",
                icon: "error",
                confirmButtonColor: "#d33",
            });
        } else {
            alert("An error occurred while deleting. Please try again.");
        }
    }
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
                showErrorMessage("Please fill in all required fields correctly.");

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

// Log that the script has loaded
console.log("Admin Jobs JS loaded successfully - all functions are now available globally");