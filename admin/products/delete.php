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
<html>
<head>
    <meta charset="utf-8"/>
    <link crossorigin="" href="https://fonts.gstatic.com/" rel="preconnect"/>
    <link as="style" href="https://fonts.googleapis.com/css2?display=swap&family=Inter%3Awght%40400%3B500%3B600%3B700%3B900&family=Noto+Sans%3Awght%40400%3B500%3B700%3B900" onload="this.rel='stylesheet'" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>
    <title>ShaiBha Admin - Delete Product</title>
    <link href="data:image/x-icon;base64," rel="icon" type="image/x-icon"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style type="text/tailwindcss">
      :root {
        --background-primary: rgba(20, 20, 20, 0.7);
        --background-secondary: rgba(48, 48, 48, 0.7);
        --border-color: rgba(48, 48, 48, 0.9);
        --text-primary: #ffffff;
        --text-secondary: #ababab;
        --blur-intensity: 10px;
        --sidebar-glow: 0 0 20px 5px rgba(128, 128, 255, 0.2);
      }
      .frosted-glass {
        backdrop-filter: blur(var(--blur-intensity));
        -webkit-backdrop-filter: blur(var(--blur-intensity));
      }
      .sidebar-item:hover, .sidebar-item.active {
        background-color: var(--background-secondary) !important;
        border-radius: 0.5rem;
      }
      .icon-button:hover {
        background-color: rgba(75, 75, 75, 0.7) !important;
      }
      .sidebar-glow-effect {
        box-shadow: var(--sidebar-glow);
          }
        </style>
</head>
<body class="bg-gradient-to-br from-black via-slate-900 to-black">
<div class="relative flex size-full min-h-screen flex-col bg-cover bg-center bg-fixed" style='font-family: Inter, "Noto Sans", sans-serif;'>
<div class="relative flex size-full min-h-screen flex-col dark group/design-root">
<div class="layout-container flex h-full grow flex-col">
<header class="frosted-glass sticky top-0 z-50 flex items-center justify-between whitespace-nowrap border-b border-solid border-[var(--border-color)] bg-[var(--background-primary)] px-6 py-4 md:px-10">
<div class="flex items-center gap-4 text-[var(--text-primary)]">
<h2 class="text-xl font-semibold leading-tight tracking-[-0.015em]">
<span class="font-bold">ShaiBha</span> Admin Panel
</h2>
</div>
<div class="flex items-center gap-3">
            </div>
</header>

<div class="flex flex-1">
<!-- Sidebar -->
<aside class="frosted-glass sticky top-[73px] h-[calc(100vh-73px)] w-64 flex-col justify-between border-r border-solid border-[var(--border-color)] bg-[var(--background-primary)] p-4 hidden md:flex sidebar-glow-effect rounded-r-xl">
<nav class="flex flex-col gap-2">
<a class="sidebar-item flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200" href="../index.php">
<span class="material-icons-outlined text-xl">dashboard</span>
<p class="text-sm font-medium">Dashboard</p>
                        </a>
<a class="sidebar-item flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200" href="../orders/index.php">
<span class="material-icons-outlined text-xl">list_alt</span>
<p class="text-sm font-medium">Orders</p>
</a>
<a class="sidebar-item active flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200" href="index.php">
<span class="material-icons-outlined text-xl">inventory_2</span>
<p class="text-sm font-medium">Products</p>
                        </a>
<a class="sidebar-item flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200" href="../customers/index.php">
<span class="material-icons-outlined text-xl">group</span>
<p class="text-sm font-medium">Customers</p>
                        </a>
<a class="sidebar-item flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200" href="../reports/sales.php">
<span class="material-icons-outlined text-xl">bar_chart</span>
<p class="text-sm font-medium">Reports</p>
                        </a>
            </nav>
<div class="flex flex-col gap-1 pt-4 border-t border-[var(--border-color)] mt-auto">
<a class="sidebar-item flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200" href="../logout.php">
<span class="material-icons-outlined text-xl">logout</span>
<p class="text-sm font-medium">Logout</p>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
<main class="flex-1 p-6 md:p-10 overflow-y-auto flex items-center justify-center">
<div class="frosted-glass flex w-full max-w-md flex-col items-center rounded-xl border border-[var(--border-color)] p-8 shadow-2xl bg-[var(--background-secondary)]">
<h3 class="text-center text-xl font-semibold leading-tight tracking-tight text-[var(--text-primary)] sm:text-2xl">
    <?php if ($in_orders > 0): ?>
        Are you sure you want to deactivate this product?
    <?php else: ?>
        Are you sure you want to delete this product?
    <?php endif; ?>
</h3>
                    
<div class="mt-4 text-center">
    <p class="text-[var(--text-primary)] font-medium"><?php echo htmlspecialchars($product['name']); ?></p>
    <p class="text-[var(--text-secondary)]">SKU: <?php echo htmlspecialchars($product['sku']); ?></p>
    <p class="text-[var(--text-secondary)]">Price: <?php echo formatPrice($product['price']); ?></p>
                    </div>
                    
                    <?php if ($in_orders > 0): ?>
    <div class="mt-4 w-full rounded-lg bg-yellow-500/20 p-3 text-yellow-300 text-sm">
                            <p><strong>Warning:</strong> This product is associated with <?php echo $in_orders; ?> order(s).</p>
                            <p>Instead of deleting, the product will be marked as inactive.</p>
                        </div>
                    <?php else: ?>
    <div class="mt-4 w-full rounded-lg bg-red-500/20 p-3 text-red-300 text-sm">
                            <p><strong>Warning:</strong> This action cannot be undone.</p>
                            <p>All product data, including images, will be permanently deleted.</p>
                        </div>
                    <?php endif; ?>
                    
<div class="mt-8 flex w-full flex-col gap-4 sm:flex-row sm:justify-center">
<a href="index.php" class="flex h-11 min-w-[84px] flex-1 cursor-pointer items-center justify-center overflow-hidden rounded-lg border border-[var(--border-color)] bg-transparent px-6 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--background-primary)] focus:outline-none focus:ring-2 focus:ring-white/20 focus:ring-offset-2 focus:ring-offset-black sm:flex-initial">
    <span class="truncate">Cancel</span>
</a>
<a href="delete.php?id=<?php echo $product_id; ?>&confirm=yes&token=<?php echo $token; ?>" class="flex h-11 min-w-[84px] flex-1 cursor-pointer items-center justify-center overflow-hidden rounded-lg bg-red-600 px-6 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-black sm:flex-initial">
    <span class="truncate"><?php echo $in_orders > 0 ? 'Deactivate' : 'Delete'; ?></span>
</a>
                </div>
            </div>
        </main>
    </div>
    </div>
    </div>
    </div>
</body>
</html>