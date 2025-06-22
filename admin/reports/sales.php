<?php
$page_title = "Sales Reports";
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Get report parameters
$report_type = $_GET['type'] ?? 'daily';
$date_from = $_GET['date_from'] ?? date('Y-m-d', strtotime('-30 days'));
$date_to = $_GET['date_to'] ?? date('Y-m-d');

// Build query based on report type
$sql = "";
$params = [];

switch ($report_type) {
    case 'daily':
        $sql = "SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as order_count,
                    SUM(total_amount) as total_sales,
                    AVG(total_amount) as average_order_value
                FROM orders
                WHERE created_at BETWEEN ? AND ? AND payment_status = 'paid'
                GROUP BY DATE(created_at)
                ORDER BY date DESC";
        $params = [$date_from, $date_to . ' 23:59:59'];
        break;
        
    case 'weekly':
        $sql = "SELECT 
                    YEARWEEK(created_at, 1) as year_week,
                    MIN(DATE(created_at)) as week_start,
                    COUNT(*) as order_count,
                    SUM(total_amount) as total_sales,
                    AVG(total_amount) as average_order_value
                FROM orders
                WHERE created_at BETWEEN ? AND ? AND payment_status = 'paid'
                GROUP BY YEARWEEK(created_at, 1)
                ORDER BY year_week DESC";
        $params = [$date_from, $date_to . ' 23:59:59'];
        break;
        
    case 'monthly':
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    DATE_FORMAT(created_at, '%M %Y') as month_name,
                    COUNT(*) as order_count,
                    SUM(total_amount) as total_sales,
                    AVG(total_amount) as average_order_value
                FROM orders
                WHERE created_at BETWEEN ? AND ? AND payment_status = 'paid'
                GROUP BY DATE_FORMAT(created_at, '%Y-%m'), DATE_FORMAT(created_at, '%M %Y')
                ORDER BY month DESC";
        $params = [$date_from, $date_to . ' 23:59:59'];
        break;
        
    case 'product':
        $sql = "SELECT 
                    p.id,
                    p.name,
                    p.sku,
                    COUNT(oi.id) as order_count,
                    SUM(oi.quantity) as quantity_sold,
                    SUM(oi.total) as total_sales
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                JOIN orders o ON oi.order_id = o.id
                WHERE o.created_at BETWEEN ? AND ? AND o.payment_status = 'paid'
                GROUP BY p.id, p.name, p.sku
                ORDER BY quantity_sold DESC";
        $params = [$date_from, $date_to . ' 23:59:59'];
        break;
}

$report_data = fetchAll($sql, $params);

// Calculate totals
$total_orders = 0;
$total_sales = 0;
$total_items = 0;

foreach ($report_data as $row) {
    $total_orders += $row['order_count'];
    $total_sales += $row['total_sales'] ?? 0;
    $total_items += $row['quantity_sold'] ?? 0;
}

// Get average order value
$average_order_value = $total_orders > 0 ? $total_sales / $total_orders : 0;
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
                        <a href="../customers/index.php" class="nav-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>Customers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="sales.php" class="nav-link active">
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
                    <h1 class="page-title">Sales Reports</h1>
                    <div class="header-actions">
                        <a href="inventory.php" class="btn btn-outline">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                            Inventory Report
                        </a>
                    </div>
                </div>
            </header>

            <!-- Reports Content -->
            <div class="admin-content">
                <!-- Report Filters -->
                <div class="filters-section">
                    <form method="GET" class="filters-form">
                        <div class="filter-group">
                            <label for="type">Report Type</label>
                            <select id="type" name="type" onchange="this.form.submit()">
                                <option value="daily" <?php echo $report_type === 'daily' ? 'selected' : ''; ?>>Daily Sales</option>
                                <option value="weekly" <?php echo $report_type === 'weekly' ? 'selected' : ''; ?>>Weekly Sales</option>
                                <option value="monthly" <?php echo $report_type === 'monthly' ? 'selected' : ''; ?>>Monthly Sales</option>
                                <option value="product" <?php echo $report_type === 'product' ? 'selected' : ''; ?>>Product Sales</option>
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
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                            <a href="sales.php" class="btn btn-outline">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Report Summary -->
                <div class="report-summary">
                    <div class="summary-card">
                        <div class="summary-icon orders-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            </svg>
                        </div>
                        <div class="summary-content">
                            <h3>Total Orders</h3>
                            <p class="summary-value"><?php echo $total_orders; ?></p>
                        </div>
                    </div>
                    
                    <div class="summary-card">
                        <div class="summary-icon revenue-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <div class="summary-content">
                            <h3>Total Sales</h3>
                            <p class="summary-value"><?php echo formatPrice($total_sales); ?></p>
                        </div>
                    </div>
                    
                    <div class="summary-card">
                        <div class="summary-icon products-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                        </div>
                        <div class="summary-content">
                            <h3><?php echo $report_type === 'product' ? 'Items Sold' : 'Average Order'; ?></h3>
                            <p class="summary-value">
                                <?php echo $report_type === 'product' ? $total_items : formatPrice($average_order_value); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Report Data -->
                <div class="report-data">
                    <div class="report-header">
                        <h2>
                            <?php
                            switch ($report_type) {
                                case 'daily':
                                    echo 'Daily Sales Report';
                                    break;
                                case 'weekly':
                                    echo 'Weekly Sales Report';
                                    break;
                                case 'monthly':
                                    echo 'Monthly Sales Report';
                                    break;
                                case 'product':
                                    echo 'Product Sales Report';
                                    break;
                            }
                            ?>
                        </h2>
                        <div class="report-period">
                            <?php echo date('M j, Y', strtotime($date_from)); ?> - <?php echo date('M j, Y', strtotime($date_to)); ?>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <?php if ($report_type === 'daily'): ?>
                                        <th>Date</th>
                                        <th>Orders</th>
                                        <th>Sales</th>
                                        <th>Average Order</th>
                                    <?php elseif ($report_type === 'weekly'): ?>
                                        <th>Week</th>
                                        <th>Orders</th>
                                        <th>Sales</th>
                                        <th>Average Order</th>
                                    <?php elseif ($report_type === 'monthly'): ?>
                                        <th>Month</th>
                                        <th>Orders</th>
                                        <th>Sales</th>
                                        <th>Average Order</th>
                                    <?php elseif ($report_type === 'product'): ?>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th>Orders</th>
                                        <th>Quantity Sold</th>
                                        <th>Total Sales</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($report_data)): ?>
                                    <?php foreach ($report_data as $row): ?>
                                        <tr>
                                            <?php if ($report_type === 'daily'): ?>
                                                <td><?php echo date('M j, Y', strtotime($row['date'])); ?></td>
                                                <td><?php echo $row['order_count']; ?></td>
                                                <td><?php echo formatPrice($row['total_sales']); ?></td>
                                                <td><?php echo formatPrice($row['average_order_value']); ?></td>
                                            <?php elseif ($report_type === 'weekly'): ?>
                                                <td><?php echo date('M j', strtotime($row['week_start'])); ?> - <?php echo date('M j, Y', strtotime($row['week_start'] . ' +6 days')); ?></td>
                                                <td><?php echo $row['order_count']; ?></td>
                                                <td><?php echo formatPrice($row['total_sales']); ?></td>
                                                <td><?php echo formatPrice($row['average_order_value']); ?></td>
                                            <?php elseif ($report_type === 'monthly'): ?>
                                                <td><?php echo $row['month_name']; ?></td>
                                                <td><?php echo $row['order_count']; ?></td>
                                                <td><?php echo formatPrice($row['total_sales']); ?></td>
                                                <td><?php echo formatPrice($row['average_order_value']); ?></td>
                                            <?php elseif ($report_type === 'product'): ?>
                                                <td>
                                                    <a href="../products/edit.php?id=<?php echo $row['id']; ?>">
                                                        <?php echo htmlspecialchars($row['name']); ?>
                                                    </a>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['sku']); ?></td>
                                                <td><?php echo $row['order_count']; ?></td>
                                                <td><?php echo $row['quantity_sold']; ?></td>
                                                <td><?php echo formatPrice($row['total_sales']); ?></td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="<?php echo $report_type === 'product' ? 5 : 4; ?>" class="no-data">No data available for the selected period</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../../js/admin.js"></script>
</body>
</html>