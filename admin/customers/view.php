<?php
$page_title = "View Customer";
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Get customer ID
$customer_id = intval($_GET['id'] ?? 0);
if (!$customer_id) {
    redirect('index.php');
}

// Get customer data
$customer = fetchOne("SELECT * FROM customers WHERE id = ?", [$customer_id]);
if (!$customer) {
    redirect('index.php');
}

// Get customer orders
$orders = fetchAll("SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC", [$customer_id]);

// Get customer addresses
$addresses = fetchAll("SELECT * FROM customer_addresses WHERE customer_id = ? ORDER BY is_default DESC", [$customer_id]);

// Get customer activity
$activity = fetchAll("SELECT * FROM activity_log WHERE user_type = 'customer' AND user_id = ? ORDER BY created_at DESC LIMIT 20", [$customer_id]);

$error = '';
$success = '';

// Handle status toggle
if (isset($_GET['toggle_status']) && $_GET['toggle_status'] === 'yes') {
    // Validate CSRF token
    if (!verifyCSRFToken($_GET['token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $new_status = $customer['status'] === 'active' ? 'inactive' : 'active';
        
        $result = executeQuery("UPDATE customers SET status = ? WHERE id = ?", [$new_status, $customer_id]);
        
        if ($result) {
            // Log activity
            logActivity('admin', getCurrentAdminId(), 'customer_status_updated', "Customer {$customer['first_name']} {$customer['last_name']} status updated to {$new_status}");
            
            $success = 'Customer status updated successfully!';
            
            // Refresh customer data
            $customer = fetchOne("SELECT * FROM customers WHERE id = ?", [$customer_id]);
        } else {
            $error = 'Failed to update customer status. Please try again.';
        }
    }
}

// Generate CSRF token
$token = generateCSRFToken();
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
                        <a href="../orders/index.php" class="nav-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            </svg>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php" class="nav-link active">
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
                    <h1 class="page-title">Customer Profile</h1>
                    <div class="header-actions">
                        <a href="index.php" class="btn btn-outline">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            Back to Customers
                        </a>
                        <a href="view.php?id=<?php echo $customer_id; ?>&toggle_status=yes&token=<?php echo $token; ?>" class="btn <?php echo $customer['status'] === 'active' ? 'btn-danger' : 'btn-success'; ?>">
                            <?php if ($customer['status'] === 'active'): ?>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 6L6 18"></path>
                                    <path d="M6 6l12 12"></path>
                                </svg>
                                Deactivate Customer
                            <?php else: ?>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 12l2 2 4-4"></path>
                                    <circle cx="12" cy="12" r="10"></circle>
                                </svg>
                                Activate Customer
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
            </header>

            <!-- View Customer Content -->
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
                
                <div class="customer-view-layout">
                    <div class="customer-main">
                        <!-- Customer Profile -->
                        <div class="customer-profile-card">
                            <div class="profile-header">
                                <div class="customer-avatar">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </div>
                                <div class="customer-info">
                                    <h2><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></h2>
                                    <div class="customer-meta">
                                        <span class="customer-email"><?php echo htmlspecialchars($customer['email']); ?></span>
                                        <span class="status-badge status-<?php echo $customer['status']; ?>">
                                            <?php echo ucfirst($customer['status']); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="profile-details">
                                <div class="detail-row">
                                    <span class="detail-label">Phone</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($customer['phone'] ?: 'Not provided'); ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Date of Birth</span>
                                    <span class="detail-value"><?php echo $customer['date_of_birth'] ? date('M j, Y', strtotime($customer['date_of_birth'])) : 'Not provided'; ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Gender</span>
                                    <span class="detail-value"><?php echo $customer['gender'] ? ucfirst($customer['gender']) : 'Not provided'; ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Registered</span>
                                    <span class="detail-value"><?php echo date('M j, Y', strtotime($customer['created_at'])); ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Last Login</span>
                                    <span class="detail-value"><?php echo $customer['last_login'] ? date('M j, Y g:i A', strtotime($customer['last_login'])) : 'Never'; ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Email Verified</span>
                                    <span class="detail-value"><?php echo $customer['email_verified'] ? 'Yes' : 'No'; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Customer Orders -->
                        <div class="customer-orders-card">
                            <h2>Order History</h2>
                            <?php if (!empty($orders)): ?>
                                <div class="table-container">
                                    <table class="data-table">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Payment</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($orders as $order): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                                                    <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                                    <td><?php echo formatPrice($order['total_amount']); ?></td>
                                                    <td>
                                                        <span class="payment-badge payment-<?php echo $order['payment_status']; ?>">
                                                            <?php echo ucfirst($order['payment_status']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="status-badge status-<?php echo $order['order_status']; ?>">
                                                            <?php echo ucfirst($order['order_status']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="table-actions">
                                                            <a href="../orders/view.php?id=<?php echo $order['id']; ?>" class="action-btn view-btn" title="View Order">
                                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                    <circle cx="12" cy="12" r="3"></circle>
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="no-data-message">No orders found for this customer</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="customer-sidebar">
                        <!-- Customer Stats -->
                        <div class="customer-stats-card">
                            <h2>Customer Stats</h2>
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <div class="stat-icon orders-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                        </svg>
                                    </div>
                                    <div class="stat-content">
                                        <h3>Total Orders</h3>
                                        <p class="stat-value"><?php echo count($orders); ?></p>
                                    </div>
                                </div>
                                
                                <div class="stat-item">
                                    <div class="stat-icon revenue-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="12" y1="1" x2="12" y2="23"></line>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                        </svg>
                                    </div>
                                    <div class="stat-content">
                                        <h3>Total Spent</h3>
                                        <p class="stat-value">
                                            <?php
                                            $total_spent = 0;
                                            foreach ($orders as $order) {
                                                if ($order['payment_status'] === 'paid') {
                                                    $total_spent += $order['total_amount'];
                                                }
                                            }
                                            echo formatPrice($total_spent);
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Customer Addresses -->
                        <div class="customer-addresses-card">
                            <h2>Addresses</h2>
                            <?php if (!empty($addresses)): ?>
                                <div class="addresses-list">
                                    <?php foreach ($addresses as $address): ?>
                                        <div class="address-item">
                                            <div class="address-header">
                                                <h3><?php echo ucfirst($address['type']); ?> Address</h3>
                                                <?php if ($address['is_default']): ?>
                                                    <span class="default-badge">Default</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="address-content">
                                                <p><?php echo htmlspecialchars($address['first_name'] . ' ' . $address['last_name']); ?></p>
                                                <?php if ($address['company']): ?>
                                                    <p><?php echo htmlspecialchars($address['company']); ?></p>
                                                <?php endif; ?>
                                                <p><?php echo htmlspecialchars($address['address_line_1']); ?></p>
                                                <?php if ($address['address_line_2']): ?>
                                                    <p><?php echo htmlspecialchars($address['address_line_2']); ?></p>
                                                <?php endif; ?>
                                                <p><?php echo htmlspecialchars($address['city'] . ', ' . $address['state'] . ' ' . $address['postal_code']); ?></p>
                                                <p><?php echo htmlspecialchars($address['country']); ?></p>
                                                <?php if ($address['phone']): ?>
                                                    <p>Phone: <?php echo htmlspecialchars($address['phone']); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="no-data-message">No addresses found for this customer</div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Customer Activity -->
                        <div class="customer-activity-card">
                            <h2>Recent Activity</h2>
                            <?php if (!empty($activity)): ?>
                                <div class="activity-timeline">
                                    <?php foreach ($activity as $log): ?>
                                        <div class="activity-item">
                                            <div class="activity-icon">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <?php if (strpos($log['action'], 'login') !== false): ?>
                                                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                                        <polyline points="10,17 15,12 10,7"></polyline>
                                                        <line x1="15" y1="12" x2="3" y2="12"></line>
                                                    <?php elseif (strpos($log['action'], 'order') !== false): ?>
                                                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                                    <?php else: ?>
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                                    <?php endif; ?>
                                                </svg>
                                            </div>
                                            <div class="activity-content">
                                                <h4><?php echo str_replace('_', ' ', ucwords($log['action'], '_')); ?></h4>
                                                <p><?php echo htmlspecialchars($log['description']); ?></p>
                                                <span class="activity-date"><?php echo date('M j, Y g:i A', strtotime($log['created_at'])); ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="no-data-message">No activity found for this customer</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../../js/admin.js"></script>
</body>
</html>