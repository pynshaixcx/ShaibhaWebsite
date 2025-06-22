<?php
$page_title = "Customer Management";
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Get customers with pagination
$page = max(1, intval($_GET['page'] ?? 1));
$customers_per_page = 20;
$offset = ($page - 1) * $customers_per_page;

// Get sorting parameters
$sort = $_GET['sort'] ?? 'created_at';
$order = $_GET['order'] ?? 'desc';

// Get filter parameters
$status = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$sql = "SELECT * FROM customers WHERE 1=1";
$params = [];

if ($status) {
    $sql .= " AND status = ?";
    $params[] = $status;
}

if ($search) {
    $sql .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ?)";
    $search_term = "%{$search}%";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
}

// Count total customers for pagination
$count_sql = str_replace("SELECT *", "SELECT COUNT(*) as total", $sql);
$total_customers = fetchOne($count_sql, $params)['total'];
$total_pages = ceil($total_customers / $customers_per_page);

// Add sorting and pagination
$valid_sort_columns = ['first_name', 'last_name', 'email', 'created_at', 'last_login', 'status'];
$sort = in_array($sort, $valid_sort_columns) ? $sort : 'created_at';
$order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

$sql .= " ORDER BY {$sort} {$order}";
$sql .= " LIMIT ? OFFSET ?";
$params[] = $customers_per_page;
$params[] = $offset;

$customers = fetchAll($sql, $params);
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
                    <h1 class="page-title">Customer Management</h1>
                </div>
            </header>

            <!-- Customers Content -->
            <div class="admin-content">
                <!-- Filters -->
                <div class="filters-section">
                    <form method="GET" class="filters-form">
                        <div class="filter-group">
                            <label for="search">Search</label>
                            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Name, email, phone...">
                        </div>
                        
                        <div class="filter-group">
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="">All Statuses</option>
                                <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                        
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="index.php" class="btn btn-outline">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Customers Table -->
                <div class="table-section">
                    <div class="table-header">
                        <h2>Customers (<?php echo $total_customers; ?>)</h2>
                    </div>
                    
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th data-sort="first_name">
                                        Name
                                        <?php if ($sort === 'first_name'): ?>
                                            <span class="sort-indicator"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
                                        <?php endif; ?>
                                    </th>
                                    <th data-sort="email">
                                        Email
                                        <?php if ($sort === 'email'): ?>
                                            <span class="sort-indicator"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
                                        <?php endif; ?>
                                    </th>
                                    <th>Phone</th>
                                    <th data-sort="created_at">
                                        Registered
                                        <?php if ($sort === 'created_at'): ?>
                                            <span class="sort-indicator"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
                                        <?php endif; ?>
                                    </th>
                                    <th data-sort="last_login">
                                        Last Login
                                        <?php if ($sort === 'last_login'): ?>
                                            <span class="sort-indicator"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
                                        <?php endif; ?>
                                    </th>
                                    <th data-sort="status">
                                        Status
                                        <?php if ($sort === 'status'): ?>
                                            <span class="sort-indicator"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
                                        <?php endif; ?>
                                    </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($customers)): ?>
                                    <?php foreach ($customers as $customer): ?>
                                        <tr>
                                            <td>
                                                <div class="customer-name">
                                                    <a href="view.php?id=<?php echo $customer['id']; ?>">
                                                        <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?>
                                                    </a>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                            <td><?php echo htmlspecialchars($customer['phone'] ?: 'N/A'); ?></td>
                                            <td><?php echo date('M j, Y', strtotime($customer['created_at'])); ?></td>
                                            <td>
                                                <?php echo $customer['last_login'] ? date('M j, Y', strtotime($customer['last_login'])) : 'Never'; ?>
                                            </td>
                                            <td>
                                                <span class="status-badge status-<?php echo $customer['status']; ?>">
                                                    <?php echo ucfirst($customer['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="table-actions">
                                                    <a href="view.php?id=<?php echo $customer['id']; ?>" class="action-btn view-btn" title="View Customer">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                    </a>
                                                    <a href="toggle-status.php?id=<?php echo $customer['id']; ?>&status=<?php echo $customer['status'] === 'active' ? 'inactive' : 'active'; ?>" class="action-btn <?php echo $customer['status'] === 'active' ? 'delete-btn' : 'edit-btn'; ?>" title="<?php echo $customer['status'] === 'active' ? 'Deactivate' : 'Activate'; ?> Customer">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <?php if ($customer['status'] === 'active'): ?>
                                                                <path d="M18 6L6 18"></path>
                                                                <path d="M6 6l12 12"></path>
                                                            <?php else: ?>
                                                                <path d="M9 12l2 2 4-4"></path>
                                                                <circle cx="12" cy="12" r="10"></circle>
                                                            <?php endif; ?>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="no-data">No customers found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo $page - 1; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&status=<?php echo $status; ?>&search=<?php echo urlencode($search); ?>" class="pagination-link">
                                    Previous
                                </a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="?page=<?php echo $i; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&status=<?php echo $status; ?>&search=<?php echo urlencode($search); ?>" 
                                   class="pagination-link <?php echo $i === $page ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo $page + 1; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&status=<?php echo $status; ?>&search=<?php echo urlencode($search); ?>" class="pagination-link">
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