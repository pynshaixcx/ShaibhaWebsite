// Cart functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize cart
    initializeCart();
    
    // Add event listeners for quantity controls
    initializeQuantityControls();
    
    // Add event listeners for remove buttons
    initializeRemoveButtons();
});

// Initialize cart
function initializeCart() {
    updateCartCount();
}

// Update cart count
function updateCartCount() {
    fetch('/cart/ajax/get-cart-count.php')
        .then(response => response.json())
        .then(data => {
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = data.count;
                cartCountElement.style.display = data.count > 0 ? 'flex' : 'none';
            }
        })
        .catch(error => {
            console.error('Error fetching cart count:', error);
        });
}

// Add to cart function
function addToCart(productId, quantity = 1) {
    fetch('/cart/ajax/add-to-cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount();
            showNotification('Product added to cart!', 'success');
        } else {
            showNotification(data.message || 'Error adding product to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding product to cart', 'error');
    });
}

// Update cart quantity
function updateCartQuantity(cartId, quantity) {
    fetch('/shop/ajax/update-cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            cart_id: cartId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount();
            updateCartTotals(data.cart_totals);
            
            if (quantity === 0) {
                // Remove item from DOM if quantity is 0
                const cartItem = document.querySelector(`.cart-item[data-cart-id="${cartId}"]`);
                if (cartItem) {
                    cartItem.remove();
                }
                
                // Check if cart is empty
                const cartItems = document.querySelectorAll('.cart-item');
                if (cartItems.length === 0) {
                    location.reload(); // Reload to show empty cart message
                }
            }
        } else {
            showNotification(data.message || 'Error updating cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating cart', 'error');
    });
}

// Remove from cart
function removeFromCart(cartId) {
    if (confirm('Are you sure you want to remove this item from your cart?')) {
        fetch('/shop/ajax/remove-from-cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                cart_id: cartId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartCount();
                updateCartTotals(data.cart_totals);
                
                // Remove item from DOM
                const cartItem = document.querySelector(`.cart-item[data-cart-id="${cartId}"]`);
                if (cartItem) {
                    cartItem.remove();
                }
                
                // Check if cart is empty
                const cartItems = document.querySelectorAll('.cart-item');
                if (cartItems.length === 0) {
                    location.reload(); // Reload to show empty cart message
                }
                
                showNotification('Item removed from cart', 'success');
            } else {
                showNotification(data.message || 'Error removing item from cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error removing item from cart', 'error');
        });
    }
}

// Initialize quantity controls
function initializeQuantityControls() {
    const quantityForms = document.querySelectorAll('.quantity-form');
    
    quantityForms.forEach(form => {
        const input = form.querySelector('input[name="quantity"]');
        const cartId = form.querySelector('input[name="cart_id"]').value;
        
        input.addEventListener('change', function() {
            const quantity = parseInt(this.value);
            if (quantity >= 1) {
                updateCartQuantity(cartId, quantity);
            } else {
                this.value = 1; // Reset to 1 if invalid value
            }
        });
    });
}

// Initialize remove buttons
function initializeRemoveButtons() {
    const removeForms = document.querySelectorAll('.remove-form');
    
    removeForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const cartId = this.querySelector('input[name="cart_id"]').value;
            removeFromCart(cartId);
        });
    });
}

// Update cart totals
function updateCartTotals(totals) {
    if (!totals) return;
    
    // Update subtotal
    const subtotalElement = document.querySelector('.summary-row:nth-child(1) span:last-child');
    if (subtotalElement) {
        subtotalElement.textContent = formatPrice(totals.subtotal);
    }
    
    // Update shipping
    const shippingElement = document.querySelector('.summary-row:nth-child(2) span:last-child');
    if (shippingElement) {
        if (totals.shipping_cost > 0) {
            shippingElement.textContent = formatPrice(totals.shipping_cost);
        } else {
            shippingElement.innerHTML = '<span class="free-shipping">FREE</span>';
        }
    }
    
    // Update total
    const totalElement = document.querySelector('.summary-total span:last-child');
    if (totalElement) {
        totalElement.textContent = formatPrice(totals.total);
    }
    
    // Update shipping notice if exists
    const shippingNotice = document.querySelector('.shipping-notice p');
    if (shippingNotice && totals.subtotal < 1999) {
        const remaining = 1999 - totals.subtotal;
        shippingNotice.textContent = `Add ${formatPrice(remaining)} more for free shipping!`;
    } else if (shippingNotice) {
        shippingNotice.parentElement.style.display = 'none';
    }
}

// Format price
function formatPrice(price) {
    return '₹' + parseFloat(price).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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

// Increase quantity
function increaseQuantity(button) {
    const input = button.parentElement.querySelector('input[name="quantity"]');
    const max = parseInt(input.getAttribute('max') || 10);
    const current = parseInt(input.value);
    if (current < max) {
        input.value = current + 1;
        if (input.form) {
            input.form.submit();
        }
    }
}

// Decrease quantity
function decreaseQuantity(button) {
    const input = button.parentElement.querySelector('input[name="quantity"]');
    const current = parseInt(input.value);
    if (current > 1) {
        input.value = current - 1;
        if (input.form) {
            input.form.submit();
        }
    }
}