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

// Get order counts for each customer
$customer_orders = [];
if (!empty($customers)) {
    $customer_ids = array_column($customers, 'id');
    $customer_ids_str = implode(',', $customer_ids);
    $order_counts = fetchAll("SELECT customer_id, COUNT(*) as order_count FROM orders WHERE customer_id IN ({$customer_ids_str}) GROUP BY customer_id");
    
    foreach ($order_counts as $count) {
        $customer_orders[$count['customer_id']] = $count['order_count'];
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
    <title>ShaiBha Admin - Customers</title>
    <link href="data:image/x-icon;base64," rel="icon" type="image/x-icon"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style type="text/tailwindcss">
        :root {
            --background-primary: rgba(20, 20, 20, 0.7);
            --background-secondary: rgba(48, 48, 48, 0.7);
            --border-color: rgba(48, 48, 48, 0.9);
            --text-primary: #ffffff;
            --text-secondary: #ababab;
            --text-tertiary: #9ca3af;
            --blur-intensity: 10px;
            --sidebar-glow: 0 0 20px 5px rgba(128, 128, 255, 0.2);
            --accent-primary: #60a5fa;
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
        <div class="relative flex size-full min-h-screen flex-col dark group/design-root overflow-x-hidden">
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
                            <a class="sidebar-item flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200" href="../products/index.php">
                                <span class="material-icons-outlined text-xl">inventory_2</span>
                                <p class="text-sm font-medium">Products</p>
                        </a>
                            <a class="sidebar-item flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200 active" href="index.php">
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
                        <div class="flex flex-wrap justify-between items-center gap-4 p-4">
                            <h1 class="text-[var(--text-primary)] tracking-tight text-3xl sm:text-4xl font-bold">All Customers</h1>
                            <div class="flex gap-2">
                                <form method="GET" class="flex flex-wrap gap-2">
                                    <select name="status" class="form-select rounded-lg border-none bg-[rgba(48,48,48,0.7)] text-[var(--text-secondary)] text-sm focus:ring-2 focus:ring-[var(--accent-primary)] focus:border-[var(--accent-primary)]" onchange="this.form.submit()">
                                        <option value="">Filter by Status</option>
                                <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                                    
                                    <?php if ($search): ?>
                                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                                    <?php endif; ?>
                                    
                                    <?php if ($sort && $sort !== 'created_at'): ?>
                                        <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
                                    <?php endif; ?>
                                    
                                    <?php if ($order && $order !== 'desc'): ?>
                                        <input type="hidden" name="order" value="<?php echo htmlspecialchars($order); ?>">
                                    <?php endif; ?>
                                </form>
                        </div>
                        </div>
                        <div class="px-4 py-3">
                            <form method="GET">
                                <label class="flex flex-col min-w-40 h-12 w-full">
                                    <div class="relative w-full flex">
                                        <div class="text-[var(--text-tertiary)] flex items-center justify-center pl-4 absolute left-0" data-icon="MagnifyingGlass" data-size="24px" data-weight="regular">
                                            <svg fill="currentColor" height="20px" viewBox="0 0 256 256" width="20px" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"></path>
                                            </svg>
                </div>
                                        <input name="search" class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[var(--text-primary)] focus:outline-none focus:ring-2 focus:ring-white/50 border border-[var(--border-color)] bg-[rgba(48,48,48,0.7)] h-full placeholder:text-[var(--text-tertiary)] pl-10 pr-4 text-sm font-normal leading-normal" placeholder="Search customers by name, email..." value="<?php echo htmlspecialchars($search); ?>"/>
                                    
                                        <?php if ($status): ?>
                                            <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
                                        <?php endif; ?>
                                        
                                        <?php if ($sort && $sort !== 'created_at'): ?>
                                            <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
                                        <?php endif; ?>
                                        
                                        <?php if ($order && $order !== 'desc'): ?>
                                            <input type="hidden" name="order" value="<?php echo htmlspecialchars($order); ?>">
                                        <?php endif; ?>
                                    </div>
                                </label>
                            </form>
                        </div>
                        <div class="px-4 py-3 @container">
                            <div class="hidden @[640px]:block overflow-hidden rounded-xl border border-[var(--border-color)] frosted-glass">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="bg-[var(--background-secondary)] bg-opacity-50">
                                            <th class="px-4 py-3 text-left text-[var(--text-secondary)] font-semibold">
                                                <a href="?sort=first_name&order=<?php echo ($sort === 'first_name' && $order === 'ASC') ? 'DESC' : 'ASC'; ?><?php echo $status ? '&status=' . htmlspecialchars($status) : ''; ?><?php echo $search ? '&search=' . htmlspecialchars($search) : ''; ?>" class="flex items-center">
                                                    Name
                                                    <?php if ($sort === 'first_name'): ?>
                                                        <span class="ml-1"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
                                                    <?php endif; ?>
                                                </a>
                                    </th>
                                            <th class="px-4 py-3 text-left text-[var(--text-secondary)] font-semibold">
                                                <a href="?sort=email&order=<?php echo ($sort === 'email' && $order === 'ASC') ? 'DESC' : 'ASC'; ?><?php echo $status ? '&status=' . htmlspecialchars($status) : ''; ?><?php echo $search ? '&search=' . htmlspecialchars($search) : ''; ?>" class="flex items-center">
                                                    Email
                                                    <?php if ($sort === 'email'): ?>
                                                        <span class="ml-1"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
                                        <?php endif; ?>
                                                </a>
                                    </th>
                                            <th class="px-4 py-3 text-left text-[var(--text-secondary)] font-semibold">
                                                <a href="?sort=created_at&order=<?php echo ($sort === 'created_at' && $order === 'ASC') ? 'DESC' : 'ASC'; ?><?php echo $status ? '&status=' . htmlspecialchars($status) : ''; ?><?php echo $search ? '&search=' . htmlspecialchars($search) : ''; ?>" class="flex items-center">
                                                    Join Date
                                                    <?php if ($sort === 'created_at'): ?>
                                                        <span class="ml-1"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
                                        <?php endif; ?>
                                                </a>
                                    </th>
                                            <th class="px-4 py-3 text-left text-[var(--text-secondary)] font-semibold">Orders</th>
                                            <th class="px-4 py-3 text-left text-[var(--text-secondary)] font-semibold">Actions</th>
                                </tr>
                            </thead>
                                    <tbody class="divide-y divide-[var(--border-color)]">
                                <?php if (!empty($customers)): ?>
                                    <?php foreach ($customers as $customer): ?>
                                                <tr class="hover:bg-[var(--background-secondary)] hover:bg-opacity-30 transition-colors">
                                                    <td class="px-4 py-3 text-[var(--text-primary)]"><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></td>
                                                    <td class="px-4 py-3 text-[var(--text-tertiary)]"><?php echo htmlspecialchars($customer['email']); ?></td>
                                                    <td class="px-4 py-3 text-[var(--text-tertiary)]"><?php echo date('Y-m-d', strtotime($customer['created_at'])); ?></td>
                                                    <td class="px-4 py-3 text-[var(--text-tertiary)] text-center"><?php echo $customer_orders[$customer['id']] ?? 0; ?></td>
                                                    <td class="px-4 py-3 text-[var(--text-tertiary)]">
                                                        <a href="view.php?id=<?php echo $customer['id']; ?>" class="text-[var(--accent-primary)] hover:underline text-xs">View</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                                <td colspan="5" class="px-4 py-3 text-center text-[var(--text-tertiary)]">No customers found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                            
                            <!-- Mobile View -->
                            <div class="grid grid-cols-1 @[480px]:grid-cols-2 gap-4 @[640px]:hidden">
                                <?php if (!empty($customers)): ?>
                                    <?php foreach ($customers as $customer): ?>
                                        <div class="rounded-xl border border-[var(--border-color)] p-4 frosted-glass space-y-2">
                                            <div class="flex justify-between items-start">
                                                <h3 class="text-lg font-semibold text-[var(--text-primary)]"><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></h3>
                                                <a href="view.php?id=<?php echo $customer['id']; ?>" class="text-[var(--accent-primary)] hover:underline text-xs mt-1">View Details</a>
                                            </div>
                                            <p class="text-sm text-[var(--text-tertiary)]"><?php echo htmlspecialchars($customer['email']); ?></p>
                                            <div class="flex justify-between text-xs text-[var(--text-tertiary)] pt-1">
                                                <span>Joined: <span class="text-[var(--text-secondary)]"><?php echo date('Y-m-d', strtotime($customer['created_at'])); ?></span></span>
                                                <span>Orders: <span class="text-[var(--text-secondary)]"><?php echo $customer_orders[$customer['id']] ?? 0; ?></span></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="rounded-xl border border-[var(--border-color)] p-4 frosted-glass col-span-2 text-center text-[var(--text-tertiary)]">
                                        No customers found
                                    </div>
                                <?php endif; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                                <div class="mt-6 flex justify-center">
                                    <div class="flex space-x-1">
                            <?php if ($page > 1): ?>
                                            <a href="?page=<?php echo $page - 1; ?><?php echo $status ? '&status=' . htmlspecialchars($status) : ''; ?><?php echo $search ? '&search=' . htmlspecialchars($search) : ''; ?><?php echo $sort && $sort !== 'created_at' ? '&sort=' . htmlspecialchars($sort) : ''; ?><?php echo $order && $order !== 'desc' ? '&order=' . htmlspecialchars($order) : ''; ?>" class="px-4 py-2 rounded-lg border border-[var(--border-color)] text-[var(--text-secondary)] hover:bg-[var(--background-secondary)] transition-colors">
                                    Previous
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                            <a href="?page=<?php echo $page + 1; ?><?php echo $status ? '&status=' . htmlspecialchars($status) : ''; ?><?php echo $search ? '&search=' . htmlspecialchars($search) : ''; ?><?php echo $sort && $sort !== 'created_at' ? '&sort=' . htmlspecialchars($sort) : ''; ?><?php echo $order && $order !== 'desc' ? '&order=' . htmlspecialchars($order) : ''; ?>" class="px-4 py-2 rounded-lg border border-[var(--border-color)] text-[var(--text-secondary)] hover:bg-[var(--background-secondary)] transition-colors">
                                    Next
                                </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>
</body>
</html>