document.addEventListener('DOMContentLoaded', function() {
    // Setup profile info edit toggles
    const editButtons = document.querySelectorAll('.edit-toggle');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const section = this.closest('.profile-section-content');
            const infoSection = section.querySelector('.profile-info');
            
            // Toggle display
            infoSection.classList.toggle('hidden');
        });
    });
    
    // Setup cancel buttons
    const cancelButtons = document.querySelectorAll('.cancel-edit');
    
    cancelButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const section = this.closest('.profile-section-content');
            const infoSection = section.querySelector('.profile-info');
            
            // Toggle display back
            infoSection.classList.toggle('hidden');
        });
    });
    
    // Flash message fadeout
    const flashMessages = document.querySelectorAll('.flash-message');
    
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            setTimeout(() => {
                message.style.display = 'none';
            }, 300);
        }, 3000);
    });
});
// Profile editing functionality
document.addEventListener('DOMContentLoaded', function() {
    // Get all edit toggle buttons
    const editButtons = document.querySelectorAll('.edit-toggle');
    const cancelButtons = document.querySelectorAll('.cancel-edit');
    
    // Add event listeners to edit buttons
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const section = this.closest('.profile-form');
            const profileInfo = section.querySelector('.profile-info');
            const profileEditForm = section.querySelector('.profile-edit-form');
            
            if (profileInfo && profileEditForm) {
                profileInfo.classList.add('hidden');
                profileEditForm.classList.add('show');
                profileEditForm.style.display = 'block';
                
                // Focus on the first input field
                const firstInput = profileEditForm.querySelector('input, textarea');
                if (firstInput) {
                    firstInput.focus();
                }
            }
        });
    });
    
    // Add event listeners to cancel buttons
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            const section = this.closest('.profile-form');
            const profileInfo = section.querySelector('.profile-info');
            const profileEditForm = section.querySelector('.profile-edit-form');
            
            if (profileInfo && profileEditForm) {
                profileInfo.classList.remove('hidden');
                profileEditForm.classList.remove('show');
                profileEditForm.style.display = 'none';
                
                // Reset form to original values
                const form = profileEditForm.querySelector('form');
                if (form) {
                    form.reset();
                    // Restore original values from data attributes or server-rendered values
                    const inputs = form.querySelectorAll('input, textarea');
                    inputs.forEach(input => {
                        if (input.hasAttribute('data-original-value')) {
                            input.value = input.getAttribute('data-original-value');
                        }
                    });
                }
            }
        });
    });
    
    // Handle escape key to cancel editing
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const activeEditForm = document.querySelector('.profile-edit-form.show');
            if (activeEditForm) {
                const cancelButton = activeEditForm.querySelector('.cancel-edit');
                if (cancelButton) {
                    cancelButton.click();
                }
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