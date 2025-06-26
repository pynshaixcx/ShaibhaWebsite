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


<html>
<head>
    <meta charset="utf-8"/>
    <link crossorigin="" href="https://fonts.gstatic.com/" rel="preconnect"/>
    <link as="style" href="https://fonts.googleapis.com/css2?display=swap&family=Inter%3Awght%40400%3B500%3B600%3B700%3B900&family=Noto+Sans%3Awght%40400%3B500%3B700%3B900" onload="this.rel='stylesheet'" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>
    <title><?php echo $page_title; ?> - ShaiBha Admin</title>
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
      .card-glow {
        box-shadow: 0 0 20px 5px rgba(255, 0, 0, 0.2);
        border: 1px solid rgba(255, 0, 0, 0.3);
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
<a class="sidebar-item flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200" href="../products/index.php">
<span class="material-icons-outlined text-xl">inventory_2</span>
<p class="text-sm font-medium">Products</p>
</a>
<a class="sidebar-item flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200" href="../customers/index.php">
<span class="material-icons-outlined text-xl">group</span>
<p class="text-sm font-medium">Customers</p>
</a>
<a class="sidebar-item active flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200" href="sales.php">
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

<main class="flex-1 p-6 md:p-10 overflow-y-auto">
<div class="max-w-7xl mx-auto">
<h1 class="text-3xl sm:text-4xl font-bold text-[var(--text-primary)] mb-8">Inventory Status</h1>

<!-- Report Filters -->
<div class="frosted-glass rounded-xl p-6 bg-[var(--background-secondary)] shadow-lg mb-8">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label for="search" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Search</label>
            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Product name, SKU, brand..." class="w-full rounded-lg border-none bg-[rgba(48,48,48,0.7)] text-[var(--text-primary)] text-sm focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div>
            <label for="category" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Category</label>
            <select id="category" name="category" class="w-full rounded-lg border-none bg-[rgba(48,48,48,0.7)] text-[var(--text-primary)] text-sm focus:ring-2 focus:ring-blue-500">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo $category_id == $category['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div>
            <label for="stock_status" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Stock Status</label>
            <select id="stock_status" name="stock_status" class="w-full rounded-lg border-none bg-[rgba(48,48,48,0.7)] text-[var(--text-primary)] text-sm focus:ring-2 focus:ring-blue-500">
                <option value="">All Stock Status</option>
                <option value="in_stock" <?php echo $stock_status === 'in_stock' ? 'selected' : ''; ?>>In Stock</option>
                <option value="out_of_stock" <?php echo $stock_status === 'out_of_stock' ? 'selected' : ''; ?>>Out of Stock</option>
                <option value="low_stock" <?php echo $stock_status === 'low_stock' ? 'selected' : ''; ?>>Low Stock</option>
            </select>
        </div>
        
        <div class="flex items-end space-x-2 md:col-span-3">
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700">Generate Report</button>
            <a href="inventory.php" class="rounded-lg border border-[var(--border-color)] bg-transparent px-4 py-2.5 text-sm font-medium text-[var(--text-secondary)] hover:bg-[rgba(48,48,48,0.7)]">Reset</a>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 @container">
<div class="frosted-glass bg-[var(--background-secondary)] rounded-xl shadow-lg overflow-hidden card-glow p-6 flex flex-col justify-between border border-[var(--border-color)]">
<div>
<div class="flex items-center mb-4">
<div class="p-2 bg-red-500/20 rounded-full mr-3">
<span class="material-icons-outlined text-red-400">warning</span>
</div>
<h2 class="text-xl font-semibold text-[var(--text-primary)]">Low Stock Alerts</h2>
</div>
<p class="text-[var(--text-secondary)] text-sm mb-4"><?php echo $low_stock; ?> items are running low on stock. Take action to replenish your inventory.</p>
</div>
<a href="?stock_status=low_stock" class="w-full mt-auto flex items-center justify-center rounded-md h-10 px-4 bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition-colors">
View Low Stock Items
</a>
</div>
<div class="frosted-glass bg-[var(--background-secondary)] rounded-xl shadow-lg p-6 flex flex-col justify-between border border-[var(--border-color)]">
<div>
<div class="flex items-center mb-4">
<div class="p-2 bg-sky-500/20 rounded-full mr-3">
<span class="material-icons-outlined text-sky-400">apps</span>
</div>
<h2 class="text-xl font-semibold text-[var(--text-primary)]">Total SKUs</h2>
</div>
<p class="text-[var(--text-secondary)] text-sm mb-2">You have a total of</p>
<p class="text-4xl font-bold text-[var(--text-primary)] mb-4"><?php echo $total_products; ?></p>
<p class="text-[var(--text-secondary)] text-sm mb-4">unique SKUs in your inventory.</p>
</div>
<a href="../products/index.php" class="w-full mt-auto flex items-center justify-center rounded-md h-10 px-4 bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium transition-colors">
Manage SKUs
</a>
</div>
<div class="frosted-glass bg-[var(--background-secondary)] rounded-xl shadow-lg p-6 flex flex-col justify-between border border-[var(--border-color)]">
<div>
<div class="flex items-center mb-4">
<div class="p-2 bg-emerald-500/20 rounded-full mr-3">
<span class="material-icons-outlined text-emerald-400">payments</span>
</div>
<h2 class="text-xl font-semibold text-[var(--text-primary)]">Inventory Valuation</h2>
</div>
<p class="text-[var(--text-secondary)] text-sm mb-2">Your total inventory is valued at</p>
<p class="text-4xl font-bold text-[var(--text-primary)] mb-4"><?php echo formatPrice($inventory_value); ?></p>
<p class="text-[var(--text-secondary)] text-sm mb-4">based on current stock levels and pricing.</p>
</div>
<a href="?stock_status=in_stock" class="w-full mt-auto flex items-center justify-center rounded-md h-10 px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium transition-colors">
View Inventory Valuation
</a>
</div>
</div>

<!-- Product List -->
<div class="mt-8">
    <div class="frosted-glass rounded-xl bg-[var(--background-secondary)] shadow-lg overflow-hidden">
        <div class="p-6 border-b border-[var(--border-color)]">
            <h2 class="text-[var(--text-primary)] text-xl font-semibold">Inventory Report</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Product</th>
                        <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">SKU</th>
                        <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Category</th>
                        <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Price</th>
                        <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Value</th>
                        <th class="px-6 py-4 text-right text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-color)]">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <?php
                            $price = $product['sale_price'] ?: $product['price'];
                            $inventory_value = $price * $product['stock_quantity'];
                            $stock_class = $product['stock_quantity'] <= 0 ? 'bg-red-400/20 text-red-400' : ($product['stock_quantity'] <= 3 ? 'bg-amber-400/20 text-amber-400' : 'bg-green-400/20 text-green-400');
                            ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 overflow-hidden rounded-md bg-gray-800">
                                            <img src="https://images.pexels.com/photos/1536619/pexels-photo-1536619.jpeg" alt="<?php echo htmlspecialchars($product['name']); ?>" class="h-full w-full object-cover object-center">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-[var(--text-primary)]">
                                                <a href="../products/edit.php?id=<?php echo $product['id']; ?>" class="hover:text-blue-400">
                                                    <?php echo htmlspecialchars($product['name']); ?>
                                                </a>
                                            </div>
                                            <?php if ($product['brand']): ?>
                                                <div class="text-xs text-[var(--text-secondary)]"><?php echo htmlspecialchars($product['brand']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-primary)]"><?php echo htmlspecialchars($product['sku'] ?: 'N/A'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-primary)]"><?php echo htmlspecialchars($product['category_name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if ($product['sale_price']): ?>
                                        <div class="text-green-400"><?php echo formatPrice($product['sale_price']); ?></div>
                                        <div class="text-[var(--text-secondary)] text-xs line-through"><?php echo formatPrice($product['price']); ?></div>
                                    <?php else: ?>
                                        <div class="text-[var(--text-primary)]"><?php echo formatPrice($product['price']); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $stock_class; ?>">
                                        <?php echo $product['stock_quantity']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $product['status'] === 'active' ? 'bg-green-400/20 text-green-400' : 'bg-gray-400/20 text-gray-400'; ?>">
                                        <?php echo ucfirst($product['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[var(--text-primary)]"><?php echo formatPrice($inventory_value); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="../products/edit.php?id=<?php echo $product['id']; ?>" class="text-blue-400 hover:text-blue-300 mr-3">Edit</a>
                                    <?php if (isset($product['slug'])): ?>
                                    <a href="../../shop/product.php?slug=<?php echo $product['slug']; ?>" target="_blank" class="text-[var(--text-primary)] hover:text-gray-300">View</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-[var(--text-secondary)] text-sm">No products found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</main>
</div>
</div>
</div>
</body>
</html>