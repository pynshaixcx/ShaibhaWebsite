<?php
$page_title = "Order History";
$page_description = "View your order history and track current orders.";
require_once '../includes/functions.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$customer_id = getCurrentCustomerId();

// Get orders with pagination
$page = max(1, intval($_GET['page'] ?? 1));
$orders_per_page = 10;
$offset = ($page - 1) * $orders_per_page;

// Get total orders count
$total_orders = fetchOne("SELECT COUNT(*) as count FROM orders WHERE customer_id = ?", [$customer_id])['count'];
$total_pages = ceil($total_orders / $orders_per_page);

// Get orders
$orders = fetchAll("
    SELECT * FROM orders 
    WHERE customer_id = ? 
    ORDER BY created_at DESC 
    LIMIT ? OFFSET ?
", [$customer_id, $orders_per_page, $offset]);

include_once '../includes/header.php';
?>

<main class="main-content">
    <!-- Orders Header -->
    <section class="orders-header">
        <div class="container">
            <div class="orders-header-content">
                <h1 class="page-title">Order History</h1>
                <p class="page-subtitle">Track and manage your orders</p>
            </div>
        </div>
    </section>

    <!-- Orders Content -->
    <section class="orders-content">
        <div class="container">
            <div class="orders-layout">
                <!-- Orders Sidebar -->
                <aside class="orders-sidebar">
                    <div class="profile-nav">
                        <h3>Account</h3>
                        <ul class="nav-list">
                            <li><a href="profile.php" class="nav-link">Profile Information</a></li>
                            <li><a href="orders.php" class="nav-link active">Order History</a></li>
                            <li><a href="logout.php" class="nav-link">Logout</a></li>
                        </ul>
                    </div>
                </aside>

                <!-- Orders List -->
                <div class="orders-main">
                    <?php if (!empty($orders)): ?>
                        <div class="orders-summary">
                            <h2>Your Orders</h2>
                            <p>You have <?php echo $total_orders; ?> order<?php echo $total_orders !== 1 ? 's' : ''; ?> in total</p>
                        </div>
                        
                        <div class="orders-list">
                            <?php foreach ($orders as $order): ?>
                                <div class="order-card">
                                    <div class="order-header">
                                        <div class="order-info">
                                            <h3>Order #<?php echo htmlspecialchars($order['order_number']); ?></h3>
                                            <p class="order-date">Placed on <?php echo date('M j, Y', strtotime($order['created_at'])); ?></p>
                                        </div>
                                        <div class="order-status">
                                            <span class="status-badge status-<?php echo $order['order_status']; ?>">
                                                <?php echo ucfirst($order['order_status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="order-details">
                                        <div class="order-summary">
                                            <div class="summary-item">
                                                <span>Total Amount:</span>
                                                <strong><?php echo formatPrice($order['total_amount']); ?></strong>
                                            </div>
                                            <div class="summary-item">
                                                <span>Payment Method:</span>
                                                <span>Cash on Delivery</span>
                                            </div>
                                            <div class="summary-item">
                                                <span>Payment Status:</span>
                                                <span class="payment-status payment-<?php echo $order['payment_status']; ?>">
                                                    <?php echo ucfirst($order['payment_status']); ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="order-actions">
                                            <a href="order-details.php?order=<?php echo $order['order_number']; ?>" class="btn btn-primary">
                                                View Details
                                            </a>
                                            <?php if ($order['order_status'] === 'pending'): ?>
                                                <button class="btn btn-outline" onclick="cancelOrder('<?php echo $order['order_number']; ?>')">
                                                    Cancel Order
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                            <div class="pagination">
                                <?php if ($page > 1): ?>
                                    <a href="?page=<?php echo $page - 1; ?>" class="pagination-link">Previous</a>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <a href="?page=<?php echo $i; ?>" 
                                       class="pagination-link <?php echo $i === $page ? 'active' : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <?php if ($page < $total_pages): ?>
                                    <a href="?page=<?php echo $page + 1; ?>" class="pagination-link">Next</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- No Orders -->
                        <div class="no-orders">
                            <div class="no-orders-content">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                </svg>
                                <h2>No orders yet</h2>
                                <p>You haven't placed any orders yet. Start shopping to see your orders here.</p>
                                <a href="../shop/" class="btn btn-primary">Start Shopping</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Orders Page Styles */
.orders-header {
    padding: var(--spacing-2xl) 0;
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-white) 100%);
    text-align: center;
}

.orders-content {
    padding: var(--spacing-3xl) 0;
}

.orders-layout {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: var(--spacing-3xl);
}

.orders-sidebar {
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

.orders-summary {
    margin-bottom: var(--spacing-xl);
    text-align: center;
}

.orders-summary h2 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.orders-summary p {
    color: var(--color-gray-600);
}

.orders-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-2xl);
}

.order-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
    transition: all var(--transition-medium);
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--glass-shadow);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-lg);
    border-bottom: 1px solid var(--color-gray-200);
}

.order-info h3 {
    margin-bottom: var(--spacing-xs);
    color: var(--color-black);
}

.order-date {
    color: var(--color-gray-600);
    font-size: 0.9rem;
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

.order-details {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
}

.order-summary {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.summary-item {
    display: flex;
    justify-content: space-between;
    min-width: 250px;
}

.summary-item span:first-child {
    color: var(--color-gray-600);
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

.order-actions {
    display: flex;
    gap: var(--spacing-sm);
}

.no-orders {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 400px;
}

.no-orders-content {
    text-align: center;
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-3xl);
    max-width: 400px;
}

.no-orders-content svg {
    color: var(--color-gray-400);
    margin-bottom: var(--spacing-lg);
}

.no-orders-content h2 {
    margin-bottom: var(--spacing-md);
    color: var(--color-black);
}

.no-orders-content p {
    color: var(--color-gray-600);
    margin-bottom: var(--spacing-xl);
}

.pagination {
    display: flex;
    justify-content: center;
    gap: var(--spacing-sm);
}

.pagination-link {
    padding: var(--spacing-sm) var(--spacing-md);
    border: 1px solid var(--color-gray-300);
    border-radius: var(--border-radius-md);
    color: var(--color-gray-700);
    text-decoration: none;
    transition: all var(--transition-fast);
}

.pagination-link:hover,
.pagination-link.active {
    background: var(--color-black);
    color: var(--color-white);
    border-color: var(--color-black);
}

/* Responsive */
@media (max-width: 992px) {
    .orders-layout {
        grid-template-columns: 1fr;
        gap: var(--spacing-xl);
    }
    
    .orders-sidebar {
        position: static;
        order: 2;
    }
    
    .order-details {
        flex-direction: column;
        gap: var(--spacing-lg);
        align-items: flex-start;
    }
    
    .order-actions {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .order-header {
        flex-direction: column;
        gap: var(--spacing-md);
    }
    
    .order-actions {
        flex-direction: column;
    }
    
    .order-actions .btn {
        width: 100%;
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