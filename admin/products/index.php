<?php
$page_title = "Product Management";
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Get products with pagination
$page = max(1, intval($_GET['page'] ?? 1));
$products_per_page = 20;
$offset = ($page - 1) * $products_per_page;

// Get sorting parameters
$sort = $_GET['sort'] ?? 'created_at';
$order = $_GET['order'] ?? 'desc';

// Get filter parameters
$category_id = $_GET['category'] ?? '';
$status = $_GET['status'] ?? '';
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

if ($status) {
    $sql .= " AND p.status = ?";
    $params[] = $status;
}

if ($search) {
    $sql .= " AND (p.name LIKE ? OR p.sku LIKE ? OR p.brand LIKE ?)";
    $search_term = "%{$search}%";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
}

// Count total products for pagination
$count_sql = str_replace("SELECT p.*, c.name as category_name", "SELECT COUNT(*) as total", $sql);
$total_products = fetchOne($count_sql, $params)['total'];
$total_pages = ceil($total_products / $products_per_page);

// Add sorting and pagination
$valid_sort_columns = ['name', 'price', 'stock_quantity', 'status', 'created_at'];
$sort = in_array($sort, $valid_sort_columns) ? $sort : 'created_at';
$order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

$sql .= " ORDER BY p.{$sort} {$order}";
$sql .= " LIMIT ? OFFSET ?";
$params[] = $products_per_page;
$params[] = $offset;

$products = fetchAll($sql, $params);

// Get categories for filter
$categories = fetchAll("SELECT id, name FROM categories WHERE status = 'active' ORDER BY name");
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
                        <a href="index.php" class="nav-link active">
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
                    <h1 class="page-title">Product Management</h1>
                    <div class="header-actions">
                        <a href="add.php" class="btn btn-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Add New Product
                        </a>
                    </div>
                </div>
            </header>

            <!-- Products Content -->
            <div class="admin-content">
                <!-- Filters -->
                <div class="filters-section">
                    <form method="GET" class="filters-form">
                        <div class="filter-group">
                            <label for="search">Search</label>
                            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search products...">
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
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="">All Statuses</option>
                                <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                <option value="sold" <?php echo $status === 'sold' ? 'selected' : ''; ?>>Sold</option>
                            </select>
                        </div>
                        
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="index.php" class="btn btn-outline">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- Products Table -->
                <div class="table-section">
                    <div class="table-header">
                        <h2>Products (<?php echo $total_products; ?>)</h2>
                    </div>
                    
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th data-sort="name">
                                        Product Name
                                        <?php if ($sort === 'name'): ?>
                                            <span class="sort-indicator"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
                                        <?php endif; ?>
                                    </th>
                                    <th>Category</th>
                                    <th data-sort="price">
                                        Price
                                        <?php if ($sort === 'price'): ?>
                                            <span class="sort-indicator"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
                                        <?php endif; ?>
                                    </th>
                                    <th data-sort="stock_quantity">
                                        Stock
                                        <?php if ($sort === 'stock_quantity'): ?>
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
                                <?php if (!empty($products)): ?>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td class="product-image-cell">
                                                <img src="https://images.pexels.com/photos/1536619/pexels-photo-1536619.jpeg" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-thumbnail">
                                            </td>
                                            <td>
                                                <div class="product-name">
                                                    <a href="edit.php?id=<?php echo $product['id']; ?>">
                                                        <?php echo htmlspecialchars($product['name']); ?>
                                                    </a>
                                                    <span class="product-sku">SKU: <?php echo htmlspecialchars($product['sku']); ?></span>
                                                </div>
                                            </td>
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
                                                <span class="stock-badge <?php echo $product['stock_quantity'] <= 3 ? 'low-stock' : ''; ?>">
                                                    <?php echo $product['stock_quantity']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status-badge status-<?php echo $product['status']; ?>">
                                                    <?php echo ucfirst($product['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="table-actions">
                                                    <a href="edit.php?id=<?php echo $product['id']; ?>" class="action-btn edit-btn" title="Edit Product">
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
                                                    <a href="delete.php?id=<?php echo $product['id']; ?>" class="action-btn delete-btn" title="Delete Product" data-confirm="Are you sure you want to delete this product?">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polyline points="3,6 5,6 21,6"></polyline>
                                                            <path d="M19,6v14a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6m3,0V4a2,2,0,0,1,2-2h4a2,2,0,0,1,2,2V6"></path>
                                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="no-data">No products found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo $page - 1; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&category=<?php echo $category_id; ?>&status=<?php echo $status; ?>&search=<?php echo urlencode($search); ?>" class="pagination-link">
                                    Previous
                                </a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="?page=<?php echo $i; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&category=<?php echo $category_id; ?>&status=<?php echo $status; ?>&search=<?php echo urlencode($search); ?>" 
                                   class="pagination-link <?php echo $i === $page ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo $page + 1; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&category=<?php echo $category_id; ?>&status=<?php echo $status; ?>&search=<?php echo urlencode($search); ?>" class="pagination-link">
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