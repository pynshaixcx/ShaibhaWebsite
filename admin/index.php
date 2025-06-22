<?php
$page_title = "Admin Dashboard";
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('login.php');
}

$admin_id = getCurrentAdminId();
$admin = fetchOne("SELECT * FROM admin_users WHERE id = ?", [$admin_id]);

// Get dashboard statistics
$total_orders = fetchOne("SELECT COUNT(*) as count FROM orders")['count'];
$pending_orders = fetchOne("SELECT COUNT(*) as count FROM orders WHERE order_status = 'pending'")['count'];
$total_customers = fetchOne("SELECT COUNT(*) as count FROM customers")['count'];
$total_products = fetchOne("SELECT COUNT(*) as count FROM products")['count'];
$low_stock_products = fetchOne("SELECT COUNT(*) as count FROM products WHERE stock_quantity <= 3 AND status = 'active'")['count'];
$total_revenue = fetchOne("SELECT SUM(total_amount) as total FROM orders WHERE payment_status = 'paid'")['total'] ?: 0;

// Get recent orders
$recent_orders = fetchAll("
    SELECT o.*, c.first_name, c.last_name, c.email 
    FROM orders o 
    LEFT JOIN customers c ON o.customer_id = c.id 
    ORDER BY o.created_at DESC 
    LIMIT 5
");

// Get top selling products
$top_products = fetchAll("
    SELECT p.id, p.name, p.slug, p.price, p.sale_price, COUNT(oi.id) as order_count, SUM(oi.quantity) as total_quantity
    FROM products p
    JOIN order_items oi ON p.id = oi.product_id
    JOIN orders o ON oi.order_id = o.id
    WHERE o.payment_status = 'paid'
    GROUP BY p.id
    ORDER BY total_quantity DESC
    LIMIT 5
");

// Get recent customers
$recent_customers = fetchAll("
    SELECT * FROM customers 
    ORDER BY created_at DESC 
    LIMIT 5
");
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
    <link rel="stylesheet" href="../css/admin.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
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
                        <a href="index.php" class="nav-link active">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9,22 9,12 15,12 15,22"></polyline>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="products/index.php" class="nav-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                            <span>Products</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="orders/index.php" class="nav-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            </svg>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="customers/index.php" class="nav-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>Customers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="reports/sales.php" class="nav-link">
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
                <div class="admin-info">
                    <div class="admin-avatar">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <div class="admin-details">
                        <p class="admin-name"><?php echo htmlspecialchars($admin['full_name']); ?></p>
                        <p class="admin-role"><?php echo ucfirst($admin['role']); ?></p>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn">
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
                    <h1 class="page-title">Dashboard</h1>
                    <div class="header-actions">
                        <div class="date-display">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <span><?php echo date('F j, Y'); ?></span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Stats Cards -->
                <section class="stats-section">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon orders-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                </svg>
                            </div>
                            <div class="stat-content">
                                <h3>Total Orders</h3>
                                <p class="stat-value"><?php echo $total_orders; ?></p>
                                <p class="stat-label">
                                    <span class="highlight"><?php echo $pending_orders; ?></span> pending
                                </p>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon revenue-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                            </div>
                            <div class="stat-content">
                                <h3>Total Revenue</h3>
                                <p class="stat-value"><?php echo formatPrice($total_revenue); ?></p>
                                <p class="stat-label">From paid orders</p>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon customers-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <div class="stat-content">
                                <h3>Customers</h3>
                                <p class="stat-value"><?php echo $total_customers; ?></p>
                                <p class="stat-label">Registered users</p>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon products-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                    <line x1="3" y1="6" x2="21" y2="6"></line>
                                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                                </svg>
                            </div>
                            <div class="stat-content">
                                <h3>Products</h3>
                                <p class="stat-value"><?php echo $total_products; ?></p>
                                <p class="stat-label">
                                    <span class="highlight"><?php echo $low_stock_products; ?></span> low stock
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Recent Orders -->
                <section class="recent-orders-section">
                    <div class="section-header">
                        <h2>Recent Orders</h2>
                        <a href="orders/index.php" class="view-all">View All</a>
                    </div>
                    
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recent_orders)): ?>
                                    <?php foreach ($recent_orders as $order): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                                            <td>
                                                <?php if ($order['customer_id']): ?>
                                                    <a href="customers/view.php?id=<?php echo $order['customer_id']; ?>">
                                                        <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?>
                                                    </a>
                                                <?php else: ?>
                                                    <?php echo htmlspecialchars($order['billing_first_name'] . ' ' . $order['billing_last_name']); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                            <td><?php echo formatPrice($order['total_amount']); ?></td>
                                            <td>
                                                <span class="status-badge status-<?php echo $order['order_status']; ?>">
                                                    <?php echo ucfirst($order['order_status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="table-actions">
                                                    <a href="orders/view.php?id=<?php echo $order['id']; ?>" class="action-btn view-btn" title="View Order">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="no-data">No orders found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Dashboard Bottom Grid -->
                <div class="dashboard-grid">
                    <!-- Top Products -->
                    <section class="top-products-section">
                        <div class="section-header">
                            <h2>Top Selling Products</h2>
                            <a href="products/index.php" class="view-all">View All</a>
                        </div>
                        
                        <div class="products-list">
                            <?php if (!empty($top_products)): ?>
                                <?php foreach ($top_products as $product): ?>
                                    <div class="product-item">
                                        <div class="product-image">
                                            <img src="https://images.pexels.com/photos/1536619/pexels-photo-1536619.jpeg" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                        </div>
                                        <div class="product-details">
                                            <h3>
                                                <a href="products/edit.php?id=<?php echo $product['id']; ?>">
                                                    <?php echo htmlspecialchars($product['name']); ?>
                                                </a>
                                            </h3>
                                            <p class="product-price">
                                                <?php echo formatPrice($product['sale_price'] ?: $product['price']); ?>
                                                <?php if ($product['sale_price']): ?>
                                                    <span class="original-price"><?php echo formatPrice($product['price']); ?></span>
                                                <?php endif; ?>
                                            </p>
                                            <p class="product-stats">
                                                <span>Sold: <?php echo $product['total_quantity']; ?> units</span>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="no-data-message">No product sales data available</div>
                            <?php endif; ?>
                        </div>
                    </section>

                    <!-- Recent Customers -->
                    <section class="recent-customers-section">
                        <div class="section-header">
                            <h2>Recent Customers</h2>
                            <a href="customers/index.php" class="view-all">View All</a>
                        </div>
                        
                        <div class="customers-list">
                            <?php if (!empty($recent_customers)): ?>
                                <?php foreach ($recent_customers as $customer): ?>
                                    <div class="customer-item">
                                        <div class="customer-avatar">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                        </div>
                                        <div class="customer-details">
                                            <h3>
                                                <a href="customers/view.php?id=<?php echo $customer['id']; ?>">
                                                    <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?>
                                                </a>
                                            </h3>
                                            <p class="customer-email"><?php echo htmlspecialchars($customer['email']); ?></p>
                                            <p class="customer-joined">
                                                Joined: <?php echo date('M j, Y', strtotime($customer['created_at'])); ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="no-data-message">No customers found</div>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>

    <script>
    // Admin Dashboard JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile sidebar toggle
        const sidebarToggle = document.querySelector('.mobile-sidebar-toggle');
        const adminSidebar = document.querySelector('.admin-sidebar');
        
        if (sidebarToggle && adminSidebar) {
            sidebarToggle.addEventListener('click', function() {
                adminSidebar.classList.toggle('active');
            });
        }
    });
    </script>
</body>
</html>