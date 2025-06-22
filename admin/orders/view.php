<?php
$page_title = "View Order";
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Get order ID
$order_id = intval($_GET['id'] ?? 0);
if (!$order_id) {
    redirect('index.php');
}

// Get order data
$order = fetchOne("
    SELECT o.*, c.first_name, c.last_name, c.email, c.phone 
    FROM orders o 
    LEFT JOIN customers c ON o.customer_id = c.id 
    WHERE o.id = ?
", [$order_id]);

if (!$order) {
    redirect('index.php');
}

// Get order items
$order_items = fetchAll("
    SELECT oi.*, p.slug, p.status as product_status 
    FROM order_items oi 
    LEFT JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
", [$order_id]);

// Get order history
$order_history = fetchAll("
    SELECT * FROM activity_log 
    WHERE action IN ('order_status_updated', 'order_payment_updated') 
    AND description LIKE ? 
    ORDER BY created_at DESC
", ["%Order {$order['order_number']}%"]);

$error = '';
$success = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $new_status = sanitizeInput($_POST['order_status'] ?? '');
        $new_payment_status = sanitizeInput($_POST['payment_status'] ?? '');
        $admin_notes = sanitizeInput($_POST['admin_notes'] ?? '');
        
        // Update order status
        $sql = "UPDATE orders SET order_status = ?, payment_status = ?, admin_notes = ?, updated_at = CURRENT_TIMESTAMP";
        $params = [$new_status, $new_payment_status, $admin_notes];
        
        // Add shipped_at date if status is shipped
        if ($new_status === 'shipped' && $order['order_status'] !== 'shipped') {
            $sql .= ", shipped_at = CURRENT_TIMESTAMP";
        }
        
        // Add delivered_at date if status is delivered
        if ($new_status === 'delivered' && $order['order_status'] !== 'delivered') {
            $sql .= ", delivered_at = CURRENT_TIMESTAMP";
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $order_id;
        
        $result = executeQuery($sql, $params);
        
        if ($result) {
            // Log activity
            if ($new_status !== $order['order_status']) {
                logActivity('admin', getCurrentAdminId(), 'order_status_updated', "Order {$order['order_number']} status updated from {$order['order_status']} to {$new_status}");
            }
            
            if ($new_payment_status !== $order['payment_status']) {
                logActivity('admin', getCurrentAdminId(), 'order_payment_updated', "Order {$order['order_number']} payment status updated from {$order['payment_status']} to {$new_payment_status}");
            }
            
            $success = 'Order updated successfully!';
            
            // Refresh order data
            $order = fetchOne("
                SELECT o.*, c.first_name, c.last_name, c.email, c.phone 
                FROM orders o 
                LEFT JOIN customers c ON o.customer_id = c.id 
                WHERE o.id = ?
            ", [$order_id]);
            
            // Refresh order history
            $order_history = fetchAll("
                SELECT * FROM activity_log 
                WHERE action IN ('order_status_updated', 'order_payment_updated') 
                AND description LIKE ? 
                ORDER BY created_at DESC
            ", ["%Order {$order['order_number']}%"]);
        } else {
            $error = 'Failed to update order. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - ShaiBha Admin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="../../css/admin.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="../../images/favicon.svg">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h1 class="sidebar-logo">ShaiBha</h1>
                <p class="sidebar-subtitle">Admin Panel</p>
            </div>
            
            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="../index.php" class="nav-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9,22 9,12 15,12 15,22"></polyline>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../products/index.php" class="nav-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                            <span>Products</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php" class="nav-link active">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            </svg>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../customers/index.php" class="nav-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>Customers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../reports/sales.php" class="nav-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                                <line x1="3" y1="20" x2="21" y2="20"></line>
                            </svg>
                            <span>Reports</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="../logout.php" class="logout-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-content">
                    <h1 class="page-title">Order #<?php echo htmlspecialchars($order['order_number']); ?></h1>
                    <div class="header-actions">
                        <a href="index.php" class="btn btn-outline">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            Back to Orders
                        </a>
                        <a href="update-status.php?id=<?php echo $order_id; ?>" class="btn btn-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            Update Status
                        </a>
                    </div>
                </div>
            </header>

            <!-- View Order Content -->
            <div class="admin-content">
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <div class="order-view-layout">
                    <div class="order-main">
                        <!-- Order Details -->
                        <div class="order-details-card">
                            <div class="card-header">
                                <h2>Order Details</h2>
                                <div class="order-badges">
                                    <span class="status-badge status-<?php echo $order['order_status']; ?>">
                                        <?php echo ucfirst($order['order_status']); ?>
                                    </span>
                                    <span class="payment-badge payment-<?php echo $order['payment_status']; ?>">
                                        <?php echo ucfirst($order['payment_status']); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="order-info-grid">
                                <div class="info-item">
                                    <span class="info-label">Order Number</span>
                                    <span class="info-value"><?php echo htmlspecialchars($order['order_number']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Order Date</span>
                                    <span class="info-value"><?php echo date('M j, Y g:i A', strtotime($order['created_at'])); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Payment Method</span>
                                    <span class="info-value">Cash on Delivery</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Total Amount</span>
                                    <span class="info-value"><?php echo formatPrice($order['total_amount']); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Items -->
                        <div class="order-items-card">
                            <h2>Order Items</h2>
                            <div class="table-container">
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>SKU</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($order_items as $item): ?>
                                            <tr>
                                                <td>
                                                    <div class="product-info">
                                                        <div class="product-image">
                                                            <img src="https://images.pexels.com/photos/1536619/pexels-photo-1536619.jpeg" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="product-thumbnail">
                                                        </div>
                                                        <div class="product-details">
                                                            <h4>
                                                                <?php if ($item['product_slug']): ?>
                                                                    <a href="../../shop/product.php?slug=<?php echo $item['product_slug']; ?>" target="_blank">
                                                                        <?php echo htmlspecialchars($item['product_name']); ?>
                                                                    </a>
                                                                <?php else: ?>
                                                                    <?php echo htmlspecialchars($item['product_name']); ?>
                                                                <?php endif; ?>
                                                            </h4>
                                                            <?php if ($item['product_brand']): ?>
                                                                <p class="product-brand"><?php echo htmlspecialchars($item['product_brand']); ?></p>
                                                            <?php endif; ?>
                                                            <?php if ($item['product_size']): ?>
                                                                <p class="product-size">Size: <?php echo htmlspecialchars($item['product_size']); ?></p>
                                                            <?php endif; ?>
                                                            <?php if ($item['product_color']): ?>
                                                                <p class="product-color">Color: <?php echo htmlspecialchars($item['product_color']); ?></p>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($item['product_sku'] ?: 'N/A'); ?></td>
                                                <td><?php echo formatPrice($item['price']); ?></td>
                                                <td><?php echo $item['quantity']; ?></td>
                                                <td><?php echo formatPrice($item['total']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-right">Subtotal</td>
                                            <td><?php echo formatPrice($order['subtotal']); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-right">Shipping</td>
                                            <td>
                                                <?php if ($order['shipping_cost'] > 0): ?>
                                                    <?php echo formatPrice($order['shipping_cost']); ?>
                                                <?php else: ?>
                                                    <span class="free-shipping">FREE</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr class="total-row">
                                            <td colspan="4" class="text-right">Total</td>
                                            <td><?php echo formatPrice($order['total_amount']); ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="order-sidebar">
                        <!-- Customer Information -->
                        <div class="customer-card">
                            <h2>Customer Information</h2>
                            <div class="customer-info">
                                <h3>
                                    <?php if ($order['customer_id']): ?>
                                        <a href="../customers/view.php?id=<?php echo $order['customer_id']; ?>">
                                            <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($order['billing_first_name'] . ' ' . $order['billing_last_name']); ?>
                                        <span class="guest-label">Guest</span>
                                    <?php endif; ?>
                                </h3>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                            </div>
                        </div>
                        
                        <!-- Addresses -->
                        <div class="addresses-card">
                            <h2>Addresses</h2>
                            <div class="address-grid">
                                <div class="address-item">
                                    <h3>Billing Address</h3>
                                    <div class="address">
                                        <p><?php echo htmlspecialchars($order['billing_first_name'] . ' ' . $order['billing_last_name']); ?></p>
                                        <p><?php echo htmlspecialchars($order['billing_address_line_1']); ?></p>
                                        <?php if ($order['billing_address_line_2']): ?>
                                            <p><?php echo htmlspecialchars($order['billing_address_line_2']); ?></p>
                                        <?php endif; ?>
                                        <p><?php echo htmlspecialchars($order['billing_city'] . ', ' . $order['billing_state'] . ' ' . $order['billing_postal_code']); ?></p>
                                        <p><?php echo htmlspecialchars($order['billing_country']); ?></p>
                                    </div>
                                </div>
                                
                                <div class="address-item">
                                    <h3>Shipping Address</h3>
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
                            </div>
                        </div>
                        
                        <!-- Order Notes -->
                        <?php if ($order['notes']): ?>
                            <div class="notes-card">
                                <h2>Customer Notes</h2>
                                <div class="notes-content">
                                    <p><?php echo nl2br(htmlspecialchars($order['notes'])); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Admin Notes -->
                        <div class="admin-notes-card">
                            <h2>Admin Notes</h2>
                            <form method="POST" class="admin-notes-form">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="update_status" value="1">
                                
                                <div class="form-group">
                                    <textarea name="admin_notes" rows="4" placeholder="Add notes about this order..."><?php echo htmlspecialchars($order['admin_notes'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="order_status">Order Status</label>
                                        <select id="order_status" name="order_status">
                                            <option value="pending" <?php echo $order['order_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="confirmed" <?php echo $order['order_status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                            <option value="processing" <?php echo $order['order_status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                            <option value="shipped" <?php echo $order['order_status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                            <option value="delivered" <?php echo $order['order_status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                            <option value="cancelled" <?php echo $order['order_status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="payment_status">Payment Status</label>
                                        <select id="payment_status" name="payment_status">
                                            <option value="pending" <?php echo $order['payment_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="paid" <?php echo $order['payment_status'] === 'paid' ? 'selected' : ''; ?>>Paid</option>
                                            <option value="failed" <?php echo $order['payment_status'] === 'failed' ? 'selected' : ''; ?>>Failed</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Update Order</button>
                            </form>
                        </div>
                        
                        <!-- Order History -->
                        <div class="history-card">
                            <h2>Order History</h2>
                            <div class="history-timeline">
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12,6 12,12 16,14"></polyline>
                                        </svg>
                                    </div>
                                    <div class="timeline-content">
                                        <h4>Order Placed</h4>
                                        <p><?php echo date('M j, Y g:i A', strtotime($order['created_at'])); ?></p>
                                    </div>
                                </div>
                                
                                <?php foreach ($order_history as $history): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-icon">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M9 12l2 2 4-4"></path>
                                                <circle cx="12" cy="12" r="10"></circle>
                                            </svg>
                                        </div>
                                        <div class="timeline-content">
                                            <h4><?php echo str_replace('_', ' ', ucwords($history['action'], '_')); ?></h4>
                                            <p><?php echo htmlspecialchars($history['description']); ?></p>
                                            <span class="timeline-date"><?php echo date('M j, Y g:i A', strtotime($history['created_at'])); ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../../js/admin.js"></script>
</body>
</html>