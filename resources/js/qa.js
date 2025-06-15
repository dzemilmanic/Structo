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
            counter.textContent = `${textarea.value.length} characters`;
            textarea.parentNode.appendChild(counter);

            textarea.addEventListener('input', function() {
                counter.textContent = `${this.value.length} characters`;
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
document.addEventListener("DOMContentLoaded", function () {
    const cards = document.querySelectorAll(".question-card");

    cards.forEach(card => {
        card.addEventListener("click", () => {
            const questionUrl = card.getAttribute("data-url");
            if (questionUrl) {
                window.location.href = questionUrl;
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.delete-testimonial-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            if (!confirm('Are you sure you want to delete this testimonial?')) {
                e.preventDefault();
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
        // Delete question confirmation
        const deleteQuestionForms = document.querySelectorAll('.delete-question-form');
        
        deleteQuestionForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This question and all its answers will be permanently deleted. This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Delete answer confirmation
        const deleteAnswerForms = document.querySelectorAll('.delete-answer-form');
        
        deleteAnswerForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This answer will be permanently deleted. This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
