<?php
$page_title = "Inventory Report";
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Get report parameters
$category_id = $_GET['category'] ?? '';
$stock_status = $_GET['stock_status'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$sql = "SELECT p.*, c.name as category_name FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE 1=1";
$params = [];

if ($category_id) {
    $sql .= " AND p.category_id = ?";
    $params[] = $category_id;
}

if ($stock_status) {
    if ($stock_status === 'in_stock') {
        $sql .= " AND p.stock_quantity > 0";
    } elseif ($stock_status === 'out_of_stock') {
        $sql .= " AND p.stock_quantity = 0";
    } elseif ($stock_status === 'low_stock') {
        $sql .= " AND p.stock_quantity > 0 AND p.stock_quantity <= 3";
    }
}

if ($search) {
    $sql .= " AND (p.name LIKE ? OR p.sku LIKE ? OR p.brand LIKE ?)";
    $search_term = "%{$search}%";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
}

$sql .= " ORDER BY p.stock_quantity ASC, p.name ASC";

$products = fetchAll($sql, $params);

// Get categories for filter
$categories = fetchAll("SELECT id, name FROM categories WHERE status = 'active' ORDER BY name");

// Calculate inventory stats
$total_products = count($products);
$in_stock = 0;
$out_of_stock = 0;
$low_stock = 0;
$inventory_value = 0;

foreach ($products as $product) {
    if ($product['stock_quantity'] > 0) {
        $in_stock++;
        $price = $product['sale_price'] ?: $product['price'];
        $inventory_value += $price * $product['stock_quantity'];
        
        if ($product['stock_quantity'] <= 3) {
            $low_stock++;
        }
    } else {
        $out_of_stock++;
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
                    <h1 class="page-title">Inventory Report</h1>
                    <div class="header-actions">
                        <a href="sales.php" class="btn btn-outline">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                                <line x1="3" y1="20" x2="21" y2="20"></line>
                            </svg>
                            Sales Report
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
                            <label for="search">Search</label>
                            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Product name, SKU, brand...">
                        </div>
                        
                        <div class="filter-group">
                            <label for="category">Category</label>
                            <select id="category" name="category">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo $category_id == $category['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="stock_status">Stock Status</label>
                            <select id="stock_status" name="stock_status">
                                <option value="">All Stock Status</option>
                                <option value="in_stock" <?php echo $stock_status === 'in_stock' ? 'selected' : ''; ?>>In Stock</option>
                                <option value="out_of_stock" <?php echo $stock_status === 'out_of_stock' ? 'selected' : ''; ?>>Out of Stock</option>
                                <option value="low_stock" <?php echo $stock_status === 'low_stock' ? 'selected' : ''; ?>>Low Stock</option>
                            </select>
                        </div>
                        
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                            <a href="inventory.php" class="btn btn-outline">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Report Summary -->
                <div class="report-summary">
                    <div class="summary-card">
                        <div class="summary-icon products-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                        </div>
                        <div class="summary-content">
                            <h3>Total Products</h3>
                            <p class="summary-value"><?php echo $total_products; ?></p>
                        </div>
                    </div>
                    
                    <div class="summary-card">
                        <div class="summary-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                        </div>
                        <div class="summary-content">
                            <h3>In Stock</h3>
                            <p class="summary-value"><?php echo $in_stock; ?></p>
                        </div>
                    </div>
                    
                    <div class="summary-card">
                        <div class="summary-icon warning-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                        </div>
                        <div class="summary-content">
                            <h3>Low Stock</h3>
                            <p class="summary-value"><?php echo $low_stock; ?></p>
                        </div>
                    </div>
                    
                    <div class="summary-card">
                        <div class="summary-icon danger-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                            </svg>
                        </div>
                        <div class="summary-content">
                            <h3>Out of Stock</h3>
                            <p class="summary-value"><?php echo $out_of_stock; ?></p>
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
                            <h3>Inventory Value</h3>
                            <p class="summary-value"><?php echo formatPrice($inventory_value); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Report Data -->
                <div class="report-data">
                    <div class="report-header">
                        <h2>Inventory Report</h2>
                    </div>
                    
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Value</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($products)): ?>
                                    <?php foreach ($products as $product): ?>
                                        <?php
                                        $price = $product['sale_price'] ?: $product['price'];
                                        $inventory_value = $price * $product['stock_quantity'];
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="product-info">
                                                    <div class="product-image">
                                                        <img src="https://images.pexels.com/photos/1536619/pexels-photo-1536619.jpeg" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-thumbnail">
                                                    </div>
                                                    <div class="product-details">
                                                        <h4>
                                                            <a href="../products/edit.php?id=<?php echo $product['id']; ?>">
                                                                <?php echo htmlspecialchars($product['name']); ?>
                                                            </a>
                                                        </h4>
                                                        <?php if ($product['brand']): ?>
                                                            <p class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($product['sku'] ?: 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                                            <td>
                                                <?php if ($product['sale_price']): ?>
                                                    <span class="sale-price"><?php echo formatPrice($product['sale_price']); ?></span>
                                                    <span class="original-price"><?php echo formatPrice($product['price']); ?></span>
                                                <?php else: ?>
                                                    <?php echo formatPrice($product['price']); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="stock-badge <?php echo $product['stock_quantity'] <= 0 ? 'out-of-stock' : ($product['stock_quantity'] <= 3 ? 'low-stock' : ''); ?>">
                                                    <?php echo $product['stock_quantity']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status-badge status-<?php echo $product['status']; ?>">
                                                    <?php echo ucfirst($product['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo formatPrice($inventory_value); ?></td>
                                            <td>
                                                <div class="table-actions">
                                                    <a href="../products/edit.php?id=<?php echo $product['id']; ?>" class="action-btn edit-btn" title="Edit Product">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                        </svg>
                                                    </a>
                                                    <a href="../../shop/product.php?slug=<?php echo $product['slug']; ?>" target="_blank" class="action-btn view-btn" title="View Product">
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
                                        <td colspan="8" class="no-data">No products found</td>
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