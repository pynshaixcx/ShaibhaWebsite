<?php
$order_number = $_GET['order'] ?? '';
if (!$order_number) {
    redirect('orders.php');
}

require_once '../includes/functions.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$customer_id = getCurrentCustomerId();

// Get order details
$order = fetchOne("SELECT * FROM orders WHERE order_number = ? AND customer_id = ?", [$order_number, $customer_id]);
if (!$order) {
    redirect('orders.php');
}

// Get order items
$order_items = fetchAll("SELECT * FROM order_items WHERE order_id = ?", [$order['id']]);

$page_title = "Order Details - {$order_number}";
$page_description = "View detailed information about your order.";

include_once '../includes/header.php';
?>

<main class="main-content">
    <!-- Order Details Header -->
    <section class="order-details-header">
        <div class="container">
            <div class="order-details-header-content">
                <h1 class="page-title">Order Details</h1>
                <p class="page-subtitle">Order #<?php echo htmlspecialchars($order['order_number']); ?></p>
            </div>
        </div>
    </section>

    <!-- Order Details Content -->
    <section class="order-details-content">
        <div class="container">
            <div class="order-details-layout">
                <!-- Order Sidebar -->
                <aside class="order-sidebar">
                    <div class="profile-nav">
                        <h3>Account</h3>
                        <ul class="nav-list">
                            <li><a href="profile.php" class="nav-link">Profile Information</a></li>
                            <li><a href="orders.php" class="nav-link">Order History</a></li>
                            <li><a href="logout.php" class="nav-link">Logout</a></li>
                        </ul>
                    </div>
                </aside>

                <!-- Order Details Main -->
                <div class="order-details-main">
                    <!-- Order Status -->
                    <div class="order-status-card">
                        <div class="status-header">
                            <h2>Order Status</h2>
                            <span class="status-badge status-<?php echo $order['order_status']; ?>">
                                <?php echo ucfirst($order['order_status']); ?>
                            </span>
                        </div>
                        <div class="status-timeline">
                            <div class="timeline-item <?php echo in_array($order['order_status'], ['pending', 'confirmed', 'processing', 'shipped', 'delivered']) ? 'completed' : ''; ?>">
                                <div class="timeline-icon">1</div>
                                <div class="timeline-content">
                                    <h4>Order Placed</h4>
                                    <p><?php echo date('M j, Y g:i A', strtotime($order['created_at'])); ?></p>
                                </div>
                            </div>
                            <div class="timeline-item <?php echo in_array($order['order_status'], ['confirmed', 'processing', 'shipped', 'delivered']) ? 'completed' : ''; ?>">
                                <div class="timeline-icon">2</div>
                                <div class="timeline-content">
                                    <h4>Order Confirmed</h4>
                                    <p>We'll call you to confirm details</p>
                                </div>
                            </div>
                            <div class="timeline-item <?php echo in_array($order['order_status'], ['processing', 'shipped', 'delivered']) ? 'completed' : ''; ?>">
                                <div class="timeline-icon">3</div>
                                <div class="timeline-content">
                                    <h4>Processing</h4>
                                    <p>Your order is being prepared</p>
                                </div>
                            </div>
                            <div class="timeline-item <?php echo in_array($order['order_status'], ['shipped', 'delivered']) ? 'completed' : ''; ?>">
                                <div class="timeline-icon">4</div>
                                <div class="timeline-content">
                                    <h4>Shipped</h4>
                                    <p><?php echo $order['shipped_at'] ? date('M j, Y', strtotime($order['shipped_at'])) : 'Not yet shipped'; ?></p>
                                </div>
                            </div>
                            <div class="timeline-item <?php echo $order['order_status'] === 'delivered' ? 'completed' : ''; ?>">
                                <div class="timeline-icon">5</div>
                                <div class="timeline-content">
                                    <h4>Delivered</h4>
                                    <p><?php echo $order['delivered_at'] ? date('M j, Y', strtotime($order['delivered_at'])) : 'Not yet delivered'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="order-items-card">
                        <h3>Order Items</h3>
                        <div class="items-list">
                            <?php foreach ($order_items as $item): ?>
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="https://images.pexels.com/photos/1536619/pexels-photo-1536619.jpeg" 
                                             alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                    </div>
                                    <div class="item-details">
                                        <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                                        <?php if ($item['product_brand']): ?>
                                            <p class="item-brand"><?php echo htmlspecialchars($item['product_brand']); ?></p>
                                        <?php endif; ?>
                                        <?php if ($item['product_size']): ?>
                                            <p class="item-size">Size: <?php echo htmlspecialchars($item['product_size']); ?></p>
                                        <?php endif; ?>
                                        <?php if ($item['product_condition']): ?>
                                            <p class="item-condition">Condition: <?php echo ucfirst(str_replace('_', ' ', $item['product_condition'])); ?></p>
                                        <?php endif; ?>
                                        <p class="item-quantity">Quantity: <?php echo $item['quantity']; ?></p>
                                    </div>
                                    <div class="item-price">
                                        <span class="price"><?php echo formatPrice($item['price']); ?></span>
                                        <span class="total">Total: <?php echo formatPrice($item['total']); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary-card">
                        <h3>Order Summary</h3>
                        <div class="summary-details">
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

                    <!-- Shipping Information -->
                    <div class="shipping-info-card">
                        <h3>Shipping Information</h3>
                        <div class="address-details">
                            <div class="address-section">
                                <h4>Shipping Address</h4>
                                <div class="address">
                                    <p><?php echo htmlspecialchars($order['shipping_first_name'] . ' ' . $order['shipping_last_name']); ?></p>
                                    <p><?php echo htmlspecialchars($order['shipping_address_line_1']); ?></p>
                                    <?php if ($order['shipping_address_line_2']): ?>
                                        <p><?php echo htmlspecialchars($order['shipping_address_line_2']); ?></p>
                                    <?php endif; ?>
                                    <p><?php echo htmlspecialchars($order['shipping_city'] . ', ' . $order['shipping_state'] . ' ' . $order['shipping_postal_code']); ?></p>
                                    <p><?php echo htmlspecialchars($order['shipping_country']); ?></p>
                                </div>
                            </div>
                            
                            <div class="payment-section">
                                <h4>Payment Information</h4>
                                <div class="payment-details">
                                    <p><strong>Method:</strong> Cash on Delivery</p>
                                    <p><strong>Status:</strong> 
                                        <span class="payment-status payment-<?php echo $order['payment_status']; ?>">
                                            <?php echo ucfirst($order['payment_status']); ?>
                                        </span>
                                    </p>
                                    <p><strong>Amount:</strong> <?php echo formatPrice($order['total_amount']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Actions -->
                    <div class="order-actions-card">
                        <div class="actions-buttons">
                            <a href="orders.php" class="btn btn-secondary">Back to Orders</a>
                            <?php if ($order['order_status'] === 'pending'): ?>
                                <button class="btn btn-outline" onclick="cancelOrder('<?php echo $order['order_number']; ?>')">
                                    Cancel Order
                                </button>
                            <?php endif; ?>
                        </div>
                        
                        <div class="need-help">
                            <h4>Need Help?</h4>
                            <p>If you have any questions about your order, please <a href="../pages/contact.php">contact us</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Order Details Page Styles */
.order-details-header {
    padding: var(--spacing-2xl) 0;
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-white) 100%);
    text-align: center;
}

.order-details-content {
    padding: var(--spacing-3xl) 0;
}

.order-details-layout {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: var(--spacing-3xl);
}

.order-sidebar {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
    height: fit-content;
    position: sticky;
    top: 100px;
}

.profile-nav h3 {
    margin-bottom: var(--spacing-md);
    color: var(--color-black);
}

.profile-nav .nav-list {
    list-style: none;
}

.profile-nav .nav-list li {
    margin-bottom: var(--spacing-xs);
}

.profile-nav .nav-link {
    display: block;
    padding: var(--spacing-sm) var(--spacing-md);
    color: var(--color-gray-600);
    text-decoration: none;
    border-radius: var(--border-radius-md);
    transition: all var(--transition-fast);
}

.profile-nav .nav-link:hover,
.profile-nav .nav-link.active {
    background-color: var(--color-black);
    color: var(--color-white);
}

.order-status-card,
.order-items-card,
.order-summary-card,
.shipping-info-card,
.order-actions-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
    margin-bottom: var(--spacing-xl);
}

.status-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-lg);
}

.status-header h2 {
    color: var(--color-black);
}

.status-badge {
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--border-radius-sm);
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-confirmed {
    background: #dbeafe;
    color: #1e40af;
}

.status-processing {
    background: #e0e7ff;
    color: #5b21b6;
}

.status-shipped {
    background: #d1fae5;
    color: #065f46;
}

.status-delivered {
    background: #dcfce7;
    color: #166534;
}

.status-cancelled {
    background: #fee2e2;
    color: #dc2626;
}

.status-timeline {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
    margin-top: var(--spacing-xl);
}

.timeline-item {
    display: flex;
    gap: var(--spacing-md);
    position: relative;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 40px;
    left: 20px;
    width: 2px;
    height: calc(100% - 20px);
    background-color: var(--color-gray-300);
    z-index: 0;
}

.timeline-item.completed:not(:last-child)::after {
    background-color: var(--color-black);
}

.timeline-icon {
    width: 40px;
    height: 40px;
    background: var(--color-gray-300);
    color: var(--color-white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    z-index: 1;
    flex-shrink: 0;
}

.timeline-item.completed .timeline-icon {
    background: var(--color-black);
}

.timeline-content h4 {
    margin-bottom: var(--spacing-xs);
    color: var(--color-black);
}

.timeline-content p {
    color: var(--color-gray-600);
    font-size: 0.9rem;
}

.order-items-card h3,
.order-summary-card h3,
.shipping-info-card h3 {
    margin-bottom: var(--spacing-lg);
    color: var(--color-black);
}

.items-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.order-item {
    display: grid;
    grid-template-columns: 80px 1fr auto;
    gap: var(--spacing-md);
    padding-bottom: var(--spacing-md);
    border-bottom: 1px solid var(--color-gray-200);
}

.order-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.item-image img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: var(--border-radius-md);
}

.item-details h4 {
    margin-bottom: var(--spacing-xs);
    color: var(--color-black);
    font-size: 1rem;
}

.item-brand,
.item-size,
.item-condition,
.item-quantity {
    color: var(--color-gray-600);
    font-size: 0.9rem;
    margin-bottom: 2px;
}

.item-price {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: var(--spacing-xs);
}

.item-price .price {
    font-weight: 600;
    color: var(--color-black);
}

.item-price .total {
    color: var(--color-gray-600);
    font-size: 0.9rem;
}

.summary-details {
    background: var(--color-white);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-lg);
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

.address-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-xl);
}

.address-section h4,
.payment-section h4 {
    margin-bottom: var(--spacing-md);
    color: var(--color-black);
}

.address p,
.payment-details p {
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-700);
}

.payment-status {
    font-weight: 600;
}

.payment-pending {
    color: #f59e0b;
}

.payment-paid {
    color: #10b981;
}

.payment-failed {
    color: #ef4444;
}

.actions-buttons {
    display: flex;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
}

.need-help {
    background: var(--color-gray-100);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-md);
}

.need-help h4 {
    margin-bottom: var(--spacing-xs);
    color: var(--color-black);
}

.need-help p {
    color: var(--color-gray-600);
    font-size: 0.9rem;
    margin: 0;
}

.need-help a {
    color: var(--color-black);
    font-weight: 600;
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 992px) {
    .order-details-layout {
        grid-template-columns: 1fr;
        gap: var(--spacing-xl);
    }
    
    .order-sidebar {
        position: static;
        order: 2;
    }
    
    .address-details {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
    }
}

@media (max-width: 768px) {
    .order-status-card,
    .order-items-card,
    .order-summary-card,
    .shipping-info-card,
    .order-actions-card {
        padding: var(--spacing-lg);
    }
    
    .order-item {
        grid-template-columns: 60px 1fr;
    }
    
    .item-price {
        grid-column: 1 / 3;
        grid-row: 2;
        align-items: flex-start;
        margin-top: var(--spacing-sm);
    }
    
    .actions-buttons {
        flex-direction: column;
    }
}
</style>

<script>
function cancelOrder(orderNumber) {
    if (confirm('Are you sure you want to cancel this order?')) {
        // Implement order cancellation
        fetch('../api/cancel-order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                order_number: orderNumber
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to cancel order: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while canceling the order');
        });
    }
}
</script>

<?php include_once '../includes/footer.php'; ?>