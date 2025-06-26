<?php
$page_title = "View Customer";
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Get customer ID
$customer_id = intval($_GET['id'] ?? 0);
if (!$customer_id) {
    redirect('index.php');
}

// Get customer data
$customer = fetchOne("SELECT * FROM customers WHERE id = ?", [$customer_id]);
if (!$customer) {
    redirect('index.php');
}

// Get customer orders
$orders = fetchAll("SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC", [$customer_id]);

// Get customer addresses
$addresses = fetchAll("SELECT * FROM customer_addresses WHERE customer_id = ? ORDER BY is_default DESC", [$customer_id]);

// Get customer activity
$activity = fetchAll("SELECT * FROM activity_log WHERE user_type = 'customer' AND user_id = ? ORDER BY created_at DESC LIMIT 20", [$customer_id]);

$error = '';
$success = '';

// Handle status toggle
if (isset($_GET['toggle_status']) && $_GET['toggle_status'] === 'yes') {
    // Validate CSRF token
    if (!verifyCSRFToken($_GET['token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $new_status = $customer['status'] === 'active' ? 'inactive' : 'active';
        
        $result = executeQuery("UPDATE customers SET status = ? WHERE id = ?", [$new_status, $customer_id]);
        
        if ($result) {
            // Log activity
            logActivity('admin', getCurrentAdminId(), 'customer_status_updated', "Customer {$customer['first_name']} {$customer['last_name']} status updated to {$new_status}");
            
            $success = 'Customer status updated successfully!';
            
            // Refresh customer data
            $customer = fetchOne("SELECT * FROM customers WHERE id = ?", [$customer_id]);
        } else {
            $error = 'Failed to update customer status. Please try again.';
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
    <title>Customer Profile - ShaiBha Admin</title>
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
            --accent-color: #4f46e5;
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
          .status-badge {
            padding: 0.375rem 0.875rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
          }
          .status-shipped {
            background-color: rgba(59, 130, 246, 0.2);color: #60a5fa;
          }
          .status-delivered {
            background-color: rgba(34, 197, 94, 0.2);color: #4ade80;
          }
          .status-cancelled {
            background-color: rgba(239, 68, 68, 0.2);color: #f87171;
          }
          .status-active {
            background-color: rgba(34, 197, 94, 0.2);color: #4ade80;
          }
          .status-inactive {
            background-color: rgba(239, 68, 68, 0.2);color: #f87171;
          }
          .status-pending {
            background-color: rgba(245, 158, 11, 0.2);color: #fbbf24;
          }
          .timeline-item:last-child .timeline-line {
            display: none;
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
    <?php if ($error): ?>
        <div class="bg-red-500/30 text-red-300 p-2 rounded-lg">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="bg-green-500/30 text-green-300 p-2 rounded-lg">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>
            </div>
    </header>
    <div class="flex flex-1">
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
    <a class="sidebar-item active flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200" href="index.php">
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
    <main class="flex-1 p-6 md:p-10 overflow-y-auto">
    <div class="flex justify-between items-center mb-8">
        <p class="text-[var(--text-primary)] text-3xl font-bold leading-tight">Customer Profile</p>
        <div class="flex space-x-2">
            <a href="index.php" class="flex h-10 items-center justify-center overflow-hidden rounded-lg border border-[var(--border-color)] bg-transparent px-6 text-sm font-medium text-[var(--text-secondary)] hover:bg-[var(--background-primary)]">
                <span class="material-icons-outlined text-sm mr-1">arrow_back</span> Back
                        </a>
            <a href="view.php?id=<?php echo $customer_id; ?>&toggle_status=yes&token=<?php echo $token; ?>" class="flex h-10 items-center justify-center overflow-hidden rounded-lg px-6 text-sm font-medium text-white 
                <?php echo $customer['status'] === 'active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'; ?>">
                            <?php if ($customer['status'] === 'active'): ?>
                    <span class="material-icons-outlined text-sm mr-1">block</span> Deactivate
                            <?php else: ?>
                    <span class="material-icons-outlined text-sm mr-1">check_circle</span> Activate
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
    <section class="frosted-glass rounded-xl p-6 bg-[var(--background-secondary)] shadow-lg mb-8">
    <h2 class="text-[var(--text-primary)] text-2xl font-semibold mb-6">Contact Info</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
        <div>
            <p class="text-[var(--text-secondary)] text-sm">Name</p>
            <p class="text-[var(--text-primary)] text-base mt-1"><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></p>
                    </div>
        <div>
            <p class="text-[var(--text-secondary)] text-sm">Email</p>
            <p class="text-[var(--text-primary)] text-base mt-1"><?php echo htmlspecialchars($customer['email']); ?></p>
                    </div>
        <div>
            <p class="text-[var(--text-secondary)] text-sm">Phone</p>
            <p class="text-[var(--text-primary)] text-base mt-1"><?php echo htmlspecialchars($customer['phone'] ?: 'Not provided'); ?></p>
                                </div>
        <div>
            <p class="text-[var(--text-secondary)] text-sm">Status</p>
            <p class="mt-1">
                                        <span class="status-badge status-<?php echo $customer['status']; ?>">
                                            <?php echo ucfirst($customer['status']); ?>
                                        </span>
            </p>
                                    </div>
        <div>
            <p class="text-[var(--text-secondary)] text-sm">Registered</p>
            <p class="text-[var(--text-primary)] text-base mt-1"><?php echo date('Y-m-d', strtotime($customer['created_at'])); ?></p>
                                </div>
        <div>
            <p class="text-[var(--text-secondary)] text-sm">Last Login</p>
            <p class="text-[var(--text-primary)] text-base mt-1"><?php echo $customer['last_login'] ? date('Y-m-d H:i', strtotime($customer['last_login'])) : 'Never'; ?></p>
                            </div>
        <?php if (!empty($addresses)): ?>
            <div class="col-span-1 sm:col-span-2 mt-4">
                <p class="text-[var(--text-secondary)] text-sm mb-2">Addresses</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($addresses as $address): ?>
                        <div class="border border-[var(--border-color)] rounded-lg p-4 bg-[var(--background-primary)]">
                            <?php if ($address['is_default']): ?>
                                <div class="mb-2">
                                    <span class="bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded-full">Default</span>
                                </div>
                            <?php endif; ?>
                            <p class="text-[var(--text-primary)]"><?php echo htmlspecialchars($address['address_line1']); ?></p>
                            <?php if ($address['address_line2']): ?>
                                <p class="text-[var(--text-primary)]"><?php echo htmlspecialchars($address['address_line2']); ?></p>
                            <?php endif; ?>
                            <p class="text-[var(--text-primary)]">
                                <?php echo htmlspecialchars($address['city'] . ', ' . $address['state'] . ' ' . $address['postal_code']); ?>
                            </p>
                            <p class="text-[var(--text-primary)]"><?php echo htmlspecialchars($address['country']); ?></p>
                                </div>
                    <?php endforeach; ?>
                                </div>
                            </div>
        <?php endif; ?>
                        </div>
    </section>
                        
                            <?php if (!empty($orders)): ?>
    <section class="frosted-glass rounded-xl p-6 bg-[var(--background-secondary)] shadow-lg mb-8">
    <h2 class="text-[var(--text-primary)] text-2xl font-semibold mb-6">Order History</h2>
    <div class="frosted-glass @container overflow-hidden rounded-xl border border-[var(--border-color)] bg-[var(--background-primary)] shadow-lg">
    <div class="overflow-x-auto">
    <table class="min-w-full">
    <thead class="bg-white/5">
                                            <tr>
    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Order ID</th>
    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Date</th>
    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Status</th>
    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Total</th>
    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
    <tbody class="divide-y divide-[var(--border-color)]">
                                            <?php foreach ($orders as $order): ?>
                                                <tr>
    <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm font-medium">#<?php echo $order['order_number']; ?></td>
    <td class="px-6 py-4 whitespace-nowrap text-[var(--text-secondary)] text-sm"><?php echo date('Y-m-d', strtotime($order['created_at'])); ?></td>
    <td class="px-6 py-4 whitespace-nowrap">
    <span class="status-badge status-<?php echo strtolower($order['order_status']); ?>"><?php echo ucfirst($order['order_status']); ?></span>
                                                    </td>
    <td class="px-6 py-4 whitespace-nowrap text-[var(--text-secondary)] text-sm"><?php echo formatPrice($order['total_amount']); ?></td>
    <td class="px-6 py-4 whitespace-nowrap text-[var(--text-secondary)] text-sm">
        <a href="../orders/view.php?id=<?php echo $order['id']; ?>" class="text-[var(--accent-color)] hover:underline">View</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
    </div>
    </section>
                            <?php endif; ?>
    
    <?php if (!empty($activity)): ?>
    <section class="frosted-glass rounded-xl p-6 bg-[var(--background-secondary)] shadow-lg">
    <h2 class="text-[var(--text-primary)] text-2xl font-semibold mb-6">Activity Logs</h2>
    <div class="space-y-6">
    <?php foreach ($activity as $index => $log): ?>
        <div class="flex items-start timeline-item">
            <div class="flex flex-col items-center mr-4">
                <?php 
                    $icon_class = 'bg-[var(--accent-color)] bg-opacity-20 text-[var(--accent-color)]';
                    $icon = 'person';
                    
                    if (strpos($log['action'], 'order') !== false) {
                        $icon_class = 'bg-blue-500 bg-opacity-20 text-blue-400';
                        $icon = 'shopping_cart';
                    } elseif (strpos($log['action'], 'login') !== false) {
                        $icon_class = 'bg-green-500 bg-opacity-20 text-green-400';
                        $icon = 'login';
                    } elseif (strpos($log['action'], 'update') !== false) {
                        $icon_class = 'bg-yellow-500 bg-opacity-20 text-yellow-400';
                        $icon = 'edit';
                    }
                ?>
                <div class="flex items-center justify-center size-10 rounded-full <?php echo $icon_class; ?>">
                    <span class="material-icons-outlined"><?php echo $icon; ?></span>
                                    </div>
                <?php if ($index < count($activity) - 1): ?>
                    <div class="w-px h-8 bg-[var(--border-color)] timeline-line"></div>
                                                <?php endif; ?>
                                            </div>
            <div>
                <p class="text-[var(--text-primary)] text-base font-medium"><?php echo ucfirst(str_replace('_', ' ', $log['action'])); ?></p>
                <p class="text-[var(--text-secondary)] text-sm"><?php echo date('Y-m-d H:i', strtotime($log['created_at'])); ?></p>
                <?php if ($log['details']): ?>
                    <p class="text-[var(--text-secondary)] text-sm mt-1"><?php echo htmlspecialchars($log['details']); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
    </section>
                            <?php endif; ?>
    </main>
                        </div>
                    </div>
                </div>
            </div>
</body>
</html>