// booking.js - Clean Version (No CSS)

// Booking Form Functionality
let currentStep = 1;
const totalSteps = 3;

document.addEventListener('DOMContentLoaded', function() {
    console.log('Booking page loaded');
    initializeBookingForm();
    updateSummary();
    
    // Mark required fields
    markRequiredFields();
});

function initializeBookingForm() {
    // Set min dates for date inputs
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    const pickupDate = document.getElementById('pickupDate');
    const returnDate = document.getElementById('returnDate');
    
    if (pickupDate) {
        pickupDate.min = today.toISOString().split('T')[0];
        pickupDate.value = today.toISOString().split('T')[0];
        
        pickupDate.addEventListener('change', function() {
            const minReturn = new Date(this.value);
            minReturn.setDate(minReturn.getDate() + 1);
            if (returnDate) {
                returnDate.min = minReturn.toISOString().split('T')[0];
                
                if (new Date(returnDate.value) < minReturn) {
                    returnDate.value = minReturn.toISOString().split('T')[0];
                }
            }
            
            updateSummary();
        });
    }
    
    if (returnDate) {
        const minReturn = new Date(today);
        minReturn.setDate(minReturn.getDate() + 1);
        returnDate.min = minReturn.toISOString().split('T')[0];
        returnDate.value = minReturn.toISOString().split('T')[0];
        
        returnDate.addEventListener('change', updateSummary);
    }
    
    // Car type change event
    const carTypeSelect = document.getElementById('carType');
    if (carTypeSelect) {
        carTypeSelect.addEventListener('change', function() {
            updateCarModels(this.value);
            updateSummary();
        });
        updateCarModels(carTypeSelect.value);
    }
    
    // Extras change events
    const extras = document.querySelectorAll('input[name="extras"]');
    extras.forEach(extra => {
        extra.addEventListener('change', updateSummary);
    });
    
    // Payment method change
    const paymentMethods = document.querySelectorAll('input[name="payment"]');
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            updatePaymentInfo(this.value);
        });
    });
    
    // Form submission
    const form = document.getElementById('bookingForm');
    if (form) {
        form.addEventListener('submit', handleBookingSubmit);
    }
    
    // Add input validation on blur
    addInputValidation();
}

function markRequiredFields() {
    const requiredFields = document.querySelectorAll('input[required], select[required]');
    requiredFields.forEach(field => {
        const label = field.closest('.form-group')?.querySelector('label');
        if (label && !label.classList.contains('required')) {
            label.classList.add('required');
        }
    });
}

function updateCarModels(carType) {
    const carModelSelect = document.getElementById('carModel');
    if (!carModelSelect) return;
    
    // Clear existing options except first
    carModelSelect.innerHTML = '<option value="">Any Model</option>';
    
    // Sample car models based on type
    const carModels = {
        sedan: ['Toyota Camry', 'Honda Accord', 'Nissan Altima', 'Hyundai Elantra'],
        suv: ['Toyota RAV4', 'Honda CR-V', 'Ford Explorer', 'BMW X5'],
        luxury: ['Mercedes C-Class', 'BMW 5 Series', 'Audi A6', 'Tesla Model 3'],
        economy: ['Toyota Corolla', 'Honda Civic', 'Nissan Sentra', 'Hyundai Accent'],
        van: ['Toyota Hiace', 'Ford Transit', 'Mercedes Sprinter']
    };
    
    const models = carModels[carType] || [];
    models.forEach(model => {
        const option = document.createElement('option');
        option.value = model.toLowerCase().replace(/\s+/g, '-');
        option.textContent = model;
        carModelSelect.appendChild(option);
    });
}

function updatePaymentInfo(paymentMethod) {
    console.log(`Payment method selected: ${paymentMethod}`);
    // Could show/hide additional payment fields based on method
}

function addInputValidation() {
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        // Real-time validation for email
        if (input.type === 'email') {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    validateEmailField(this);
                }
            });
        }
        
        // Real-time validation for phone
        if (input.type === 'tel') {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    validatePhoneField(this);
                }
            });
        }
    });
}

function validateField(field) {
    if (field.hasAttribute('required') && !field.value.trim()) {
        showError(field, 'This field is required');
        return false;
    }
    
    if (field.type === 'email' && field.value.trim()) {
        if (!validateEmail(field.value)) {
            showError(field, 'Please enter a valid email address');
            return false;
        }
    }
    
    if (field.type === 'tel' && field.value.trim()) {
        if (!validatePhone(field.value)) {
            showError(field, 'Please enter a valid phone number');
            return false;
        }
    }
    
    clearError(field);
    return true;
}

function validateEmailField(field) {
    if (field.value.trim() && validateEmail(field.value)) {
        showSuccess(field, 'Email looks good!');
    }
}

function validatePhoneField(field) {
    if (field.value.trim() && validatePhone(field.value)) {
        showSuccess(field, 'Phone number is valid');
    }
}

function showSuccess(input, message) {
    const formGroup = input.closest('.form-group');
    formGroup.classList.remove('error');
    formGroup.classList.add('success');
    
    let successElement = formGroup.querySelector('.success-message');
    if (!successElement) {
        successElement = document.createElement('div');
        successElement.className = 'success-message';
        formGroup.appendChild(successElement);
    }
    successElement.textContent = message;
}

function nextStep() {
    if (validateStep(currentStep)) {
        document.getElementById(`step${currentStep}`).classList.remove('active');
        currentStep++;
        document.getElementById(`step${currentStep}`).classList.add('active');
        updateProgress();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

function prevStep() {
    document.getElementById(`step${currentStep}`).classList.remove('active');
    currentStep--;
    document.getElementById(`step${currentStep}`).classList.add('active');
    updateProgress();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function validateStep(step) {
    let isValid = true;
    const stepElement = document.getElementById(`step${step}`);
    
    // Get all required fields in current step
    const requiredFields = stepElement.querySelectorAll('input[required], select[required]');
    
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    // Additional step-specific validation
    if (step === 2) {
        const pickupDate = document.getElementById('pickupDate');
        const returnDate = document.getElementById('returnDate');
        
        if (pickupDate.value && returnDate.value) {
            const pickup = new Date(pickupDate.value);
            const returnD = new Date(returnDate.value);
            
            if (returnD <= pickup) {
                showError(returnDate, 'Return date must be after pickup date');
                isValid = false;
            }
        }
    }
    
    return isValid;
}

function showError(input, message) {
    const formGroup = input.closest('.form-group');
    formGroup.classList.add('error');
    formGroup.classList.remove('success');
    
    let errorElement = formGroup.querySelector('.error-message');
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        formGroup.appendChild(errorElement);
    }
    errorElement.textContent = message;
    
    // Remove success message if exists
    const successElement = formGroup.querySelector('.success-message');
    if (successElement) {
        successElement.remove();
    }
}

function clearError(input) {
    const formGroup = input.closest('.form-group');
    formGroup.classList.remove('error');
    
    const errorElement = formGroup.querySelector('.error-message');
    if (errorElement) {
        errorElement.remove();
    }
}

function updateProgress() {
    // You can add a progress bar here if needed
    console.log(`Step ${currentStep} of ${totalSteps}`);
    
    // Update step indicators in the UI if you add them
    const steps = document.querySelectorAll('.step-indicator');
    steps.forEach((step, index) => {
        if (index + 1 === currentStep) {
            step.classList.add('active');
        } else {
            step.classList.remove('active');
        }
    });
}

function updateSummary() {
    // Calculate days
    const pickupDate = document.getElementById('pickupDate');
    const returnDate = document.getElementById('returnDate');
    const carType = document.getElementById('carType');
    
    let days = 3; // Default
    if (pickupDate && pickupDate.value && returnDate && returnDate.value) {
        const pickup = new Date(pickupDate.value);
        const returnD = new Date(returnDate.value);
        const diffTime = Math.abs(returnD - pickup);
        days = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) || 1;
    }
    
    // Base price based on car type
    let basePrice = 45;
    if (carType && carType.value) {
        switch(carType.value) {
            case 'economy': basePrice = 35; break;
            case 'sedan': basePrice = 45; break;
            case 'suv': basePrice = 65; break;
            case 'luxury': basePrice = 150; break;
            case 'van': basePrice = 85; break;
            default: basePrice = 45;
        }
    }
    
    // Calculate extras
    let extrasTotal = 0;
    let extrasHTML = '';
    const extras = document.querySelectorAll('input[name="extras"]:checked');
    
    extras.forEach(extra => {
        let extraPrice = 0;
        let extraName = '';
        
        switch(extra.value) {
            case 'insurance': 
                extraPrice = 15 * days; 
                extraName = 'Full Insurance';
                break;
            case 'gps': 
                extraPrice = 5 * days; 
                extraName = 'GPS Navigation';
                break;
            case 'childseat': 
                extraPrice = 10 * days; 
                extraName = 'Child Safety Seat';
                break;
            case 'driver': 
                extraPrice = 20; 
                extraName = 'Additional Driver';
                break;
        }
        
        extrasTotal += extraPrice;
        extrasHTML += `
            <div class="summary-item">
                <span>${extraName}</span>
                <span class="price">+$${extraPrice.toFixed(2)}</span>
            </div>
        `;
    });
    
    // Update summary display
    const subtotal = basePrice * days;
    const total = subtotal + extrasTotal;
    
    // Update summary card elements
    const basePriceEl = document.querySelector('.summary-item:nth-child(1) .price');
    const daysEl = document.querySelector('.summary-item:nth-child(2) span:nth-child(2)');
    const subtotalEl = document.querySelector('.summary-item:nth-child(3) .price');
    const extrasContainer = document.querySelector('.summary-extras');
    const totalEl = document.querySelector('.total-price');
    
    if (basePriceEl) basePriceEl.textContent = `$${basePrice.toFixed(2)}`;
    if (daysEl) daysEl.textContent = `${days} ${days === 1 ? 'day' : 'days'}`;
    if (subtotalEl) subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
    if (totalEl) totalEl.textContent = `$${total.toFixed(2)}`;
    
    // Update extras section
    if (extrasContainer) {
        if (extras.length > 0) {
            extrasContainer.innerHTML = `
                <h3>Extras</h3>
                ${extrasHTML}
            `;
        } else {
            extrasContainer.innerHTML = `
                <h3>Extras</h3>
                <div class="summary-item">
                    <span>No extras selected</span>
                    <span class="price">$0.00</span>
                </div>
            `;
        }
    }
}

async function handleBookingSubmit(event) {
    event.preventDefault();
    
    // Validate step 3
    if (!validateStep(3)) {
        return;
    }
    
    const terms = document.getElementById('terms');
    if (!terms || !terms.checked) {
        showError(terms, 'You must agree to the terms and conditions');
        terms.closest('.terms-option').scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('.btn-submit');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
    
    // Collect form data
    const formData = {
        personal: {
            name: document.getElementById('fullName').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            country: document.getElementById('country').value,
            address: document.getElementById('address').value
        },
        rental: {
            pickupLocation: document.getElementById('pickupLocation').value,
            dropoffLocation: document.getElementById('dropoffLocation').value,
            pickupDate: document.getElementById('pickupDate').value,
            pickupTime: document.getElementById('pickupTime').value,
            returnDate: document.getElementById('returnDate').value,
            returnTime: document.getElementById('returnTime').value,
            carType: document.getElementById('carType').value,
            carModel: document.getElementById('carModel').value
        },
        extras: Array.from(document.querySelectorAll('input[name="extras"]:checked')).map(e => e.value),
        payment: document.querySelector('input[name="payment"]:checked').value,
        termsAccepted: true
    };
    
    console.log('Booking Data:', formData);
    
    // Simulate API call (2 seconds)
    await new Promise(resolve => setTimeout(resolve, 2000));
    
    // Show success message
    showBookingSuccess(formData);
}

function showBookingSuccess(formData) {
    // Create success modal
    const modal = document.createElement('div');
    modal.className = 'booking-success-modal';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Booking Confirmed!</h2>
            <p>Your booking has been successfully submitted.</p>
            
            <div class="booking-details">
                <h3>Booking Details</h3>
                <p><strong>Reference #:</strong> SR-${Date.now().toString().slice(-8)}</p>
                <p><strong>Name:</strong> ${formData.personal.name}</p>
                <p><strong>Pickup:</strong> ${formData.rental.pickupDate} at ${formData.rental.pickupTime}</p>
                <p><strong>Car Type:</strong> ${formData.rental.carType.charAt(0).toUpperCase() + formData.rental.carType.slice(1)}</p>
            </div>
            
            <p class="confirmation-note">A confirmation email has been sent to ${formData.personal.email}</p>
            
            <div class="modal-buttons">
                <button onclick="printBooking()" class="btn-secondary">
                    <i class="fas fa-print"></i> Print Confirmation
                </button>
                <button onclick="closeModalAndRedirect()" class="btn-primary">
                    <i class="fas fa-home"></i> Back to Home
                </button>
            </div>
        </div>
    `;
    
    // Add modal styles
    const modalStyles = `
        .booking-success-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            animation: fadeIn 0.3s ease;
        }
        
        .modal-content {
            background: white;
            padding: 3rem;
            border-radius: var(--radius-lg);
            max-width: 500px;
            width: 90%;
            text-align: center;
            animation: slideUp 0.5s ease;
        }
        
        .modal-icon {
            font-size: 4rem;
            color: var(--success-color);
            margin-bottom: 1rem;
        }
        
        .modal-content h2 {
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }
        
        .booking-details {
            background: rgba(52, 152, 219, 0.05);
            padding: 1.5rem;
            border-radius: var(--radius-sm);
            margin: 1.5rem 0;
            text-align: left;
        }
        
        .booking-details h3 {
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
        
        .confirmation-note {
            color: var(--gray-color);
            font-size: 0.9rem;
            margin: 1.5rem 0;
        }
        
        .modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }
        
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    `;
    
    const style = document.createElement('style');
    style.textContent = modalStyles;
    document.head.appendChild(style);
    
    document.body.appendChild(modal);
    
    // Prevent scrolling when modal is open
    document.body.style.overflow = 'hidden';
}

function printBooking() {
    window.print();
}

function closeModalAndRedirect() {
    const modal = document.querySelector('.booking-success-modal');
    if (modal) {
        modal.remove();
    }
    
    // Remove modal styles
    const modalStyle = document.querySelector('style[data-modal-styles]');
    if (modalStyle) {
        modalStyle.remove();
    }
    
    document.body.style.overflow = '';
    
    // Redirect after a delay
    setTimeout(() => {
        window.location.href = 'index.html';
    }, 500);
}

// Email validation helper
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Phone validation helper
function validatePhone(phone) {
    const re = /^[\+]?[1-9][\d]{0,15}$/;
    return re.test(phone.replace(/[\s\-\(\)]/g, ''));
}

// Add to global scope
window.nextStep = nextStep;
window.prevStep = prevStep;
window.printBooking = printBooking;
window.closeModalAndRedirect = closeModalAndRedirect;