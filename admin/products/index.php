<?php
$page_title = "Products";
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Get all products
$products = fetchAll("
    SELECT p.*, c.name as category_name 
    FROM products p
        LEFT JOIN categories c ON p.category_id = c.id 
    ORDER BY p.created_at DESC
");

// Handle search
$search = sanitizeInput($_GET['search'] ?? '');
if ($search) {
    $products = fetchAll("
        SELECT p.*, c.name as category_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.name LIKE ? OR p.sku LIKE ? OR c.name LIKE ?
        ORDER BY p.created_at DESC
    ", ["%$search%", "%$search%", "%$search%"]);
}

// Handle category filter
$category_filter = sanitizeInput($_GET['category'] ?? '');
if ($category_filter) {
    $products = fetchAll("
        SELECT p.*, c.name as category_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE c.id = ?
        ORDER BY p.created_at DESC
    ", [$category_filter]);
}

// Get categories for filter
$categories = fetchAll("SELECT * FROM categories ORDER BY name");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <link crossorigin="" href="https://fonts.gstatic.com/" rel="preconnect"/>
    <link as="style" href="https://fonts.googleapis.com/css2?display=swap&family=Inter%3Awght%40400%3B500%3B600%3B700%3B900&family=Noto+Sans%3Awght%40400%3B500%3B700%3B900" onload="this.rel='stylesheet'" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>
    <title>ShaiBha Admin - Products</title>
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
        /* Product-specific styles */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .status-in-stock {
            background-color: rgba(74, 222, 128, 0.1);
            color: #4ade80;
        }
        .status-low-stock {
            background-color: rgba(250, 204, 21, 0.1);
            color: #fac00a;
        }
        .status-out-of-stock {
            background-color: rgba(248, 113, 113, 0.1);
            color: #f87171;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-black via-slate-900 to-black">
    <div class="relative flex size-full min-h-screen flex-col bg-cover bg-center bg-fixed" style='font-family: Inter, "Noto Sans", sans-serif;'>
        <div class="relative flex size-full min-h-screen flex-col dark group/design-root">
            <div class="layout-container flex h-full grow flex-col">
                <!-- Header -->
                <header class="frosted-glass sticky top-0 z-50 flex items-center justify-between whitespace-nowrap border-b border-solid border-[var(--border-color)] bg-[var(--background-primary)] px-6 py-4 md:px-10">
                    <div class="flex items-center gap-4 text-[var(--text-primary)]">
                        <h2 class="text-[var(--text-primary)] text-xl font-semibold leading-tight tracking-[-0.015em]">
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
                    <main class="flex-1 p-6 md:p-10 overflow-y-auto">
                        <div class="frosted-glass rounded-xl p-6">
                            <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                                <h1 class="text-[var(--text-primary)] text-3xl font-bold">Product Inventory</h1>
                                <a href="add.php" class="bg-[var(--accent-color)] text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 hover:opacity-90 transition-opacity">
                                    <svg fill="currentColor" height="16" viewBox="0 0 256 256" width="16" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M224,128a8,8,0,0,1-8,8H136v80a8,8,0,0,1-16,0V136H40a8,8,0,0,1,0-16h80V40a8,8,0,0,1,16,0v80h80A8,8,0,0,1,224,128Z"></path>
                            </svg>
                                    Add Product
                        </a>
                    </div>
                            
                            <!-- Search and Filters -->
                            <form method="GET" class="mb-6">
                                <div class="flex gap-3 mb-6 flex-wrap">
                                    <div class="flex-1">
                                        <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>" class="w-full h-9 rounded-md px-4 py-1.5 text-sm bg-[var(--background-secondary)] border border-[var(--border-color)] text-[var(--text-primary)]">
                        </div>
                                    <select name="category" class="h-9 rounded-md px-4 py-1.5 text-sm bg-[var(--background-secondary)] border border-[var(--border-color)] text-[var(--text-secondary)]">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>" <?php echo $category_filter == $category['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                                    <button type="submit" class="h-9 px-4 py-1.5 rounded-md text-sm font-medium bg-[var(--background-secondary)] border border-[var(--border-color)] text-[var(--text-primary)] hover:bg-opacity-80 transition-colors">
                                        Filter
                                    </button>
                        </div>
                    </form>
                            
                            <div class="frosted-glass overflow-hidden">
                                <table class="w-full text-left">
                                    <thead class="border-b border-[var(--border-color)]">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">Product</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">Category</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">Stock</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">Price</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">Status</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-white">Actions</th>
                                </tr>
                            </thead>
                                    <tbody class="divide-y divide-[var(--border-color)]">
                                        <?php if (count($products) > 0): ?>
                                    <?php foreach ($products as $product): ?>
                                                <?php 
                                                    $statusClass = 'status-in-stock';
                                                    $statusText = 'In Stock';
                                                    
                                                    if ($product['stock_quantity'] <= 0) {
                                                        $statusClass = 'status-out-of-stock';
                                                        $statusText = 'Out of Stock';
                                                    } elseif ($product['stock_quantity'] <= 10) {
                                                        $statusClass = 'status-low-stock';
                                                        $statusText = 'Low Stock';
                                                    }
                                                ?>
                                                <tr class="hover:bg-[rgba(255,255,255,0.03)]">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[var(--text-primary)]">
                                                        <?php echo htmlspecialchars($product['name']); ?>
                                            </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-secondary)]">
                                                        <?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?>
                                            </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-secondary)]">
                                                    <?php echo $product['stock_quantity']; ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-secondary)]">
                                                        <?php echo formatPrice($product['price']); ?>
                                            </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                            </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex space-x-2">
                                                            <a href="edit.php?id=<?php echo $product['id']; ?>" class="text-blue-400 hover:text-blue-300">
                                                                <span class="material-icons-outlined text-sm">edit</span>
                                                    </a>
                                                            <a href="delete.php?id=<?php echo $product['id']; ?>" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure you want to delete this product?');">
                                                                <span class="material-icons-outlined text-sm">delete</span>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                                <td colspan="6" class="px-6 py-4 text-center text-sm text-[var(--text-secondary)]">
                                                    No products found
                                                </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>
</body>
</html>