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