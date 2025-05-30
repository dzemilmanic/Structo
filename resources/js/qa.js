document.addEventListener('DOMContentLoaded', function() {
    // Handle search form submission
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = document.querySelector('.search-input');
            if (searchInput.value.trim() === '') {
                e.preventDefault();
                searchInput.focus();
            }
        });
    }

    // Handle sort selection changes
    const sortSelect = document.querySelector('.sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('sort', this.value);
            window.location.href = currentUrl.toString();
        });
    }

    // Smooth scroll to answers section when clicking on answer count
    const answerLinks = document.querySelectorAll('.answer-link');
    if (answerLinks.length > 0) {
        answerLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    // Add character count for textareas
    const textareas = document.querySelectorAll('textarea');
    if (textareas.length > 0) {
        textareas.forEach(textarea => {
            const counter = document.createElement('div');
            counter.className = 'form-text text-right';
            counter.textContent = `${textarea.value.length} karaktera`;
            textarea.parentNode.appendChild(counter);

            textarea.addEventListener('input', function() {
                counter.textContent = `${this.value.length} karaktera`;
            });
        });
    }

    // Confirm delete actions
    const deleteButtons = document.querySelectorAll('.delete-btn');
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Da li ste sigurni da želite da obrišete?')) {
                    e.preventDefault();
                }
            });
        });
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 500);
            }, 5000);
        });
    }
});