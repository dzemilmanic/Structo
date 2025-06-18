// Profile editing functionality
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
                const firstInput = editForm.querySelector('input, textarea');
                if (firstInput) {
                    firstInput.focus();
                }
            }
        });
    });
    
    // Add event listeners to cancel buttons
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            const fieldName = this.getAttribute('data-field');
            hideEditForm(fieldName);
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
            const form = editForm.querySelector('form');
            if (form) {
                form.reset();
                // Restore original values from server-rendered values
                const inputs = form.querySelectorAll('input, textarea');
                inputs.forEach(input => {
                    if (input.hasAttribute('data-original-value')) {
                        input.value = input.getAttribute('data-original-value');
                    }
                });
            }
        }
    }
    
    // Handle escape key to cancel editing
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const activeEditForm = document.querySelector('.profile-edit-form.show');
            if (activeEditForm) {
                const fieldName = activeEditForm.id.replace('edit-form-', '');
                hideEditForm(fieldName);
            }
        }
    });
    
    // Auto-hide flash messages after 5 seconds
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            setTimeout(() => {
                message.style.display = 'none';
            }, 300);
        }, 5000);
    });
    
    // Handle photo preview
    const photoInput = document.getElementById('photo');
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // You can add photo preview functionality here if needed
                    console.log('Photo selected:', file.name);
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Handle textarea auto-resize for bio
    const bioTextarea = document.getElementById('bio');
    if (bioTextarea) {
        bioTextarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }
});