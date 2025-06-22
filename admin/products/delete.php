<?php
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Get product ID
$product_id = intval($_GET['id'] ?? 0);
if (!$product_id) {
    redirect('index.php');
}

// Get product data
$product = fetchOne("SELECT * FROM products WHERE id = ?", [$product_id]);
if (!$product) {
    redirect('index.php');
}

// Check if product is in any orders
$in_orders = fetchOne("SELECT COUNT(*) as count FROM order_items WHERE product_id = ?", [$product_id])['count'];

// Handle confirmation
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    // Check CSRF token
    if (!verifyCSRFToken($_GET['token'] ?? '')) {
        setFlashMessage('error', 'Invalid security token. Please try again.');
        redirect('index.php');
    }
    
    // If product is in orders, just mark as inactive
    if ($in_orders > 0) {
        $result = executeQuery("UPDATE products SET status = 'inactive', updated_at = CURRENT_TIMESTAMP WHERE id = ?", [$product_id]);
        
        if ($result) {
            // Log activity
            logActivity('admin', getCurrentAdminId(), 'product_deactivated', "Product '{$product['name']}' deactivated (in orders)");
            
            setFlashMessage('success', 'Product has been deactivated because it exists in orders.');
            redirect('index.php');
        } else {
            setFlashMessage('error', 'Failed to deactivate product. Please try again.');
            redirect('index.php');
        }
    } else {
        // Delete product images first
        executeQuery("DELETE FROM product_images WHERE product_id = ?", [$product_id]);
        
        // Delete product
        $result = executeQuery("DELETE FROM products WHERE id = ?", [$product_id]);
        
        if ($result) {
            // Log activity
            logActivity('admin', getCurrentAdminId(), 'product_deleted', "Product '{$product['name']}' deleted");
            
            setFlashMessage('success', 'Product deleted successfully.');
            redirect('index.php');
        } else {
            setFlashMessage('error', 'Failed to delete product. Please try again.');
            redirect('index.php');
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
    <title>Delete Product - ShaiBha Admin</title>
    
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
                    <h1 class="page-title">Delete Product</h1>
                    <div class="header-actions">
                        <a href="index.php" class="btn btn-outline">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            Back to Products
                        </a>
                    </div>
                </div>
            </header>

            <!-- Delete Product Content -->
            <div class="admin-content">
                <div class="delete-confirmation">
                    <div class="confirmation-icon">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    
                    <h2>Delete Product</h2>
                    
                    <div class="product-info">
                        <div class="product-image">
                            <img src="https://images.pexels.com/photos/1536619/pexels-photo-1536619.jpeg" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                        <div class="product-details">
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="product-sku">SKU: <?php echo htmlspecialchars($product['sku']); ?></p>
                            <p class="product-price">Price: <?php echo formatPrice($product['price']); ?></p>
                        </div>
                    </div>
                    
                    <?php if ($in_orders > 0): ?>
                        <div class="alert alert-warning">
                            <p><strong>Warning:</strong> This product is associated with <?php echo $in_orders; ?> order(s).</p>
                            <p>Instead of deleting, the product will be marked as inactive.</p>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <p><strong>Warning:</strong> This action cannot be undone.</p>
                            <p>All product data, including images, will be permanently deleted.</p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="confirmation-actions">
                        <a href="delete.php?id=<?php echo $product_id; ?>&confirm=yes&token=<?php echo $token; ?>" class="btn btn-danger">
                            <?php echo $in_orders > 0 ? 'Deactivate Product' : 'Delete Product'; ?>
                        </a>
                        <a href="index.php" class="btn btn-outline">Cancel</a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../../js/admin.js"></script>
</body>
</html>