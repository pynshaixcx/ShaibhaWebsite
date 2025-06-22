<?php
$page_title = "Update Order Status";
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
$order = fetchOne("SELECT * FROM orders WHERE id = ?", [$order_id]);
if (!$order) {
    redirect('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $new_status = sanitizeInput($_POST['order_status'] ?? '');
        $new_payment_status = sanitizeInput($_POST['payment_status'] ?? '');
        $tracking_number = sanitizeInput($_POST['tracking_number'] ?? '');
        $admin_notes = sanitizeInput($_POST['admin_notes'] ?? '');
        
        // Validation
        if (empty($new_status) || empty($new_payment_status)) {
            $error = 'Please select both order status and payment status';
        } else {
            // Update order status
            $sql = "UPDATE orders SET order_status = ?, payment_status = ?, tracking_number = ?, admin_notes = ?, updated_at = CURRENT_TIMESTAMP";
            $params = [$new_status, $new_payment_status, $tracking_number, $admin_notes];
            
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
                
                $success = 'Order status updated successfully!';
                
                // Redirect after a short delay
                header("refresh:2;url=view.php?id={$order_id}");
            } else {
                $error = 'Failed to update order status. Please try again.';
            }
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
                    <h1 class="page-title">Update Order Status</h1>
                    <div class="header-actions">
                        <a href="view.php?id=<?php echo $order_id; ?>" class="btn btn-outline">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            Back to Order
                        </a>
                    </div>
                </div>
            </header>

            <!-- Update Status Content -->
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
                
                <div class="update-status-layout">
                    <div class="order-summary-card">
                        <h2>Order Summary</h2>
                        <div class="order-summary">
                            <div class="summary-row">
                                <span class="summary-label">Order Number:</span>
                                <span class="summary-value"><?php echo htmlspecialchars($order['order_number']); ?></span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Order Date:</span>
                                <span class="summary-value"><?php echo date('M j, Y g:i A', strtotime($order['created_at'])); ?></span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Customer:</span>
                                <span class="summary-value">
                                    <?php echo htmlspecialchars($order['billing_first_name'] . ' ' . $order['billing_last_name']); ?>
                                </span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Email:</span>
                                <span class="summary-value"><?php echo htmlspecialchars($order['customer_email']); ?></span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Phone:</span>
                                <span class="summary-value"><?php echo htmlspecialchars($order['customer_phone']); ?></span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Total Amount:</span>
                                <span class="summary-value"><?php echo formatPrice($order['total_amount']); ?></span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Current Status:</span>
                                <span class="summary-value">
                                    <span class="status-badge status-<?php echo $order['order_status']; ?>">
                                        <?php echo ucfirst($order['order_status']); ?>
                                    </span>
                                </span>
                            </div>
                            <div class="summary-row">
                                <span class="summary-label">Payment Status:</span>
                                <span class="summary-value">
                                    <span class="payment-badge payment-<?php echo $order['payment_status']; ?>">
                                        <?php echo ucfirst($order['payment_status']); ?>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="update-status-card">
                        <h2>Update Status</h2>
                        <form method="POST" class="update-status-form">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="order_status">Order Status</label>
                                    <select id="order_status" name="order_status" required>
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
                                    <select id="payment_status" name="payment_status" required>
                                        <option value="pending" <?php echo $order['payment_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="paid" <?php echo $order['payment_status'] === 'paid' ? 'selected' : ''; ?>>Paid</option>
                                        <option value="failed" <?php echo $order['payment_status'] === 'failed' ? 'selected' : ''; ?>>Failed</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="tracking_number">Tracking Number</label>
                                <input type="text" id="tracking_number" name="tracking_number" value="<?php echo htmlspecialchars($order['tracking_number'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="admin_notes">Admin Notes</label>
                                <textarea id="admin_notes" name="admin_notes" rows="4"><?php echo htmlspecialchars($order['admin_notes'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Status</button>
                                <a href="view.php?id=<?php echo $order_id; ?>" class="btn btn-outline">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../../js/admin.js"></script>
</body>
</html>