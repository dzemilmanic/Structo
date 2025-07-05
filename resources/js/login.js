// Password visibility toggle
document.addEventListener('DOMContentLoaded', function() {
    const passwordFields = document.querySelectorAll('input[type="password"]');
    
    passwordFields.forEach(field => {
        // Create toggle button
        const toggleButton = document.createElement('button');
        toggleButton.type = 'button';
        toggleButton.className = 'toggle-password';
        toggleButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5c-7.333 0-12 6-12 6s4.667 6 12 6 12-6 12-6-4.667-6-12-6z"/><circle cx="12" cy="11" r="3"/></svg>';
        
        // Insert toggle button after password field
        field.parentNode.appendChild(toggleButton);
        
        // Toggle password visibility
        toggleButton.addEventListener('click', function() {
            const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
            field.setAttribute('type', type);
            
            // Toggle eye icon
            this.innerHTML = type === 'password' 
                ? '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5c-7.333 0-12 6-12 6s4.667 6 12 6 12-6 12-6-4.667-6-12-6z"/><circle cx="12" cy="11" r="3"/></svg>'
                : '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Password validation elements
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const passwordRequirements = document.getElementById('passwordRequirements');
    const passwordMatchIndicator = document.getElementById('passwordMatchIndicator');
    const registerButton = document.getElementById('registerButton');
    const registerForm = document.getElementById('registerForm');
    
    // Password toggle elements
    const passwordToggle = document.getElementById('passwordToggle');
    const passwordConfirmToggle = document.getElementById('passwordConfirmToggle');
    
    // Requirement elements
    const lengthRequirement = document.getElementById('lengthRequirement');
    const uppercaseRequirement = document.getElementById('uppercaseRequirement');
    const numberRequirement = document.getElementById('numberRequirement');
    const passwordMatch = document.getElementById('passwordMatch');
    
    // Password validation state
    let validationState = {
        length: false,
        uppercase: false,
        number: false,
        match: false
    };
    
    // Show password requirements when user starts typing
    passwordInput.addEventListener('focus', function() {
        passwordRequirements.style.display = 'block';
        passwordRequirements.classList.add('show');
    });
    
    // Real-time password validation
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        
        // Show requirements if not already visible
        if (passwordRequirements.style.display === 'none') {
            passwordRequirements.style.display = 'block';
            passwordRequirements.classList.add('show');
        }
        
        // Check length requirement (minimum 8 characters)
        if (password.length >= 8) {
            validationState.length = true;
            lengthRequirement.classList.add('valid');
            lengthRequirement.classList.remove('invalid');
        } else {
            validationState.length = false;
            lengthRequirement.classList.add('invalid');
            lengthRequirement.classList.remove('valid');
        }
        
        // Check uppercase requirement
        if (/[A-Z]/.test(password)) {
            validationState.uppercase = true;
            uppercaseRequirement.classList.add('valid');
            uppercaseRequirement.classList.remove('invalid');
        } else {
            validationState.uppercase = false;
            uppercaseRequirement.classList.add('invalid');
            uppercaseRequirement.classList.remove('valid');
        }
        
        // Check number requirement
        if (/[0-9]/.test(password)) {
            validationState.number = true;
            numberRequirement.classList.add('valid');
            numberRequirement.classList.remove('invalid');
        } else {
            validationState.number = false;
            numberRequirement.classList.add('invalid');
            numberRequirement.classList.remove('valid');
        }
        
        // Check password match if confirm password has value
        if (passwordConfirmInput.value) {
            checkPasswordMatch();
        }
        
        // Update register button state
        updateRegisterButton();
    });
    
    // Show password match indicator when user starts typing in confirm field
    passwordConfirmInput.addEventListener('focus', function() {
        if (passwordInput.value) {
            passwordMatchIndicator.style.display = 'block';
            passwordMatchIndicator.classList.add('show');
        }
    });
    
    // Real-time password confirmation validation
    passwordConfirmInput.addEventListener('input', function() {
        // Show match indicator if not already visible
        if (passwordMatchIndicator.style.display === 'none' && passwordInput.value) {
            passwordMatchIndicator.style.display = 'block';
            passwordMatchIndicator.classList.add('show');
        }
        
        checkPasswordMatch();
        updateRegisterButton();
    });
    
    // Check if passwords match
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = passwordConfirmInput.value;
        
        if (confirmPassword && password === confirmPassword) {
            validationState.match = true;
            passwordMatch.classList.add('valid');
            passwordMatch.classList.remove('invalid');
        } else if (confirmPassword) {
            validationState.match = false;
            passwordMatch.classList.add('invalid');
            passwordMatch.classList.remove('valid');
        } else {
            validationState.match = false;
            passwordMatch.classList.remove('valid', 'invalid');
        }
    }
    
    // Update register button state
    function updateRegisterButton() {
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        
        const allValid = validationState.length && 
                        validationState.uppercase && 
                        validationState.number && 
                        validationState.match &&
                        nameInput.value.trim() !== '' &&
                        emailInput.value.trim() !== '';
        
        if (allValid) {
            registerButton.disabled = false;
            registerButton.classList.add('enabled');
        } else {
            registerButton.disabled = true;
            registerButton.classList.remove('enabled');
        }
    }
    
    // Also check name and email inputs
    document.getElementById('name').addEventListener('input', updateRegisterButton);
    document.getElementById('email').addEventListener('input', updateRegisterButton);
    
    // Password toggle functionality
    if (passwordToggle) {
        passwordToggle.addEventListener('click', function() {
            togglePasswordVisibility(passwordInput, this);
        });
    }
    
    if (passwordConfirmToggle) {
        passwordConfirmToggle.addEventListener('click', function() {
            togglePasswordVisibility(passwordConfirmInput, this);
        });
    }
    
    function togglePasswordVisibility(input, toggle) {
        const eyeOpen = toggle.querySelector('.eye-open');
        const eyeClosed = toggle.querySelector('.eye-closed');
        
        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.style.display = 'none';
            eyeClosed.style.display = 'block';
        } else {
            input.type = 'password';
            eyeOpen.style.display = 'block';
            eyeClosed.style.display = 'none';
        }
    }
    
    // Prevent form submission if validation fails
    registerForm.addEventListener('submit', function(e) {
        const allValid = validationState.length && 
                        validationState.uppercase && 
                        validationState.number && 
                        validationState.match;
        
        if (!allValid) {
            e.preventDefault();
            
            // Show all requirements if hidden
            passwordRequirements.style.display = 'block';
            passwordRequirements.classList.add('show');
            
            if (passwordConfirmInput.value) {
                passwordMatchIndicator.style.display = 'block';
                passwordMatchIndicator.classList.add('show');
            }
            
            // Focus on password field
            passwordInput.focus();
        }
    });
    
    // Hide requirements when clicking outside (optional)
    document.addEventListener('click', function(e) {
        if (!passwordInput.contains(e.target) && 
            !passwordRequirements.contains(e.target) && 
            passwordInput.value === '') {
            passwordRequirements.style.display = 'none';
            passwordRequirements.classList.remove('show');
        }
        
        if (!passwordConfirmInput.contains(e.target) && 
            !passwordMatchIndicator.contains(e.target) && 
            passwordConfirmInput.value === '') {
            passwordMatchIndicator.style.display = 'none';
            passwordMatchIndicator.classList.remove('show');
        }
    });
});