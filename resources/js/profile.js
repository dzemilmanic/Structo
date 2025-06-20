// Profile editing functionality with SweetAlert2
document.addEventListener('DOMContentLoaded', function() {
    // Get all edit toggle buttons
    const editButtons = document.querySelectorAll('.edit-toggle');
    const cancelButtons = document.querySelectorAll('.cancel-edit');
    
    // Add event listeners to edit buttons
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const fieldName = this.getAttribute('data-field');
            
            // Hide all other edit forms first
            hideAllEditForms();
            
            // Show the specific edit form for this field
            const editForm = document.getElementById(`edit-form-${fieldName}`);
            const displayElement = document.getElementById(`display-${fieldName}`);
            
            if (editForm) {
                editForm.style.display = 'block';
                editForm.classList.add('show');
                
                // Hide the display element if it exists
                if (displayElement && displayElement.closest('.profile-display')) {
                    displayElement.closest('.profile-display').style.display = 'none';
                }
                
                // For photo section, hide the entire photo section display
                if (fieldName === 'photo') {
                    const photoContainer = document.querySelector('.profile-photo-container');
                    if (photoContainer) {
                        photoContainer.style.display = 'none';
                    }
                }
                
                // Focus on the first input field
                const firstInput = editForm.querySelector('input:not([type="hidden"]), textarea');
                if (firstInput) {
                    setTimeout(() => firstInput.focus(), 100);
                }
            }
        });
    });
    
    // Add event listeners to cancel buttons
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            const fieldName = this.getAttribute('data-field');
            
            // Show confirmation dialog for cancel
            Swal.fire({
                title: 'Cancel Changes?',
                text: 'Are you sure you want to cancel your changes?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, cancel',
                cancelButtonText: 'No, continue',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    hideEditForm(fieldName);
                    Swal.fire({
                        icon: 'info',
                        title: 'Cancelled',
                        text: 'Changes have been cancelled.',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }
            });
        });
    });
    
    // Function to hide all edit forms
    function hideAllEditForms() {
        const allEditForms = document.querySelectorAll('.profile-edit-form');
        allEditForms.forEach(form => {
            form.style.display = 'none';
            form.classList.remove('show');
        });
        
        // Show all display elements
        const allDisplays = document.querySelectorAll('.profile-display');
        allDisplays.forEach(display => {
            display.style.display = 'flex';
        });
        
        // Show photo container
        const photoContainer = document.querySelector('.profile-photo-container');
        if (photoContainer) {
            photoContainer.style.display = 'flex';
        }
    }
    
    // Function to hide specific edit form
    function hideEditForm(fieldName) {
        const editForm = document.getElementById(`edit-form-${fieldName}`);
        const displayElement = document.getElementById(`display-${fieldName}`);
        
        if (editForm) {
            editForm.style.display = 'none';
            editForm.classList.remove('show');
            
            // Show the display element
            if (displayElement && displayElement.closest('.profile-display')) {
                displayElement.closest('.profile-display').style.display = 'flex';
            }
            
            // For photo section, show the photo container
            if (fieldName === 'photo') {
                const photoContainer = document.querySelector('.profile-photo-container');
                if (photoContainer) {
                    photoContainer.style.display = 'flex';
                }
            }
            
            // Reset form to original values
            const inputs = editForm.querySelectorAll('input:not([type="hidden"]), textarea');
            inputs.forEach(input => {
                if (input.hasAttribute('data-original-value')) {
                    input.value = input.getAttribute('data-original-value') || '';
                }
            });
        }
    }
    
    // Handle form submissions with loading states and SweetAlert2
    const forms = document.querySelectorAll('.profile-edit-form form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            // Show loading state
            submitButton.textContent = 'Updating...';
            submitButton.disabled = true;
            
            // Show loading alert
            Swal.fire({
                title: 'Updating Profile...',
                text: 'Please wait while we update your information.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Create FormData and submit via fetch
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token')
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.text();
                }
                throw new Error('Network response was not ok');
            })
            .then(data => {
                // Close loading alert
                Swal.close();
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Profile updated successfully.',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                }).then(() => {
                    // Reload page to show updated data
                    window.location.reload();
                });
            })
            .catch(error => {
                // Close loading alert
                Swal.close();
                
                // Reset button state
                submitButton.textContent = originalText;
                submitButton.disabled = false;
                
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while updating your profile. Please try again.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#d33'
                });
            });
        });
    });
    
    // Handle escape key to cancel editing
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const activeEditForm = document.querySelector('.profile-edit-form.show');
            if (activeEditForm) {
                const fieldName = activeEditForm.id.replace('edit-form-', '');
                
                Swal.fire({
                    title: 'Cancel Changes?',
                    text: 'You pressed Escape. Do you want to cancel your changes?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, cancel',
                    cancelButtonText: 'No, continue',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6'
                }).then((result) => {
                    if (result.isConfirmed) {
                        hideEditForm(fieldName);
                    }
                });
            }
        }
    });
    
    // Handle photo preview with SweetAlert2
    const photoInput = document.getElementById('photo');
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Basic file validation
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                const maxSize = 2 * 1024 * 1024; // 2MB
                
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Please select a valid image file (JPEG, PNG, JPG, GIF)',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#d33'
                    });
                    this.value = '';
                    return;
                }
                
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'File size must be less than 2MB',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#d33'
                    });
                    this.value = '';
                    return;
                }
                
                // Show success message for valid file
                Swal.fire({
                    icon: 'success',
                    title: 'File Selected',
                    text: `Selected image: ${file.name}`,
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
        });
    }
    
    // Handle textarea auto-resize for bio
    const bioTextarea = document.getElementById('bio');
    if (bioTextarea) {
        function resizeTextarea() {
            bioTextarea.style.height = 'auto';
            bioTextarea.style.height = (bioTextarea.scrollHeight) + 'px';
        }
        
        bioTextarea.addEventListener('input', resizeTextarea);
        bioTextarea.addEventListener('focus', resizeTextarea);
        
        // Initial resize
        resizeTextarea();
    }
    
    // Update display values after successful update
    function updateDisplayValue(fieldName, newValue) {
        const displayElement = document.getElementById(`display-${fieldName}`);
        if (displayElement) {
            if (newValue && newValue.trim() !== '') {
                displayElement.textContent = newValue;
                displayElement.classList.remove('empty');
            } else {
                const placeholders = {
                    'specialization': 'Not specified',
                    'bio': 'Tell us about yourself...',
                    'phone': 'Not specified',
                    'location': 'Not specified'
                };
                displayElement.textContent = placeholders[fieldName] || 'Not specified';
                displayElement.classList.add('empty');
            }
        }
    }
});