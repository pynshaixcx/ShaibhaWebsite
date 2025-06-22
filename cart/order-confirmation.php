<?php
$order_number = $_GET['order'] ?? '';
if (!$order_number) {
    redirect('index.php');
}

require_once '../includes/functions.php';

$order = fetchOne("SELECT * FROM orders WHERE order_number = ?", [$order_number]);
if (!$order) {
    redirect('index.php');
}

$order_items = fetchAll("SELECT * FROM order_items WHERE order_id = ?", [$order['id']]);

$page_title = "Order Confirmation - {$order_number}";
$page_description = "Your order has been placed successfully. Thank you for shopping with ShaiBha.";

include_once '../includes/header.php';
?>

<main class="main-content">
    <!-- Confirmation Header -->
    <section class="confirmation-header">
        <div class="container">
            <div class="confirmation-content">
                <div class="success-icon">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4"></path>
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                </div>
                <h1 class="page-title">Order Confirmed!</h1>
                <p class="page-subtitle">Thank you for your order. We'll send you a confirmation email shortly.</p>
                <div class="order-number">
                    <span>Order Number: <strong><?php echo htmlspecialchars($order['order_number']); ?></strong></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Order Details -->
    <section class="order-details">
        <div class="container">
            <div class="order-layout">
                <!-- Order Summary -->
                <div class="order-summary">
                    <div class="summary-card">
                        <h3>Order Summary</h3>
                        
                        <div class="order-items">
                            <?php foreach ($order_items as $item): ?>
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="https://images.pexels.com/photos/1536619/pexels-photo-1536619.jpeg" 
                                             alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                    </div>
                                    <div class="item-details">
                                        <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                                        <?php if ($item['product_brand']): ?>
                                            <p><?php echo htmlspecialchars($item['product_brand']); ?></p>
                                        <?php endif; ?>
                                        <?php if ($item['product_size']): ?>
                                            <p>Size: <?php echo htmlspecialchars($item['product_size']); ?></p>
                                        <?php endif; ?>
                                        <?php if ($item['product_condition']): ?>
                                            <p>Condition: <?php echo ucfirst(str_replace('_', ' ', $item['product_condition'])); ?></p>
                                        <?php endif; ?>
                                        <p>Quantity: <?php echo $item['quantity']; ?></p>
                                    </div>
                                    <div class="item-price">
                                        <span><?php echo formatPrice($item['total']); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="summary-totals">
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span><?php echo formatPrice($order['subtotal']); ?></span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping</span>
                                <span>
                                    <?php if ($order['shipping_cost'] > 0): ?>
                                        <?php echo formatPrice($order['shipping_cost']); ?>
                                    <?php else: ?>
                                        <span class="free-shipping">FREE</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="summary-total">
                                <span>Total</span>
                                <span><?php echo formatPrice($order['total_amount']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Information -->
                <div class="order-info">
                    <!-- Shipping Information -->
                    <div class="info-card">
                        <h3>Shipping Information</h3>
                        <div class="address">
                            <p><strong><?php echo htmlspecialchars($order['shipping_first_name'] . ' ' . $order['shipping_last_name']); ?></strong></p>
                            <p><?php echo htmlspecialchars($order['shipping_address_line_1']); ?></p>
                            <?php if ($order['shipping_address_line_2']): ?>
                                <p><?php echo htmlspecialchars($order['shipping_address_line_2']); ?></p>
                            <?php endif; ?>
                            <p><?php echo htmlspecialchars($order['shipping_city'] . ', ' . $order['shipping_state'] . ' ' . $order['shipping_postal_code']); ?></p>
                            <p><?php echo htmlspecialchars($order['shipping_country']); ?></p>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="info-card">
                        <h3>Payment Information</h3>
                        <div class="payment-method">
                            <div class="payment-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9,22 9,12 15,12 15,22"></polyline>
                                </svg>
                            </div>
                            <div class="payment-details">
                                <h4>Cash on Delivery</h4>
                                <p>Pay <?php echo formatPrice($order['total_amount']); ?> when your order is delivered.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="info-card">
                        <h3>Contact Information</h3>
                        <div class="contact-details">
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                        </div>
                    </div>

                    <!-- Order Status -->
                    <div class="info-card">
                        <h3>Order Status</h3>
                        <div class="status-info">
                            <div class="status-badge pending">
                                <?php echo ucfirst($order['order_status']); ?>
                            </div>
                            <p>We'll process your order and contact you for confirmation within 24 hours.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h3>What happens next?</h3>
                <div class="steps-grid">
                    <div class="step-item">
                        <div class="step-icon">
                            <span>1</span>
                        </div>
                        <div class="step-content">
                            <h4>Order Confirmation</h4>
                            <p>We'll call you within 24 hours to confirm your order and delivery details.</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-icon">
                            <span>2</span>
                        </div>
                        <div class="step-content">
                            <h4>Processing</h4>
                            <p>Once confirmed, we'll carefully package your items for delivery.</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-icon">
                            <span>3</span>
                        </div>
                        <div class="step-content">
                            <h4>Delivery</h4>
                            <p>Your order will be delivered within 3-5 business days. Pay on delivery.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="confirmation-actions">
                <a href="../shop/" class="btn btn-secondary">Continue Shopping</a>
                <?php if (isLoggedIn()): ?>
                    <a href="../customer/orders.php" class="btn btn-primary">View Orders</a>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<style>
/* Order Confirmation Styles */
.confirmation-header {
    padding: var(--spacing-3xl) 0;
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-white) 100%);
    text-align: center;
}

.confirmation-content {
    max-width: 600px;
    margin: 0 auto;
}

.success-icon {
    color: #10b981;
    margin-bottom: var(--spacing-lg);
}

.order-number {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-md);
    margin-top: var(--spacing-lg);
    font-size: 1.1rem;
}

.order-details {
    padding: var(--spacing-3xl) 0;
}

.order-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-3xl);
    margin-bottom: var(--spacing-3xl);
}

.summary-card,
.info-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
    margin-bottom: var(--spacing-lg);
}

.summary-card h3,
.info-card h3 {
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
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: var(--border-radius-md);
}

.item-details {
    flex: 1;
}

.item-details h4 {
    font-size: 1rem;
    margin-bottom: var(--spacing-xs);
    color: var(--color-black);
}

.item-details p {
    font-size: 0.9rem;
    color: var(--color-gray-600);
    margin-bottom: 2px;
}

.item-price {
    font-weight: 700;
    color: var(--color-black);
    font-size: 1.1rem;
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
    font-size: 1.3rem;
    color: var(--color-black);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--color-gray-200);
    margin-top: var(--spacing-md);
}

.address p {
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-700);
}

.payment-method {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
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
}

.contact-details p {
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-700);
}

.status-info {
    text-align: center;
}

.status-badge {
    display: inline-block;
    padding: var(--spacing-sm) var(--spacing-lg);
    border-radius: var(--border-radius-md);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.9rem;
    margin-bottom: var(--spacing-md);
}

.status-badge.pending {
    background: #fef3c7;
    color: #92400e;
}

.next-steps {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-2xl);
    margin-bottom: var(--spacing-2xl);
}

.next-steps h3 {
    text-align: center;
    margin-bottom: var(--spacing-xl);
    color: var(--color-black);
}

.steps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-xl);
}

.step-item {
    text-align: center;
}

.step-icon {
    width: 60px;
    height: 60px;
    background: var(--color-black);
    color: var(--color-white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 auto var(--spacing-md);
}

.step-content h4 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.step-content p {
    color: var(--color-gray-600);
    line-height: 1.6;
}

.confirmation-actions {
    display: flex;
    gap: var(--spacing-md);
    justify-content: center;
    flex-wrap: wrap;
}

/* Responsive */
@media (max-width: 992px) {
    .order-layout {
        grid-template-columns: 1fr;
        gap: var(--spacing-xl);
    }
    
    .steps-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
    }
}

@media (max-width: 768px) {
    .summary-card,
    .info-card,
    .next-steps {
        padding: var(--spacing-lg);
    }
    
    .confirmation-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .confirmation-actions .btn {
        width: 100%;
        max-width: 280px;
    }
}
</style>

<?php include_once '../includes/footer.php'; ?>