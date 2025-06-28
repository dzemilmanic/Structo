// ===== PROFESSIONAL REQUEST SYSTEM WITH FILE UPLOAD =====

// Global variables
let selectedFiles = [];
let maxFiles = 5;
let maxFileSize = 10 * 1024 * 1024; // 10MB
let allowedTypes = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'image/jpeg',
    'image/jpg',
    'image/png',
    'image/gif',
    'image/webp'
];

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeProfessionalRequest();
    initializeProfessionalsSearch();
    showSessionMessages();
});

function initializeProfessionalRequest() {
    const becomeProfessionalBtn = document.getElementById('becomeProfessionalBtn');
    
    if (becomeProfessionalBtn) {
        becomeProfessionalBtn.addEventListener('click', function() {
            showProfessionalRequestModal();
        });
    }
}

function initializeProfessionalsSearch() {
    const searchInput = document.getElementById('professionalsSearchInput');
    const professionalsGrid = document.getElementById('professionalsGrid');
    
    if (searchInput && professionalsGrid) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const cards = professionalsGrid.querySelectorAll('.profi-card');
            
            cards.forEach(card => {
                const searchData = card.getAttribute('data-search') || '';
                if (searchData.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
}

function showSessionMessages() {
    // Show success/error notifications
    const sessionSuccess = document.querySelector('meta[name="session-success"]');
    if (sessionSuccess) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: sessionSuccess.content,
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            customClass: {
                popup: 'swal2-toast-custom'
            }
        });
    }

    const sessionError = document.querySelector('meta[name="session-error"]');
    if (sessionError) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: sessionError.content,
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            customClass: {
                popup: 'swal2-toast-custom'
            }
        });
    }

    const validationErrors = document.querySelector('meta[name="validation-errors"]');
    if (validationErrors) {
        Swal.fire({
            icon: 'error',
            title: 'Validation Errors',
            text: validationErrors.content,
            confirmButtonText: 'OK',
            customClass: {
                popup: 'swal2-popup-custom',
                confirmButton: 'swal2-confirm-custom'
            }
        });
    }
}

function showProfessionalRequestModal() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Add backdrop blur effect
    document.body.classList.add('modal-backdrop-blur');
    
    Swal.fire({
        title: 'Become a Professional',
        html: createModalHTML(csrfToken),
        width: '700px',
        padding: '0',
        showCancelButton: true,
        confirmButtonText: `
            <svg class="swal-btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            Send Request
        `,
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        backdrop: true,
        allowOutsideClick: true,
        customClass: {
            popup: 'swal2-popup-professional',
            title: 'swal2-title-professional',
            confirmButton: 'swal2-confirm-professional',
            cancelButton: 'swal2-cancel-professional',
            htmlContainer: 'swal2-html-professional'
        },
        didOpen: () => {
            initializeFileUpload();
        },
        willClose: () => {
            // Remove backdrop blur effect when modal closes
            document.body.classList.remove('modal-backdrop-blur');
        },
        preConfirm: () => {
            return validateAndGetFormData();
        }
    }).then((result) => {
        if (result.isConfirmed) {
            submitProfessionalRequest(result.value);
        }
    });
}

function createModalHTML(csrfToken) {
    return `
        <div class="professional-modal-header">
            <div class="modal-header-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h2>Become a Professional</h2>
            <p>Join our verified professionals and unlock exclusive features</p>
        </div>

        <form id="professionalRequestForm" enctype="multipart/form-data" class="professional-modal-form">
            <input type="hidden" name="_token" value="${csrfToken}">
            
            <div class="form-group">
                <label for="swal-specialization" class="form-label">
                    <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6z"/>
                    </svg>
                    Specialization *
                </label>
                <input type="text" name="specialization" id="swal-specialization" class="form-input" 
                       placeholder="e.g., Full Stack Developer, UI/UX Designer, Data Scientist" required>
                <small class="form-text">Describe your professional expertise and main area of specialization</small>
            </div>

            <div class="form-group">
                <label for="swal-files" class="form-label">
                    <svg class="label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Professional Documents (Optional)
                </label>
                
                <div class="file-upload-container">
                    <div class="file-drop-zone" id="fileDropZone">
                        <svg class="file-upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="file-upload-text">
                            <strong>Click to upload</strong> or drag and drop files here
                        </p>
                        <p class="file-upload-subtext">
                            PDF, Word documents, or images (Max: ${maxFiles} files, 10MB each)
                        </p>
                        <input type="file" id="swal-files" name="files[]" multiple 
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.webp" 
                               class="file-input-hidden">
                    </div>
                    
                    <div class="selected-files-container" id="selectedFilesContainer" style="display: none;">
                        <h6 class="selected-files-title">Selected Files:</h6>
                        <div class="selected-files-list" id="selectedFilesList"></div>
                    </div>
                </div>
                
                <small class="form-text">
                    Upload certificates, diplomas, portfolio samples, or other proof of your professional qualifications
                </small>
            </div>

            <div class="professional-benefits">
                <h6 class="benefits-title">
                    <svg class="benefits-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    Professional Benefits
                </h6>
                <ul class="benefits-list">
                    <li>
                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Verified professional badge
                    </li>
                    <li>
                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Enhanced profile visibility
                    </li>
                    <li>
                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Access to professional features
                    </li>
                    <li>
                        <svg class="benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Priority in search results
                    </li>
                </ul>
            </div>
        </form>
    `;
}

function initializeFileUpload() {
    const fileInput = document.getElementById('swal-files');
    const dropZone = document.getElementById('fileDropZone');
    
    // Reset selected files
    selectedFiles = [];
    
    // File input change event
    fileInput.addEventListener('change', handleFileSelect);
    
    // Drag and drop events
    dropZone.addEventListener('click', () => fileInput.click());
    dropZone.addEventListener('dragover', handleDragOver);
    dropZone.addEventListener('dragleave', handleDragLeave);
    dropZone.addEventListener('drop', handleFileDrop);
}

function handleFileSelect(event) {
    const files = Array.from(event.target.files);
    processSelectedFiles(files);
}

function handleDragOver(event) {
    event.preventDefault();
    event.stopPropagation();
    event.currentTarget.classList.add('drag-over');
}

function handleDragLeave(event) {
    event.preventDefault();
    event.stopPropagation();
    event.currentTarget.classList.remove('drag-over');
}

function handleFileDrop(event) {
    event.preventDefault();
    event.stopPropagation();
    event.currentTarget.classList.remove('drag-over');
    
    const files = Array.from(event.dataTransfer.files);
    processSelectedFiles(files);
}

function processSelectedFiles(files) {
    const errors = [];
    const validFiles = [];
    
    // Check total file count
    if (selectedFiles.length + files.length > maxFiles) {
        errors.push(`Maximum ${maxFiles} files allowed. You can select ${maxFiles - selectedFiles.length} more files.`);
        return;
    }
    
    files.forEach(file => {
        // Check file type
        if (!allowedTypes.includes(file.type)) {
            errors.push(`${file.name}: Invalid file type. Only PDF, Word documents, and images are allowed.`);
            return;
        }
        
        // Check file size
        if (file.size > maxFileSize) {
            errors.push(`${file.name}: File size exceeds 10MB limit.`);
            return;
        }
        
        // Check for duplicates
        if (selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
            errors.push(`${file.name}: File already selected.`);
            return;
        }
        
        validFiles.push(file);
    });
    
    if (errors.length > 0) {
        Swal.showValidationMessage(errors.join('<br>'));
        return;
    }
    
    // Add valid files to selection
    selectedFiles.push(...validFiles);
    updateFilesList();
}

function updateFilesList() {
    const container = document.getElementById('selectedFilesContainer');
    const list = document.getElementById('selectedFilesList');
    
    if (selectedFiles.length === 0) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'block';
    list.innerHTML = '';
    
    selectedFiles.forEach((file, index) => {
        const fileItem = document.createElement('div');
        fileItem.className = 'selected-file-item';
        fileItem.innerHTML = `
            <div class="file-info">
                <svg class="file-icon ${getFileIconClass(file.type)}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${getFileIconPath(file.type)}
                </svg>
                <div class="file-details">
                    <span class="file-name">${file.name}</span>
                    <span class="file-size">${formatFileSize(file.size)}</span>
                </div>
            </div>
            <button type="button" class="remove-file-btn" onclick="removeFile(${index})">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        list.appendChild(fileItem);
    });
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    updateFilesList();
}

function getFileIconClass(mimeType) {
    if (mimeType.includes('pdf')) return 'file-pdf';
    if (mimeType.includes('word') || mimeType.includes('document')) return 'file-word';
    if (mimeType.includes('image')) return 'file-image';
    return 'file-generic';
}

function getFileIconPath(mimeType) {
    if (mimeType.includes('pdf')) {
        return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>';
    }
    if (mimeType.includes('word') || mimeType.includes('document')) {
        return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>';
    }
    if (mimeType.includes('image')) {
        return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>';
    }
    return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function validateAndGetFormData() {
    const specialization = document.getElementById('swal-specialization').value;
    
    if (!specialization.trim()) {
        Swal.showValidationMessage('Please enter your specialization');
        return false;
    }
    
    return {
        specialization: specialization,
        files: selectedFiles
    };
}

function submitProfessionalRequest(data) {
    // Show loading
    Swal.fire({
        title: 'Submitting Request...',
        text: 'Please wait while we process your professional request.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Create FormData
    const formData = new FormData();
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    formData.append('_token', csrfToken);
    formData.append('specialization', data.specialization);
    
    // Append files
    data.files.forEach((file, index) => {
        formData.append(`files[${index}]`, file);
    });

    // Get route URL
    const routeUrl = '/profi-requests';

    // Submit form
    fetch(routeUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Request Submitted!',
                text: data.message || 'Your professional request has been submitted successfully and is pending review.',
                confirmButtonText: 'Great!',
                customClass: {
                    popup: 'swal2-popup-custom',
                    confirmButton: 'swal2-confirm-custom'
                }
            }).then(() => {
                // Reload page to update UI
                window.location.reload();
            });
        } else {
            throw new Error(data.message || 'An error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Submission Failed',
            text: error.message || 'Failed to submit your request. Please try again.',
            confirmButtonText: 'OK',
            customClass: {
                popup: 'swal2-popup-custom',
                confirmButton: 'swal2-confirm-custom'
            }
        });
    });
}

// Make functions globally available
window.removeFile = removeFile;