<?php
$page_title = "Admin Dashboard";
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('login.php');
}

$admin_id = getCurrentAdminId();
$admin = fetchOne("SELECT * FROM admin_users WHERE id = ?", [$admin_id]);

// Get dashboard statistics
$total_orders = fetchOne("SELECT COUNT(*) as count FROM orders")['count'];
$pending_orders = fetchOne("SELECT COUNT(*) as count FROM orders WHERE order_status = 'pending'")['count'];
$total_customers = fetchOne("SELECT COUNT(*) as count FROM customers")['count'];
$total_products = fetchOne("SELECT COUNT(*) as count FROM products")['count'];
$low_stock_products = fetchOne("SELECT COUNT(*) as count FROM products WHERE stock_quantity <= 3 AND status = 'active'")['count'];
$total_revenue = fetchOne("SELECT SUM(total_amount) as total FROM orders WHERE payment_status = 'paid'")['total'] ?: 0;

// Get recent orders
$recent_orders = fetchAll("
    SELECT o.*, c.first_name, c.last_name, c.email 
    FROM orders o 
    LEFT JOIN customers c ON o.customer_id = c.id 
    ORDER BY o.created_at DESC 
    LIMIT 5
");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <link crossorigin="" href="https://fonts.gstatic.com/" rel="preconnect"/>
    <link as="style" href="https://fonts.googleapis.com/css2?display=swap&family=Inter%3Awght%40400%3B500%3B600%3B700%3B900&family=Noto+Sans%3Awght%40400%3B500%3B700%3B900" onload="this.rel='stylesheet'" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>
    <title>ShaiBha Admin</title>
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
    <div class="relative flex size-full min-h-screen flex-col dark group/design-root overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">
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
    <aside class="frosted-glass sticky top-[73px] h-[calc(100vh-73px)] w-64 flex-col justify-between border-r border-solid border-[var(--border-color)] bg-[var(--background-primary)] p-4 hidden md:flex sidebar-glow-effect rounded-r-xl">
    <nav class="flex flex-col gap-2">
    <a class="sidebar-item active flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200" href="#">
    <span class="material-icons-outlined text-xl">dashboard</span>
    <p class="text-sm font-medium">Dashboard</p>
    </a>
    <a class="sidebar-item flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200 hover:bg-[var(--background-secondary)] hover:rounded-lg" href="orders/index.php">
    <span class="material-icons-outlined text-xl">list_alt</span>
    <p class="text-sm font-medium">Orders</p>
    </a>
    <a class="sidebar-item flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200 hover:bg-[var(--background-secondary)] hover:rounded-lg" href="products/index.php">
    <span class="material-icons-outlined text-xl">inventory_2</span>
    <p class="text-sm font-medium">Products</p>
    </a>
    <a class="sidebar-item flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200 hover:bg-[var(--background-secondary)] hover:rounded-lg" href="customers/index.php">
    <span class="material-icons-outlined text-xl">group</span>
    <p class="text-sm font-medium">Customers</p>
    </a>
    <a class="sidebar-item flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200 hover:bg-[var(--background-secondary)] hover:rounded-lg" href="reports/sales.php">
    <span class="material-icons-outlined text-xl">bar_chart</span>
    <p class="text-sm font-medium">Reports</p>
    </a>
            </nav>
    <div class="flex flex-col gap-1 pt-4 border-t border-[var(--border-color)] mt-auto">
    <a class="sidebar-item flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200 hover:bg-[var(--background-secondary)] hover:rounded-lg" href="logout.php">
    <span class="material-icons-outlined text-xl">logout</span>
    <p class="text-sm font-medium">Logout</p>
                </a>
            </div>
        </aside>
    <main class="flex-1 p-6 md:p-10 overflow-y-auto">
    <div class="mb-8">
    <p class="text-[var(--text-primary)] text-3xl font-bold leading-tight">Dashboard</p>
                        </div>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-8">
    <div class="frosted-glass flex flex-col gap-2 rounded-xl p-6 bg-[var(--background-secondary)] shadow-lg">
    <p class="text-[var(--text-primary)] text-base font-medium">Total Orders</p>
    <p class="text-[var(--text-primary)] text-3xl font-bold"><?php echo $total_orders; ?></p>
    <p class="text-[var(--text-secondary)] text-sm"><?php echo $pending_orders; ?> pending</p>
                    </div>
    <div class="frosted-glass flex flex-col gap-2 rounded-xl p-6 bg-[var(--background-secondary)] shadow-lg">
    <p class="text-[var(--text-primary)] text-base font-medium">Total Revenue</p>
    <p class="text-[var(--text-primary)] text-3xl font-bold"><?php echo formatPrice($total_revenue); ?></p>
    <p class="text-[var(--text-secondary)] text-sm">From paid orders</p>
                </div>
    <div class="frosted-glass flex flex-col gap-2 rounded-xl p-6 bg-[var(--background-secondary)] shadow-lg">
    <p class="text-[var(--text-primary)] text-base font-medium">Active Users</p>
    <p class="text-[var(--text-primary)] text-3xl font-bold"><?php echo $total_customers; ?></p>
    <p class="text-[var(--text-secondary)] text-sm">Registered users</p>
                            </div>
                        </div>
    <div class="mb-8">
    <h2 class="text-[var(--text-primary)] text-2xl font-semibold mb-4">Quick Actions</h2>
    <div class="flex flex-wrap gap-4">
    <a href="orders/index.php" class="frosted-glass flex items-center justify-center rounded-lg h-12 px-6 bg-white/10 text-[var(--text-primary)] text-sm font-semibold transition-colors duration-200 hover:bg-white/20">
    <span class="material-icons-outlined mr-2">add_circle_outline</span>
    <span>View Orders</span>
    </a>
    <a href="products/index.php" class="frosted-glass flex items-center justify-center rounded-lg h-12 px-6 bg-[var(--background-secondary)] text-[var(--text-primary)] text-sm font-semibold transition-colors duration-200 hover:bg-[rgba(75,75,75,0.7)]">
    <span class="material-icons-outlined mr-2">visibility</span>
    <span>Manage Products</span>
    </a>
                            </div>
                        </div>
    <div>
    <h2 class="text-[var(--text-primary)] text-2xl font-semibold mb-4">Recent Orders</h2>
    <div class="frosted-glass @container overflow-hidden rounded-xl border border-[var(--border-color)] bg-[var(--background-primary)] shadow-lg">
    <div class="overflow-x-auto">
    <table class="min-w-full">
    <thead class="bg-white/5">
    <tr>
    <th class="table-9c6cc8fa-5b60-4d87-b11d-51a51beceb24-column-120 px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Order ID</th>
    <th class="table-9c6cc8fa-5b60-4d87-b11d-51a51beceb24-column-240 px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Customer</th>
    <th class="table-9c6cc8fa-5b60-4d87-b11d-51a51beceb24-column-360 px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Date</th>
    <th class="table-9c6cc8fa-5b60-4d87-b11d-51a51beceb24-column-480 px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Status</th>
    <th class="table-9c6cc8fa-5b60-4d87-b11d-51a51beceb24-column-600 px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
    <tbody class="divide-y divide-[var(--border-color)]">
                                    <?php foreach ($recent_orders as $order): ?>
                                        <tr>
    <td class="table-9c6cc8fa-5b60-4d87-b11d-51a51beceb24-column-120 px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm font-medium">#<?php echo $order['id']; ?></td>
    <td class="table-9c6cc8fa-5b60-4d87-b11d-51a51beceb24-column-240 px-6 py-4 whitespace-nowrap text-[var(--text-secondary)] text-sm"><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
    <td class="table-9c6cc8fa-5b60-4d87-b11d-51a51beceb24-column-360 px-6 py-4 whitespace-nowrap text-[var(--text-secondary)] text-sm"><?php echo formatDate($order['created_at']); ?></td>
    <td class="table-9c6cc8fa-5b60-4d87-b11d-51a51beceb24-column-480 px-6 py-4 whitespace-nowrap">
    <?php
    $statusClass = '';
    switch ($order['order_status']) {
        case 'pending':
            $statusClass = 'bg-yellow-500/30 text-yellow-300';
            break;
        case 'processing':
            $statusClass = 'bg-blue-500/30 text-blue-300';
            break;
        case 'shipped':
            $statusClass = 'bg-blue-500/30 text-blue-300';
            break;
        case 'delivered':
            $statusClass = 'bg-green-500/30 text-green-300';
            break;
        case 'cancelled':
            $statusClass = 'bg-red-500/30 text-red-300';
            break;
    }
    ?>
    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusClass; ?>"><?php echo ucfirst($order['order_status']); ?></span>
                                            </td>
    <td class="table-9c6cc8fa-5b60-4d87-b11d-51a51beceb24-column-600 px-6 py-4 whitespace-nowrap text-[var(--text-secondary)] text-sm"><?php echo formatPrice($order['total_amount']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
    <style>
                        @media (max-width: 768px) {.table-9c6cc8fa-5b60-4d87-b11d-51a51beceb24-column-360,
                          .table-9c6cc8fa-5b60-4d87-b11d-51a51beceb24-column-600 {
                            display: none;
                          }
                        }
                         @media (max-width: 640px) {.table-9c6cc8fa-5b60-4d87-b11d-51a51beceb24-column-240,
                          .table-9c6cc8fa-5b60-4d87-b11d-51a51beceb24-column-480 {
                            display: none;
                          }
                        }
                      </style>
                </div>
            </div>
        </main>
    </div>
    </div>
    </div>
    </div>
</body>
</html>