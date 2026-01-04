// Contact Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Contact page loaded');
    
    // Initialize FAQ functionality
    initFAQ();
    
    // Initialize contact form
    initContactForm();
});

function initFAQ() {
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            // Toggle active class on question
            this.classList.toggle('active');
            
            // Get the answer element
            const answer = this.nextElementSibling;
            
            // Toggle active class on answer
            answer.classList.toggle('active');
            
            // Close other FAQ items
            if (this.classList.contains('active')) {
                faqQuestions.forEach(otherQuestion => {
                    if (otherQuestion !== this && otherQuestion.classList.contains('active')) {
                        otherQuestion.classList.remove('active');
                        otherQuestion.nextElementSibling.classList.remove('active');
                    }
                });
            }
        });
    });
}

function initContactForm() {
    const contactForm = document.getElementById('contactForm');
    
    if (!contactForm) return;
    
    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Validate form
        if (!validateContactForm()) {
            return;
        }
        
        // Get form data
        const formData = {
            name: document.getElementById('contactName').value,
            email: document.getElementById('contactEmail').value,
            subject: document.getElementById('contactSubject').value,
            message: document.getElementById('contactMessage').value
        };
        
        // Show loading state
        const submitBtn = contactForm.querySelector('.btn-submit');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        submitBtn.disabled = true;
        
        // Simulate API call (in real app, this would be fetch to backend)
        await new Promise(resolve => setTimeout(resolve, 1500));
        
        // Show success message
        showSuccessMessage(contactForm, formData);
        
        // Reset form
        contactForm.reset();
        
        // Restore button
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });
    
    // Add input validation
    addContactFormValidation();
}

function addContactFormValidation() {
    const inputs = contactForm.querySelectorAll('input, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateContactField(this);
        });
        
        // Real-time email validation
        if (input.type === 'email') {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    validateEmailField(this);
                }
            });
        }
    });
}

function validateContactField(field) {
    if (field.hasAttribute('required') && !field.value.trim()) {
        showContactError(field, 'This field is required');
        return false;
    }
    
    if (field.type === 'email' && field.value.trim()) {
        if (!validateEmail(field.value)) {
            showContactError(field, 'Please enter a valid email address');
            return false;
        }
    }
    
    clearContactError(field);
    return true;
}

function validateContactForm() {
    let isValid = true;
    const requiredFields = contactForm.querySelectorAll('input[required], textarea[required]');
    
    requiredFields.forEach(field => {
        if (!validateContactField(field)) {
            isValid = false;
        }
    });
    
    return isValid;
}

function showContactError(input, message) {
    const formGroup = input.closest('.form-group');
    formGroup.classList.add('error');
    
    let errorElement = formGroup.querySelector('.error-message');
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        formGroup.appendChild(errorElement);
    }
    errorElement.textContent = message;
}

function clearContactError(input) {
    const formGroup = input.closest('.form-group');
    formGroup.classList.remove('error');
    
    const errorElement = formGroup.querySelector('.error-message');
    if (errorElement) {
        errorElement.remove();
    }
}

function validateEmailField(field) {
    if (field.value.trim() && validateEmail(field.value)) {
        showContactSuccess(field, 'Email looks good!');
    }
}

function showContactSuccess(input, message) {
    const formGroup = input.closest('.form-group');
    formGroup.classList.remove('error');
    
    let successElement = formGroup.querySelector('.success-message');
    if (!successElement) {
        successElement = document.createElement('div');
        successElement.className = 'success-message';
        formGroup.appendChild(successElement);
    }
    successElement.textContent = message;
}

function showSuccessMessage(form, formData) {
    // Create success message element
    const successDiv = document.createElement('div');
    successDiv.className = 'form-success';
    successDiv.innerHTML = `
        <i class="fas fa-check-circle"></i>
        <h3>Message Sent Successfully!</h3>
        <p>Thank you, ${formData.name}. We'll get back to you within 24 hours.</p>
        <p>A confirmation email has been sent to ${formData.email}</p>
    `;
    
    // Insert success message
    form.parentNode.insertBefore(successDiv, form.nextSibling);
    
    // Remove success message after 5 seconds
    setTimeout(() => {
        successDiv.remove();
    }, 5000);
}

// Email validation helper (reuse from booking.js)
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}