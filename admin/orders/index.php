<?php
$page_title = "Order Management";
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Get orders with pagination
$page = max(1, intval($_GET['page'] ?? 1));
$orders_per_page = 20;
$offset = ($page - 1) * $orders_per_page;

// Get sorting parameters
$sort = $_GET['sort'] ?? 'created_at';
$order = $_GET['order'] ?? 'desc';

// Get filter parameters
$status = $_GET['status'] ?? '';
$payment_status = $_GET['payment_status'] ?? '';
$search = $_GET['search'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Build query
$sql = "SELECT o.*, c.first_name, c.last_name, c.email 
        FROM orders o 
        LEFT JOIN customers c ON o.customer_id = c.id 
        WHERE 1=1";
$params = [];

if ($status) {
    $sql .= " AND o.order_status = ?";
    $params[] = $status;
}

if ($payment_status) {
    $sql .= " AND o.payment_status = ?";
    $params[] = $payment_status;
}

if ($search) {
    $sql .= " AND (o.order_number LIKE ? OR o.customer_email LIKE ? OR o.customer_phone LIKE ? OR 
              CONCAT(o.billing_first_name, ' ', o.billing_last_name) LIKE ?)";
    $search_term = "%{$search}%";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
}

if ($date_from) {
    $sql .= " AND DATE(o.created_at) >= ?";
    $params[] = $date_from;
}

if ($date_to) {
    $sql .= " AND DATE(o.created_at) <= ?";
    $params[] = $date_to;
}

// Count total orders for pagination
$count_sql = str_replace("SELECT o.*, c.first_name, c.last_name, c.email", "SELECT COUNT(*) as total", $sql);
$total_orders = fetchOne($count_sql, $params)['total'];
$total_pages = ceil($total_orders / $orders_per_page);

// Add sorting and pagination
$valid_sort_columns = ['order_number', 'created_at', 'total_amount', 'order_status', 'payment_status'];
$sort = in_array($sort, $valid_sort_columns) ? $sort : 'created_at';
$order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

$sql .= " ORDER BY o.{$sort} {$order}";
$sql .= " LIMIT ? OFFSET ?";
$params[] = $orders_per_page;
$params[] = $offset;

$orders = fetchAll($sql, $params);
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
                    <h1 class="page-title">Order Management</h1>
                </div>
            </header>

            <!-- Orders Content -->
            <div class="admin-content">
                <!-- Filters -->
                <div class="filters-section">
                    <form method="GET" class="filters-form">
                        <div class="filter-group">
                            <label for="search">Search</label>
                            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Order #, email, phone...">
                        </div>
                        
                        <div class="filter-group">
                            <label for="status">Order Status</label>
                            <select id="status" name="status">
                                <option value="">All Statuses</option>
                                <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="confirmed" <?php echo $status === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="processing" <?php echo $status === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="shipped" <?php echo $status === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                <option value="delivered" <?php echo $status === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $status === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="payment_status">Payment Status</label>
                            <select id="payment_status" name="payment_status">
                                <option value="">All Statuses</option>
                                <option value="pending" <?php echo $payment_status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="paid" <?php echo $payment_status === 'paid' ? 'selected' : ''; ?>>Paid</option>
                                <option value="failed" <?php echo $payment_status === 'failed' ? 'selected' : ''; ?>>Failed</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="date_from">Date From</label>
                            <input type="date" id="date_from" name="date_from" value="<?php echo htmlspecialchars($date_from); ?>">
                        </div>
                        
                        <div class="filter-group">
                            <label for="date_to">Date To</label>
                            <input type="date" id="date_to" name="date_to" value="<?php echo htmlspecialchars($date_to); ?>">
                        </div>
                        
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="index.php" class="btn btn-outline">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Orders Table -->
                <div class="table-section">
                    <div class="table-header">
                        <h2>Orders (<?php echo $total_orders; ?>)</h2>
                    </div>
                    
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th data-sort="order_number">
                                        Order #
                                        <?php if ($sort === 'order_number'): ?>
                                            <span class="sort-indicator"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
                                        <?php endif; ?>
                                    </th>
                                    <th>Customer</th>
                                    <th data-sort="created_at">
                                        Date
                                        <?php if ($sort === 'created_at'): ?>
                                            <span class="sort-indicator"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
                                        <?php endif; ?>
                                    </th>
                                    <th data-sort="total_amount">
                                        Amount
                                        <?php if ($sort === 'total_amount'): ?>
                                            <span class="sort-indicator"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
                                        <?php endif; ?>
                                    </th>
                                    <th data-sort="payment_status">
                                        Payment
                                        <?php if ($sort === 'payment_status'): ?>
                                            <span class="sort-indicator"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
                                        <?php endif; ?>
                                    </th>
                                    <th data-sort="order_status">
                                        Status
                                        <?php if ($sort === 'order_status'): ?>
                                            <span class="sort-indicator"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
                                        <?php endif; ?>
                                    </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($orders)): ?>
                                    <?php foreach ($orders as $order_item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($order_item['order_number']); ?></td>
                                            <td>
                                                <?php if ($order_item['customer_id']): ?>
                                                    <a href="../customers/view.php?id=<?php echo $order_item['customer_id']; ?>">
                                                        <?php echo htmlspecialchars($order_item['first_name'] . ' ' . $order_item['last_name']); ?>
                                                    </a>
                                                <?php else: ?>
                                                    <?php echo htmlspecialchars($order_item['billing_first_name'] . ' ' . $order_item['billing_last_name']); ?>
                                                <?php endif; ?>
                                                <div class="customer-email"><?php echo htmlspecialchars($order_item['customer_email']); ?></div>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($order_item['created_at'])); ?></td>
                                            <td><?php echo formatPrice($order_item['total_amount']); ?></td>
                                            <td>
                                                <span class="payment-badge payment-<?php echo $order_item['payment_status']; ?>">
                                                    <?php echo ucfirst($order_item['payment_status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status-badge status-<?php echo $order_item['order_status']; ?>">
                                                    <?php echo ucfirst($order_item['order_status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="table-actions">
                                                    <a href="view.php?id=<?php echo $order_item['id']; ?>" class="action-btn view-btn" title="View Order">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                    </a>
                                                    <a href="update-status.php?id=<?php echo $order_item['id']; ?>" class="action-btn edit-btn" title="Update Status">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="no-data">No orders found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo $page - 1; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&status=<?php echo $status; ?>&payment_status=<?php echo $payment_status; ?>&search=<?php echo urlencode($search); ?>&date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>" class="pagination-link">
                                    Previous
                                </a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="?page=<?php echo $i; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&status=<?php echo $status; ?>&payment_status=<?php echo $payment_status; ?>&search=<?php echo urlencode($search); ?>&date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>" 
                                   class="pagination-link <?php echo $i === $page ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo $page + 1; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&status=<?php echo $status; ?>&payment_status=<?php echo $payment_status; ?>&search=<?php echo urlencode($search); ?>&date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>" class="pagination-link">
                                    Next
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="../../js/admin.js"></script>
</body>
</html>