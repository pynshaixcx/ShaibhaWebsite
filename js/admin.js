// Admin panel functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sidebar toggle
    initializeSidebarToggle();
    
    // Initialize data tables
    initializeDataTables();
    
    // Initialize form validation
    initializeFormValidation();
    
    // Initialize delete confirmations
    initializeDeleteConfirmations();
});

// Initialize sidebar toggle
function initializeSidebarToggle() {
    const sidebarToggle = document.querySelector('.mobile-sidebar-toggle');
    const adminSidebar = document.querySelector('.admin-sidebar');
    
    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', function() {
            adminSidebar.classList.toggle('active');
        });
        
        // Close sidebar when clicking outside
        document.addEventListener('click', function(e) {
            if (adminSidebar.classList.contains('active') && 
                !adminSidebar.contains(e.target) && 
                !sidebarToggle.contains(e.target)) {
                adminSidebar.classList.remove('active');
            }
        });
    }
}

// Initialize data tables
function initializeDataTables() {
    const tables = document.querySelectorAll('.data-table');
    
    tables.forEach(table => {
        // Add sorting functionality
        const headers = table.querySelectorAll('th[data-sort]');
        
        headers.forEach(header => {
            header.addEventListener('click', function() {
                const sortKey = this.getAttribute('data-sort');
                const sortOrder = this.classList.contains('sort-asc') ? 'desc' : 'asc';
                
                // Update URL with sort parameters
                const url = new URL(window.location);
                url.searchParams.set('sort', sortKey);
                url.searchParams.set('order', sortOrder);
                window.location = url;
            });
            
            // Add sort indicator
            const sortKey = header.getAttribute('data-sort');
            const currentSort = new URLSearchParams(window.location.search).get('sort');
            const currentOrder = new URLSearchParams(window.location.search).get('order');
            
            if (sortKey === currentSort) {
                header.classList.add(currentOrder === 'asc' ? 'sort-asc' : 'sort-desc');
                
                const indicator = document.createElement('span');
                indicator.className = 'sort-indicator';
                indicator.textContent = currentOrder === 'asc' ? ' ↑' : ' ↓';
                header.appendChild(indicator);
            }
        });
    });
}

// Initialize form validation
function initializeFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let hasError = false;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('error');
                    hasError = true;
                    
                    // Add error message if not exists
                    let errorMessage = field.nextElementSibling;
                    if (!errorMessage || !errorMessage.classList.contains('error-message')) {
                        errorMessage = document.createElement('div');
                        errorMessage.className = 'error-message';
                        errorMessage.textContent = 'This field is required';
                        field.parentNode.insertBefore(errorMessage, field.nextSibling);
                    }
                } else {
                    field.classList.remove('error');
                    
                    // Remove error message if exists
                    const errorMessage = field.nextElementSibling;
                    if (errorMessage && errorMessage.classList.contains('error-message')) {
                        errorMessage.remove();
                    }
                }
            });
            
            if (hasError) {
                e.preventDefault();
                
                // Scroll to first error
                const firstError = form.querySelector('.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }
        });
        
        // Clear error on input
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('error');
                
                // Remove error message if exists
                const errorMessage = this.nextElementSibling;
                if (errorMessage && errorMessage.classList.contains('error-message')) {
                    errorMessage.remove();
                }
            });
        });
    });
}

// Initialize delete confirmations
function initializeDeleteConfirmations() {
    const deleteButtons = document.querySelectorAll('.delete-btn, [data-confirm]');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm') || 'Are you sure you want to delete this item?';
            
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
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
        top: 80px;
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

// Format price
function formatPrice(price) {
    return '₹' + parseFloat(price).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Image preview
function previewImage(input, previewElement) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById(previewElement);
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Toggle status
function toggleStatus(id, type, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    
    fetch(`/admin/api/toggle-status.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id: id,
            type: type,
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showNotification(data.message || 'Error updating status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating status', 'error');
    });
}