document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('professionalsSearchInput');
    const professionalsGrid = document.getElementById('professionalsGrid');
    const profiCards = professionalsGrid.querySelectorAll('.profi-card');

    if (searchInput && professionalsGrid) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;

            profiCards.forEach(card => {
                const searchData = card.getAttribute('data-search');
                if (searchData && searchData.includes(searchTerm)) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Show/hide no results message
            const noProfiElement = professionalsGrid.querySelector('.no-professionals');
            if (visibleCount === 0 && searchTerm !== '') {
                if (!noProfiElement) {
                    const noResults = document.createElement('div');
                    noResults.className = 'no-professionals search-no-results';
                    noResults.innerHTML = `
                        <div class="no-professionals-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="no-professionals-title">No Results Found</h3>
                        <p class="no-professionals-text">No professionals match your search criteria. Try different keywords.</p>
                    `;
                    professionalsGrid.appendChild(noResults);
                }
            } else {
                const searchNoResults = professionalsGrid.querySelector('.search-no-results');
                if (searchNoResults) {
                    searchNoResults.remove();
                }
            }
        });
    }

    // Professional request modal
    const becomeProfessionalBtn = document.getElementById('becomeProfessionalBtn');
    
    if (becomeProfessionalBtn) {
        becomeProfessionalBtn.addEventListener('click', function() {
            showProfessionalRequestModal();
        });
    }

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
});

function showProfessionalRequestModal() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    Swal.fire({
        title: '<div class="swal-title-with-icon"><svg class="swal-title-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>Become a Professional</div>',
        html: `
            <form id="professionalRequestForm" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="${csrfToken}">
                
                <div class="swal-form-group">
                    <label for="swal-specialization" class="swal-form-label">
                        <svg class="swal-label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6z"/>
                        </svg>
                        Specialization *
                    </label>
                    <input type="text" name="specialization" id="swal-specialization" class="swal-input" 
                           placeholder="e.g., Full Stack Developer, UI/UX Designer, Data Scientist" required>
                    <small class="swal-form-text">Describe your professional expertise and main area of specialization</small>
                </div>

                <div class="swal-form-group">
                    <label for="swal-image" class="swal-form-label">
                        <svg class="swal-label-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Professional Proof (Optional)
                    </label>
                    <input type="file" name="image" id="swal-image" class="swal-file-input" accept="image/*">
                    <small class="swal-form-text">Upload a certificate, diploma, or other proof of your professional qualifications (Max: 2MB)</small>
                </div>

                <div class="swal-professional-benefits">
                    <h6 class="swal-benefits-title">Professional Benefits:</h6>
                    <ul class="swal-benefits-list">
                        <li>
                            <svg class="swal-benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Verified professional badge
                        </li>
                        <li>
                            <svg class="swal-benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Enhanced profile visibility
                        </li>
                        <li>
                            <svg class="swal-benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Access to professional features
                        </li>
                        <li>
                            <svg class="swal-benefit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Priority in search results
                        </li>
                    </ul>
                </div>
            </form>
        `,
        width: '600px',
        showCancelButton: true,
        confirmButtonText: '<svg class="swal-btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>Send Request',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        customClass: {
            popup: 'swal2-popup-professional',
            confirmButton: 'swal2-confirm-professional',
            cancelButton: 'swal2-cancel-professional',
            htmlContainer: 'swal2-html-professional'
        },
        preConfirm: () => {
            const form = document.getElementById('professionalRequestForm');
            const specialization = document.getElementById('swal-specialization').value;
            const imageFile = document.getElementById('swal-image').files[0];
            
            if (!specialization.trim()) {
                Swal.showValidationMessage('Please enter your specialization');
                return false;
            }
            
            if (imageFile && imageFile.size > 2097152) { // 2MB in bytes
                Swal.showValidationMessage('Image file size must be less than 2MB');
                return false;
            }
            
            return {
                specialization: specialization,
                image: imageFile
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            submitProfessionalRequest(result.value);
        }
    });
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
    if (data.image) {
        formData.append('image', data.image);
    }

    // Get route URL from meta tag or construct it
    const routeUrl = document.querySelector('meta[name="profi-requests-store"]') ? 
        document.querySelector('meta[name="profi-requests-store"]').getAttribute('content') :
        '/profi-requests';

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