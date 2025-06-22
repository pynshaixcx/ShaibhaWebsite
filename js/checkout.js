// Checkout functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize shipping address toggle
    initializeShippingAddressToggle();
    
    // Initialize form validation
    initializeFormValidation();
});

// Initialize shipping address toggle
function initializeShippingAddressToggle() {
    const sameAsBillingCheckbox = document.getElementById('same_as_billing');
    const shippingFields = document.getElementById('shipping-fields');
    
    if (!sameAsBillingCheckbox || !shippingFields) return;
    
    sameAsBillingCheckbox.addEventListener('change', function() {
        toggleShippingAddress();
    });
    
    // Initial toggle
    toggleShippingAddress();
}

// Toggle shipping address fields
function toggleShippingAddress() {
    const checkbox = document.getElementById('same_as_billing');
    const shippingFields = document.getElementById('shipping-fields');
    
    if (!checkbox || !shippingFields) return;
    
    if (checkbox.checked) {
        shippingFields.style.display = 'none';
        
        // Copy billing address to shipping address
        copyBillingToShipping();
        
        // Remove required attribute from shipping fields
        const shippingInputs = shippingFields.querySelectorAll('input[required]');
        shippingInputs.forEach(input => {
            input.removeAttribute('required');
        });
    } else {
        shippingFields.style.display = 'block';
        
        // Add required attribute to shipping fields
        const requiredFields = [
            'shipping_first_name', 'shipping_last_name', 'shipping_address_line_1',
            'shipping_city', 'shipping_state', 'shipping_postal_code'
        ];
        
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field) field.setAttribute('required', 'required');
        });
    }
}

// Copy billing address to shipping address
function copyBillingToShipping() {
    const billingFields = [
        ['billing_first_name', 'shipping_first_name'],
        ['billing_last_name', 'shipping_last_name'],
        ['billing_address_line_1', 'shipping_address_line_1'],
        ['billing_address_line_2', 'shipping_address_line_2'],
        ['billing_city', 'shipping_city'],
        ['billing_state', 'shipping_state'],
        ['billing_postal_code', 'shipping_postal_code']
    ];
    
    billingFields.forEach(([billingField, shippingField]) => {
        const billingInput = document.getElementById(billingField);
        const shippingInput = document.getElementById(shippingField);
        
        if (billingInput && shippingInput) {
            shippingInput.value = billingInput.value;
        }
    });
}

// Initialize form validation
function initializeFormValidation() {
    const checkoutForm = document.querySelector('.order-form');
    
    if (!checkoutForm) return;
    
    checkoutForm.addEventListener('submit', function(e) {
        const requiredFields = checkoutForm.querySelectorAll('input[required]');
        let hasError = false;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.style.borderColor = '#ef4444';
                hasError = true;
            } else {
                field.style.borderColor = '';
            }
        });
        
        // Validate email
        const emailField = document.getElementById('customer_email');
        if (emailField && emailField.value.trim() && !isValidEmail(emailField.value)) {
            emailField.style.borderColor = '#ef4444';
            hasError = true;
        }
        
        // Validate phone
        const phoneField = document.getElementById('customer_phone');
        if (phoneField && phoneField.value.trim() && !isValidPhone(phoneField.value)) {
            phoneField.style.borderColor = '#ef4444';
            hasError = true;
        }
        
        if (hasError) {
            e.preventDefault();
            showNotification('Please fill in all required fields correctly', 'error');
            
            // Scroll to first error
            const firstError = checkoutForm.querySelector('input[style*="border-color: #ef4444"]');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });
    
    // Add input event listeners to clear error styling
    const formInputs = checkoutForm.querySelectorAll('input, select, textarea');
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.style.borderColor = '';
        });
    });
    
    // Add event listeners to billing fields to update shipping fields
    if (document.getElementById('same_as_billing') && document.getElementById('same_as_billing').checked) {
        const billingFields = [
            'billing_first_name', 'billing_last_name', 'billing_address_line_1',
            'billing_address_line_2', 'billing_city', 'billing_state', 'billing_postal_code'
        ];
        
        billingFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field) {
                field.addEventListener('input', function() {
                    if (document.getElementById('same_as_billing').checked) {
                        const shippingField = document.getElementById(fieldName.replace('billing_', 'shipping_'));
                        if (shippingField) {
                            shippingField.value = this.value;
                        }
                    }
                });
            }
        });
    }
}

// Validate email
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Validate phone
function isValidPhone(phone) {
    // Basic validation for Indian phone numbers
    const re = /^(\+91[\-\s]?)?[0]?(91)?[6789]\d{9}$/;
    return re.test(phone);
}

// Show notification
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Style the notification
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        font-weight: 500;
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}