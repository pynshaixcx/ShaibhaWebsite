<?php
$page_title = "Checkout";
$page_description = "Complete your order with secure cash on delivery.";
require_once '../includes/functions.php';
require_once '../includes/cart-functions.php';

$session_id = getSessionId();
$customer_id = getCurrentCustomerId();

// Validate cart
$cart_errors = validateCart($session_id, $customer_id);
if (!empty($cart_errors)) {
    setFlashMessage('error', implode('<br>', $cart_errors));
    redirect('index.php');
}

$cart_items = getCartItems($session_id, $customer_id);
$cart_totals = calculateCartTotal($session_id, $customer_id);

if (empty($cart_items)) {
    redirect('index.php');
}

$error = '';
$success = '';

// Get customer info if logged in
$customer = null;
if ($customer_id) {
    $customer = fetchOne("SELECT * FROM customers WHERE id = ?", [$customer_id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        // Get form data
        $customer_email = sanitizeInput($_POST['customer_email'] ?? '');
        $customer_phone = sanitizeInput($_POST['customer_phone'] ?? '');
        
        // Billing address
        $billing_first_name = sanitizeInput($_POST['billing_first_name'] ?? '');
        $billing_last_name = sanitizeInput($_POST['billing_last_name'] ?? '');
        $billing_address_line_1 = sanitizeInput($_POST['billing_address_line_1'] ?? '');
        $billing_address_line_2 = sanitizeInput($_POST['billing_address_line_2'] ?? '');
        $billing_city = sanitizeInput($_POST['billing_city'] ?? '');
        $billing_state = sanitizeInput($_POST['billing_state'] ?? '');
        $billing_postal_code = sanitizeInput($_POST['billing_postal_code'] ?? '');
        
        // Shipping address
        $same_as_billing = isset($_POST['same_as_billing']);
        $shipping_first_name = $same_as_billing ? $billing_first_name : sanitizeInput($_POST['shipping_first_name'] ?? '');
        $shipping_last_name = $same_as_billing ? $billing_last_name : sanitizeInput($_POST['shipping_last_name'] ?? '');
        $shipping_address_line_1 = $same_as_billing ? $billing_address_line_1 : sanitizeInput($_POST['shipping_address_line_1'] ?? '');
        $shipping_address_line_2 = $same_as_billing ? $billing_address_line_2 : sanitizeInput($_POST['shipping_address_line_2'] ?? '');
        $shipping_city = $same_as_billing ? $billing_city : sanitizeInput($_POST['shipping_city'] ?? '');
        $shipping_state = $same_as_billing ? $billing_state : sanitizeInput($_POST['shipping_state'] ?? '');
        $shipping_postal_code = $same_as_billing ? $billing_postal_code : sanitizeInput($_POST['shipping_postal_code'] ?? '');
        
        $notes = sanitizeInput($_POST['notes'] ?? '');
        
        // Validation
        $required_fields = [
            'customer_email', 'customer_phone', 'billing_first_name', 'billing_last_name',
            'billing_address_line_1', 'billing_city', 'billing_state', 'billing_postal_code'
        ];
        
        if (!$same_as_billing) {
            $required_fields = array_merge($required_fields, [
                'shipping_first_name', 'shipping_last_name', 'shipping_address_line_1',
                'shipping_city', 'shipping_state', 'shipping_postal_code'
            ]);
        }
        
        $missing_fields = [];
        foreach ($required_fields as $field) {
            if (empty($$field)) {
                $missing_fields[] = str_replace('_', ' ', $field);
            }
        }
        
        if (!empty($missing_fields)) {
            $error = 'Please fill in all required fields: ' . implode(', ', $missing_fields);
        } elseif (!validateEmail($customer_email)) {
            $error = 'Please enter a valid email address';
        } else {
            // Create order
            $order_number = generateOrderNumber();
            
            $order_sql = "INSERT INTO orders (
                order_number, customer_id, customer_email, customer_phone,
                billing_first_name, billing_last_name, billing_address_line_1, billing_address_line_2,
                billing_city, billing_state, billing_postal_code, billing_country,
                shipping_first_name, shipping_last_name, shipping_address_line_1, shipping_address_line_2,
                shipping_city, shipping_state, shipping_postal_code, shipping_country,
                subtotal, shipping_cost, total_amount, payment_method, payment_status, order_status, notes
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'India', ?, ?, ?, ?, ?, ?, ?, 'India', ?, ?, ?, 'cod', 'pending', 'pending', ?)";
            
            $order_params = [
                $order_number, $customer_id, $customer_email, $customer_phone,
                $billing_first_name, $billing_last_name, $billing_address_line_1, $billing_address_line_2,
                $billing_city, $billing_state, $billing_postal_code,
                $shipping_first_name, $shipping_last_name, $shipping_address_line_1, $shipping_address_line_2,
                $shipping_city, $shipping_state, $shipping_postal_code,
                $cart_totals['subtotal'], $cart_totals['shipping_cost'], $cart_totals['total'], $notes
            ];
            
            $order_result = executeQuery($order_sql, $order_params);
            
            if ($order_result) {
                $order_id = getLastInsertId();
                
                // Add order items
                foreach ($cart_items as $item) {
                    $item_price = $item['sale_price'] ?: $item['price'];
                    $item_total = $item_price * $item['quantity'];
                    
                    $item_sql = "INSERT INTO order_items (
                        order_id, product_id, product_name, product_sku, product_slug,
                        quantity, price, total, product_condition, product_size, product_color, product_brand
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    executeQuery($item_sql, [
                        $order_id, $item['product_id'], $item['name'], $item['sku'], $item['slug'],
                        $item['quantity'], $item_price, $item_total,
                        $item['condition_rating'], $item['size'], $item['color'], $item['brand']
                    ]);
                    
                    // Update product stock
                    executeQuery("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?", 
                               [$item['quantity'], $item['product_id']]);
                }
                
                // Clear cart
                clearCart($session_id, $customer_id);
                
                // Log activity
                if ($customer_id) {
                    logActivity('customer', $customer_id, 'order_placed', "Order {$order_number} placed");
                }
                
                // Redirect to order confirmation
                redirect("order-confirmation.php?order={$order_number}");
            } else {
                $error = 'Sorry, there was an error processing your order. Please try again.';
            }
        }
    }
}

include_once '../includes/header.php';
?>

<main class="main-content">
    <!-- Checkout Header -->
    <section class="checkout-header">
        <div class="container">
            <h1 class="page-title">Checkout</h1>
            <div class="checkout-steps">
                <div class="step active">
                    <span class="step-number">1</span>
                    <span class="step-label">Shipping Details</span>
                </div>
                <div class="step">
                    <span class="step-number">2</span>
                    <span class="step-label">Payment</span>
                </div>
                <div class="step">
                    <span class="step-number">3</span>
                    <span class="step-label">Confirmation</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Checkout Content -->
    <section class="checkout-content">
        <div class="container">
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <div class="checkout-layout">
                <!-- Checkout Form -->
                <div class="checkout-form">
                    <form method="POST" class="order-form">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <!-- Contact Information -->
                        <div class="form-section">
                            <h3>Contact Information</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="customer_email">Email Address *</label>
                                    <input type="email" id="customer_email" name="customer_email" required
                                           value="<?php echo htmlspecialchars($customer['email'] ?? $_POST['customer_email'] ?? ''); ?>"
                                           placeholder="your@email.com">
                                </div>
                                <div class="form-group">
                                    <label for="customer_phone">Phone Number *</label>
                                    <input type="tel" id="customer_phone" name="customer_phone" required
                                           value="<?php echo htmlspecialchars($customer['phone'] ?? $_POST['customer_phone'] ?? ''); ?>"
                                           placeholder="+91 9876543210">
                                </div>
                            </div>
                        </div>

                        <!-- Billing Address -->
                        <div class="form-section">
                            <h3>Billing Address</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="billing_first_name">First Name *</label>
                                    <input type="text" id="billing_first_name" name="billing_first_name" required
                                           value="<?php echo htmlspecialchars($customer['first_name'] ?? $_POST['billing_first_name'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="billing_last_name">Last Name *</label>
                                    <input type="text" id="billing_last_name" name="billing_last_name" required
                                           value="<?php echo htmlspecialchars($customer['last_name'] ?? $_POST['billing_last_name'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="billing_address_line_1">Address Line 1 *</label>
                                <input type="text" id="billing_address_line_1" name="billing_address_line_1" required
                                       value="<?php echo htmlspecialchars($_POST['billing_address_line_1'] ?? ''); ?>"
                                       placeholder="Street address, apartment, suite, etc.">
                            </div>
                            <div class="form-group">
                                <label for="billing_address_line_2">Address Line 2</label>
                                <input type="text" id="billing_address_line_2" name="billing_address_line_2"
                                       value="<?php echo htmlspecialchars($_POST['billing_address_line_2'] ?? ''); ?>"
                                       placeholder="Apartment, suite, unit, building, floor, etc.">
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="billing_city">City *</label>
                                    <input type="text" id="billing_city" name="billing_city" required
                                           value="<?php echo htmlspecialchars($_POST['billing_city'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="billing_state">State *</label>
                                    <input type="text" id="billing_state" name="billing_state" required
                                           value="<?php echo htmlspecialchars($_POST['billing_state'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="billing_postal_code">Postal Code *</label>
                                    <input type="text" id="billing_postal_code" name="billing_postal_code" required
                                           value="<?php echo htmlspecialchars($_POST['billing_postal_code'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="form-section">
                            <div class="section-header">
                                <h3>Shipping Address</h3>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="same_as_billing" id="same_as_billing" 
                                           <?php echo isset($_POST['same_as_billing']) ? 'checked' : ''; ?>
                                           onchange="toggleShippingAddress()">
                                    <span class="checkmark"></span>
                                    Same as billing address
                                </label>
                            </div>
                            
                            <div id="shipping-fields" style="<?php echo isset($_POST['same_as_billing']) ? 'display: none;' : ''; ?>">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="shipping_first_name">First Name *</label>
                                        <input type="text" id="shipping_first_name" name="shipping_first_name"
                                               value="<?php echo htmlspecialchars($_POST['shipping_first_name'] ?? ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="shipping_last_name">Last Name *</label>
                                        <input type="text" id="shipping_last_name" name="shipping_last_name"
                                               value="<?php echo htmlspecialchars($_POST['shipping_last_name'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="shipping_address_line_1">Address Line 1 *</label>
                                    <input type="text" id="shipping_address_line_1" name="shipping_address_line_1"
                                           value="<?php echo htmlspecialchars($_POST['shipping_address_line_1'] ?? ''); ?>"
                                           placeholder="Street address, apartment, suite, etc.">
                                </div>
                                <div class="form-group">
                                    <label for="shipping_address_line_2">Address Line 2</label>
                                    <input type="text" id="shipping_address_line_2" name="shipping_address_line_2"
                                           value="<?php echo htmlspecialchars($_POST['shipping_address_line_2'] ?? ''); ?>"
                                           placeholder="Apartment, suite, unit, building, floor, etc.">
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="shipping_city">City *</label>
                                        <input type="text" id="shipping_city" name="shipping_city"
                                               value="<?php echo htmlspecialchars($_POST['shipping_city'] ?? ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="shipping_state">State *</label>
                                        <input type="text" id="shipping_state" name="shipping_state"
                                               value="<?php echo htmlspecialchars($_POST['shipping_state'] ?? ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="shipping_postal_code">Postal Code *</label>
                                        <input type="text" id="shipping_postal_code" name="shipping_postal_code"
                                               value="<?php echo htmlspecialchars($_POST['shipping_postal_code'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div class="form-section">
                            <h3>Order Notes (Optional)</h3>
                            <div class="form-group">
                                <label for="notes">Special Instructions</label>
                                <textarea id="notes" name="notes" rows="3" 
                                          placeholder="Any special delivery instructions or notes..."><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="form-section">
                            <h3>Payment Method</h3>
                            <div class="payment-method">
                                <div class="payment-option selected">
                                    <div class="payment-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                            <polyline points="9,22 9,12 15,12 15,22"></polyline>
                                        </svg>
                                    </div>
                                    <div class="payment-details">
                                        <h4>Cash on Delivery (COD)</h4>
                                        <p>Pay when your order is delivered to your doorstep. No advance payment required.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary place-order-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            Place Order
                        </button>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <div class="summary-card">
                        <h3>Order Summary</h3>
                        
                        <div class="order-items">
                            <?php foreach ($cart_items as $item): ?>
                                <?php
                                $item_price = $item['sale_price'] ?: $item['price'];
                                $item_total = $item_price * $item['quantity'];
                                ?>
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="https://images.pexels.com/photos/1536619/pexels-photo-1536619.jpeg" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>">
                                    </div>
                                    <div class="item-details">
                                        <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                        <p><?php echo htmlspecialchars($item['brand']); ?></p>
                                        <?php if ($item['size']): ?>
                                            <p>Size: <?php echo htmlspecialchars($item['size']); ?></p>
                                        <?php endif; ?>
                                        <p>Qty: <?php echo $item['quantity']; ?></p>
                                    </div>
                                    <div class="item-price">
                                        <span><?php echo formatPrice($item_total); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="summary-totals">
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span><?php echo formatPrice($cart_totals['subtotal']); ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping</span>
                                <span>
                                    <?php if ($cart_totals['shipping_cost'] > 0): ?>
                                        <?php echo formatPrice($cart_totals['shipping_cost']); ?>
                                    <?php else: ?>
                                        <span class="free-shipping">FREE</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="summary-total">
                                <span>Total</span>
                                <span><?php echo formatPrice($cart_totals['total']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Checkout Page Styles */
.checkout-header {
    padding: var(--spacing-2xl) 0;
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-white) 100%);
    text-align: center;
}

.checkout-steps {
    display: flex;
    justify-content: center;
    gap: var(--spacing-xl);
    margin-top: var(--spacing-lg);
}

.step {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    color: var(--color-gray-500);
}

.step.active {
    color: var(--color-black);
}

.step-number {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--color-gray-300);
    color: var(--color-white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
}

.step.active .step-number {
    background: var(--color-black);
}

.checkout-content {
    padding: var(--spacing-3xl) 0;
}

.checkout-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: var(--spacing-3xl);
}

.checkout-form {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-2xl);
}

.form-section {
    margin-bottom: var(--spacing-2xl);
    padding-bottom: var(--spacing-xl);
    border-bottom: 1px solid var(--color-gray-200);
}

.form-section:last-of-type {
    border-bottom: none;
}

.form-section h3 {
    margin-bottom: var(--spacing-lg);
    color: var(--color-black);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-lg);
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--spacing-lg);
}

.form-group {
    margin-bottom: var(--spacing-lg);
}

.form-group label {
    display: block;
    margin-bottom: var(--spacing-xs);
    font-weight: 600;
    color: var(--color-black);
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: var(--spacing-md);
    border: 1px solid var(--color-gray-300);
    border-radius: var(--border-radius-md);
    font-size: 1rem;
    transition: border-color var(--transition-fast);
    background: var(--color-white);
    font-family: var(--font-primary);
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--color-black);
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 80px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: 0.9rem;
    color: var(--color-gray-700);
}

.checkbox-label input {
    margin-right: var(--spacing-sm);
}

.payment-method {
    border: 1px solid var(--color-gray-300);
    border-radius: var(--border-radius-md);
    overflow: hidden;
}

.payment-option {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding: var(--spacing-lg);
    background: var(--color-white);
    cursor: pointer;
    transition: background-color var(--transition-fast);
}

.payment-option.selected {
    background: var(--color-gray-100);
    border-left: 4px solid var(--color-black);
}

.payment-icon {
    width: 48px;
    height: 48px;
    background: var(--color-black);
    color: var(--color-white);
    border-radius: var(--border-radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
}

.payment-details h4 {
    margin-bottom: var(--spacing-xs);
    color: var(--color-black);
}

.payment-details p {
    color: var(--color-gray-600);
    font-size: 0.9rem;
}

.place-order-btn {
    width: 100%;
    justify-content: center;
    font-size: 1.1rem;
    padding: var(--spacing-lg) var(--spacing-xl);
}

.order-summary {
    position: sticky;
    top: 100px;
    height: fit-content;
}

.summary-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
}

.summary-card h3 {
    margin-bottom: var(--spacing-lg);
    color: var(--color-black);
}

.order-items {
    margin-bottom: var(--spacing-lg);
}

.order-item {
    display: flex;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
    padding-bottom: var(--spacing-md);
    border-bottom: 1px solid var(--color-gray-200);
}

.order-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.item-image img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: var(--border-radius-md);
}

.item-details {
    flex: 1;
}

.item-details h4 {
    font-size: 0.9rem;
    margin-bottom: var(--spacing-xs);
    color: var(--color-black);
}

.item-details p {
    font-size: 0.8rem;
    color: var(--color-gray-600);
    margin-bottom: 2px;
}

.item-price {
    font-weight: 700;
    color: var(--color-black);
}

.summary-totals {
    border-top: 1px solid var(--color-gray-200);
    padding-top: var(--spacing-lg);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: var(--spacing-sm);
    color: var(--color-gray-700);
}

.free-shipping {
    color: #10b981;
    font-weight: 600;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    font-weight: 700;
    font-size: 1.2rem;
    color: var(--color-black);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--color-gray-200);
    margin-top: var(--spacing-md);
}

.alert {
    padding: var(--spacing-md);
    border-radius: var(--border-radius-md);
    margin-bottom: var(--spacing-lg);
}

.alert-error {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

/* Responsive */
@media (max-width: 992px) {
    .checkout-layout {
        grid-template-columns: 1fr;
        gap: var(--spacing-xl);
    }
    
    .order-summary {
        position: static;
        order: -1;
    }
    
    .checkout-steps {
        flex-direction: column;
        align-items: center;
        gap: var(--spacing-md);
    }
}

@media (max-width: 768px) {
    .checkout-form,
    .summary-card {
        padding: var(--spacing-lg);
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--spacing-sm);
    }
}
</style>

<script>
function toggleShippingAddress() {
    const checkbox = document.getElementById('same_as_billing');
    const shippingFields = document.getElementById('shipping-fields');
    
    if (checkbox.checked) {
        shippingFields.style.display = 'none';
        // Clear shipping field requirements
        const shippingInputs = shippingFields.querySelectorAll('input[required]');
        shippingInputs.forEach(input => input.removeAttribute('required'));
    } else {
        shippingFields.style.display = 'block';
        // Add back shipping field requirements
        const requiredFields = ['shipping_first_name', 'shipping_last_name', 'shipping_address_line_1', 'shipping_city', 'shipping_state', 'shipping_postal_code'];
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field) field.setAttribute('required', 'required');
        });
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleShippingAddress();
});
</script>

<?php include_once '../includes/footer.php'; ?>