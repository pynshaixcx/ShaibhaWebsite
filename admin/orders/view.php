<?php
$page_title = "View Order";
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Get order ID
$order_id = intval($_GET['id'] ?? 0);
if (!$order_id) {
    redirect('index.php');
}

// Get order data
$order = fetchOne("
    SELECT o.*, c.first_name, c.last_name, c.email, c.phone 
    FROM orders o 
    LEFT JOIN customers c ON o.customer_id = c.id 
    WHERE o.id = ?
", [$order_id]);

if (!$order) {
    redirect('index.php');
}

// Get order items
$order_items = fetchAll("
    SELECT oi.*, p.slug, p.status as product_status 
    FROM order_items oi 
    LEFT JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
", [$order_id]);

// Get order history
$order_history = fetchAll("
    SELECT * FROM activity_log 
    WHERE action IN ('order_status_updated', 'order_payment_updated') 
    AND description LIKE ? 
    ORDER BY created_at DESC
", ["%Order {$order['order_number']}%"]);

$error = '';
$success = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $new_status = sanitizeInput($_POST['order_status'] ?? '');
        $new_payment_status = sanitizeInput($_POST['payment_status'] ?? '');
        $admin_notes = sanitizeInput($_POST['admin_notes'] ?? '');
        
        // Update order status
        $sql = "UPDATE orders SET order_status = ?, payment_status = ?, admin_notes = ?, updated_at = CURRENT_TIMESTAMP";
        $params = [$new_status, $new_payment_status, $admin_notes];
        
        // Add shipped_at date if status is shipped
        if ($new_status === 'shipped' && $order['order_status'] !== 'shipped') {
            $sql .= ", shipped_at = CURRENT_TIMESTAMP";
        }
        
        // Add delivered_at date if status is delivered
        if ($new_status === 'delivered' && $order['order_status'] !== 'delivered') {
            $sql .= ", delivered_at = CURRENT_TIMESTAMP";
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $order_id;
        
        $result = executeQuery($sql, $params);
        
        if ($result) {
            // Log activity
            if ($new_status !== $order['order_status']) {
                logActivity('admin', getCurrentAdminId(), 'order_status_updated', "Order {$order['order_number']} status updated from {$order['order_status']} to {$new_status}");
            }
            
            if ($new_payment_status !== $order['payment_status']) {
                logActivity('admin', getCurrentAdminId(), 'order_payment_updated', "Order {$order['order_number']} payment status updated from {$order['payment_status']} to {$new_payment_status}");
            }
            
            $success = 'Order updated successfully!';
            
            // Refresh order data
            $order = fetchOne("
                SELECT o.*, c.first_name, c.last_name, c.email, c.phone 
                FROM orders o 
                LEFT JOIN customers c ON o.customer_id = c.id 
                WHERE o.id = ?
            ", [$order_id]);
            
            // Refresh order history
            $order_history = fetchAll("
                SELECT * FROM activity_log 
                WHERE action IN ('order_status_updated', 'order_payment_updated') 
                AND description LIKE ? 
                ORDER BY created_at DESC
            ", ["%Order {$order['order_number']}%"]);
        } else {
            $error = 'Failed to update order. Please try again.';
        }
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=Noto+Sans:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="../../images/favicon.svg">
    
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
        .glass-card {
            background-color: var(--background-secondary);
            backdrop-filter: blur(var(--blur-intensity));
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
        }
        .btn {
            @apply flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition-colors duration-200;
        }
        .btn-primary {
            @apply frosted-glass bg-white/10 text-[var(--text-primary)] hover:bg-white/20;
        }
        .btn-outline {
            @apply frosted-glass bg-[var(--background-secondary)] text-[var(--text-primary)] hover:bg-white/10;
        }
        .btn-danger {
            @apply bg-red-500/10 text-red-400 hover:bg-red-500/20;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-black via-slate-900 to-black">
<div class="relative flex size-full min-h-screen flex-col bg-cover bg-center bg-fixed" style='font-family: Inter, "Noto Sans", sans-serif;'>
<div class="relative flex size-full min-h-screen flex-col dark group/design-root overflow-x-hidden">
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
            <a class="sidebar-item active flex items-center gap-3 px-3 py-2.5 text-[var(--text-primary)] transition-colors duration-200" href="index.php">
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
        <div class="mx-auto max-w-6xl">
            <div class="flex flex-col gap-6">
                <!-- Page Header -->
                <div class="flex items-center justify-between">
                    <h1 class="text-[var(--text-primary)] text-3xl font-bold leading-tight">Order #<?php echo htmlspecialchars($order['order_number']); ?></h1>
                    <div class="flex gap-2">
                        <a class="btn btn-outline" href="index.php">
                            <span class="material-icons-outlined">arrow_back</span>
                            <span>Back to Orders</span>
                        </a>
                        <a class="btn btn-primary" href="update-status.php?id=<?php echo $order_id; ?>">
                            <span class="material-icons-outlined">edit</span>
                            <span>Update Status</span>
                        </a>
                    </div>
                </div>

                <?php if ($error): ?>
                    <div class="p-4 rounded-xl bg-red-500/30 text-red-300">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="p-4 rounded-xl bg-green-500/30 text-green-300">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Order Details Card -->
                <div class="frosted-glass rounded-xl p-6 bg-[var(--background-secondary)] shadow-lg border border-[var(--border-color)]">
                    <div class="mb-6 flex items-center justify-between border-b border-[var(--border-color)] pb-4">
                        <div>
                            <h2 class="text-xl font-bold text-[var(--text-primary)]">Order #<?php echo htmlspecialchars($order['order_number']); ?></h2>
                            <p class="text-sm text-[var(--text-secondary)]">Placed on: <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                        </div>
                        <div class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            <?php 
                                switch($order['order_status']) {
                                    case 'pending': echo 'bg-yellow-500/30 text-yellow-300'; break;
                                    case 'confirmed': echo 'bg-blue-500/30 text-blue-300'; break;
                                    case 'processing': echo 'bg-purple-500/30 text-purple-300'; break;
                                    case 'shipped': echo 'bg-blue-500/30 text-blue-300'; break;
                                    case 'delivered': echo 'bg-green-500/30 text-green-300'; break;
                                    case 'cancelled': echo 'bg-red-500/30 text-red-300'; break;
                                    default: echo 'bg-gray-500/30 text-gray-300';
                                }
                            ?>">
                                        <?php echo ucfirst($order['order_status']); ?>
                                </div>
                            </div>
                            
                    <!-- Customer and Shipping Info -->
                    <div class="mb-6 grid gap-6 md:grid-cols-2">
                        <div>
                            <h3 class="mb-2 text-sm font-medium uppercase text-[var(--text-secondary)]">Customer Information</h3>
                            <div class="rounded-lg frosted-glass p-4 bg-[var(--background-primary)] border border-[var(--border-color)]">
                                <?php if ($order['customer_id']): ?>
                                    <p class="font-medium text-[var(--text-primary)]">
                                        <a href="../customers/view.php?id=<?php echo $order['customer_id']; ?>" class="hover:underline">
                                            <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?>
                                        </a>
                                    </p>
                                <?php else: ?>
                                    <p class="font-medium text-[var(--text-primary)]">
                                        <?php echo htmlspecialchars($order['billing_first_name'] . ' ' . $order['billing_last_name']); ?>
                                        <span class="ml-2 text-xs px-2 py-1 rounded-full bg-gray-500/30 text-gray-300">Guest</span>
                                    </p>
                                <?php endif; ?>
                                <p class="text-sm text-[var(--text-secondary)]"><?php echo htmlspecialchars($order['customer_email']); ?></p>
                                <p class="text-sm text-[var(--text-secondary)]"><?php echo htmlspecialchars($order['customer_phone']); ?></p>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="mb-2 text-sm font-medium uppercase text-[var(--text-secondary)]">Shipping Address</h3>
                            <div class="rounded-lg frosted-glass p-4 bg-[var(--background-primary)] border border-[var(--border-color)]">
                                <p class="font-medium text-[var(--text-primary)]"><?php echo htmlspecialchars($order['shipping_first_name'] . ' ' . $order['shipping_last_name']); ?></p>
                                <p class="text-sm text-[var(--text-secondary)]"><?php echo htmlspecialchars($order['shipping_address_line_1']); ?></p>
                                <?php if ($order['shipping_address_line_2']): ?>
                                    <p class="text-sm text-[var(--text-secondary)]"><?php echo htmlspecialchars($order['shipping_address_line_2']); ?></p>
                                <?php endif; ?>
                                <p class="text-sm text-[var(--text-secondary)]">
                                    <?php echo htmlspecialchars($order['shipping_city'] . ', ' . $order['shipping_state'] . ' ' . $order['shipping_postal_code']); ?>
                                </p>
                                <p class="text-sm text-[var(--text-secondary)]"><?php echo htmlspecialchars($order['shipping_country']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="order-view-layout">
                    <div class="order-main">
                        <!-- Order Items -->
                        <div class="mb-6">
                            <h3 class="mb-2 text-sm font-medium uppercase text-[var(--text-secondary)]">Order Items</h3>
                            <div class="overflow-hidden rounded-xl frosted-glass border border-[var(--border-color)] bg-[var(--background-primary)] shadow-lg">
                                <table class="min-w-full">
                                    <thead class="bg-white/5">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Product</th>
                                            <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Price</th>
                                            <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Quantity</th>
                                            <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[var(--border-color)]">
                                        <?php foreach ($order_items as $item): ?>
                                            <tr class="hover:bg-white/5 transition-colors">
                                                <td class="whitespace-nowrap px-6 py-4">
                                                    <div class="flex items-center">
                                                        <div class="size-10 flex-shrink-0 rounded-md bg-[var(--background-secondary)] border border-[var(--border-color)] overflow-hidden">
                                                            <img src="../../images/products/<?php echo htmlspecialchars($item['product_image'] ?? 'product_1.jpg'); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="w-full h-full object-cover">
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-[var(--text-primary)]">
                                                                <?php if ($item['product_slug']): ?>
                                                                    <a href="../../shop/product.php?slug=<?php echo $item['product_slug']; ?>" target="_blank" class="hover:underline">
                                                                        <?php echo htmlspecialchars($item['product_name']); ?>
                                                                    </a>
                                                                <?php else: ?>
                                                                    <?php echo htmlspecialchars($item['product_name']); ?>
                                                                <?php endif; ?>
                                                            </div>
                                                            <?php if ($item['product_brand']): ?>
                                                                <div class="text-xs text-[var(--text-secondary)]"><?php echo htmlspecialchars($item['product_brand']); ?></div>
                                                            <?php endif; ?>
                                                            <?php if ($item['product_size'] || $item['product_color']): ?>
                                                                <div class="text-xs text-[var(--text-secondary)]">
                                                                    <?php 
                                                                        $attributes = [];
                                                                        if ($item['product_size']) $attributes[] = "Size: " . htmlspecialchars($item['product_size']);
                                                                        if ($item['product_color']) $attributes[] = "Color: " . htmlspecialchars($item['product_color']);
                                                                        echo implode(" | ", $attributes);
                                                                    ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-[var(--text-secondary)]"><?php echo formatPrice($item['price']); ?></td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-[var(--text-secondary)]"><?php echo $item['quantity']; ?></td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-[var(--text-primary)]"><?php echo formatPrice($item['total']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="mb-6 border-t border-[var(--border-color)] pt-4">
                            <div class="flex justify-between py-2">
                                <span class="text-sm text-[var(--text-secondary)]">Subtotal</span>
                                <span class="text-sm text-[var(--text-primary)]"><?php echo formatPrice($order['subtotal']); ?></span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-sm text-[var(--text-secondary)]">Shipping</span>
                                <span class="text-sm text-[var(--text-primary)]">
                                                <?php if ($order['shipping_cost'] > 0): ?>
                                                    <?php echo formatPrice($order['shipping_cost']); ?>
                                                <?php else: ?>
                                        <span class="text-green-400">FREE</span>
                                                <?php endif; ?>
                                </span>
                            </div>
                            <div class="flex justify-between border-t border-[var(--border-color)] py-2 mt-2">
                                <span class="text-base font-medium text-[var(--text-primary)]">Total</span>
                                <span class="text-base font-medium text-[var(--text-primary)]"><?php echo formatPrice($order['total_amount']); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="order-sidebar">
                        <!-- Customer Information -->
                        <div class="customer-card">
                            <h2 class="text-[var(--text-primary)]">Customer Information</h2>
                            <div class="customer-info">
                                <h3 class="text-[var(--text-primary)]">
                                    <?php if ($order['customer_id']): ?>
                                        <a href="../customers/view.php?id=<?php echo $order['customer_id']; ?>" class="text-blue-400 hover:underline">
                                            <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?>
                                        </a>
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($order['billing_first_name'] . ' ' . $order['billing_last_name']); ?>
                                        <span class="guest-label text-xs px-2 py-1 rounded-full bg-gray-500/30 text-gray-300 ml-2">Guest</span>
                                    <?php endif; ?>
                                </h3>
                                <p class="text-[var(--text-primary)]"><strong>Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                                <p class="text-[var(--text-primary)]"><strong>Phone:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
                            </div>
                        </div>
                        
                        <!-- Addresses -->
                        <div class="addresses-card">
                            <h2 class="text-[var(--text-primary)]">Addresses</h2>
                            <div class="address-grid">
                                <div class="address-item">
                                    <h3 class="text-[var(--text-primary)]">Billing Address</h3>
                                    <div class="address text-[var(--text-primary)]">
                                        <p><?php echo htmlspecialchars($order['billing_first_name'] . ' ' . $order['billing_last_name']); ?></p>
                                        <p><?php echo htmlspecialchars($order['billing_address_line_1']); ?></p>
                                        <?php if ($order['billing_address_line_2']): ?>
                                            <p><?php echo htmlspecialchars($order['billing_address_line_2']); ?></p>
                                        <?php endif; ?>
                                        <p><?php echo htmlspecialchars($order['billing_city'] . ', ' . $order['billing_state'] . ' ' . $order['billing_postal_code']); ?></p>
                                        <p><?php echo htmlspecialchars($order['billing_country']); ?></p>
                                    </div>
                                </div>
                                
                                <div class="address-item">
                                    <h3 class="text-[var(--text-primary)]">Shipping Address</h3>
                                    <div class="address text-[var(--text-primary)]">
                                        <p><?php echo htmlspecialchars($order['shipping_first_name'] . ' ' . $order['shipping_last_name']); ?></p>
                                        <p><?php echo htmlspecialchars($order['shipping_address_line_1']); ?></p>
                                        <?php if ($order['shipping_address_line_2']): ?>
                                            <p><?php echo htmlspecialchars($order['shipping_address_line_2']); ?></p>
                                        <?php endif; ?>
                                        <p><?php echo htmlspecialchars($order['shipping_city'] . ', ' . $order['shipping_state'] . ' ' . $order['shipping_postal_code']); ?></p>
                                        <p><?php echo htmlspecialchars($order['shipping_country']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Notes -->
                        <?php if ($order['notes']): ?>
                            <div class="notes-card">
                                <h2 class="text-[var(--text-primary)]">Customer Notes</h2>
                                <div class="notes-content text-[var(--text-primary)]">
                                    <p><?php echo nl2br(htmlspecialchars($order['notes'])); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Order Timeline -->
                        <div class="mb-6">
                            <h3 class="mb-2 text-sm font-medium uppercase text-[var(--text-secondary)]">Order Timeline</h3>
                            <div class="rounded-lg frosted-glass p-4 bg-[var(--background-primary)] border border-[var(--border-color)] shadow-lg">
                                <div class="space-y-4">
                                    <div class="flex">
                                        <div class="mr-4 flex flex-col items-center">
                                            <div class="h-4 w-4 rounded-full bg-purple-500 shadow-md shadow-purple-500/20"></div>
                                            <div class="h-full w-0.5 bg-[var(--border-color)]"></div>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-[var(--text-primary)]">Order Placed</p>
                                            <p class="text-xs text-[var(--text-secondary)]"><?php echo date('F j, Y - g:i A', strtotime($order['created_at'])); ?></p>
                                            <p class="mt-1 text-sm text-[var(--text-secondary)]">Order was received and is being reviewed.</p>
                                        </div>
                                    </div>
                                    
                                    <?php foreach ($order_history as $index => $history): ?>
                                        <div class="flex">
                                            <div class="mr-4 flex flex-col items-center">
                                                <div class="h-4 w-4 rounded-full 
                                                    <?php 
                                                        if (strpos($history['action'], 'status_updated') !== false) {
                                                            echo 'bg-blue-500 shadow-md shadow-blue-500/20';
                                                        } else {
                                                            echo 'bg-green-500 shadow-md shadow-green-500/20';
                                                        }
                                                    ?>"></div>
                                                <div class="h-full w-0.5 <?php echo ($index === count($order_history) - 1) ? 'bg-transparent' : 'bg-[var(--border-color)]'; ?>"></div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-[var(--text-primary)]">
                                                    <?php 
                                                        if ($history['action'] === 'order_status_updated') {
                                                            echo 'Order Status Updated';
                                                        } else if ($history['action'] === 'order_payment_updated') {
                                                            echo 'Payment Status Updated';
                                                        }
                                                    ?>
                                                </p>
                                                <p class="text-xs text-[var(--text-secondary)]"><?php echo date('F j, Y - g:i A', strtotime($history['created_at'])); ?></p>
                                                <p class="mt-1 text-sm text-[var(--text-secondary)]"><?php echo htmlspecialchars($history['description']); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Admin Notes and Status Update -->
                        <div class="mb-6">
                            <h3 class="mb-2 text-sm font-medium uppercase text-[var(--text-secondary)]">Admin Notes & Status Update</h3>
                            <div class="rounded-lg frosted-glass p-4 bg-[var(--background-primary)] border border-[var(--border-color)] shadow-lg">
                                <form method="POST" class="space-y-4">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                <input type="hidden" name="update_status" value="1">
                                
                                    <div>
                                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2" for="admin_notes">Admin Notes</label>
                                        <textarea class="frosted-glass w-full rounded-lg border border-[var(--border-color)] bg-[var(--background-primary)] px-4 py-2 text-[var(--text-primary)] focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" id="admin_notes" name="admin_notes" rows="4" placeholder="Add notes about this order..."><?php echo htmlspecialchars($order['admin_notes'] ?? ''); ?></textarea>
                                </div>
                                
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2" for="order_status">Order Status</label>
                                            <select class="frosted-glass w-full rounded-lg border border-[var(--border-color)] bg-[var(--background-primary)] px-4 py-2 text-[var(--text-primary)] focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" id="order_status" name="order_status">
                                            <option value="pending" <?php echo $order['order_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="confirmed" <?php echo $order['order_status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                            <option value="processing" <?php echo $order['order_status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                            <option value="shipped" <?php echo $order['order_status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                            <option value="delivered" <?php echo $order['order_status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                            <option value="cancelled" <?php echo $order['order_status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </div>
                                    
                                        <div>
                                            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2" for="payment_status">Payment Status</label>
                                            <select class="frosted-glass w-full rounded-lg border border-[var(--border-color)] bg-[var(--background-primary)] px-4 py-2 text-[var(--text-primary)] focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" id="payment_status" name="payment_status">
                                            <option value="pending" <?php echo $order['payment_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="paid" <?php echo $order['payment_status'] === 'paid' ? 'selected' : ''; ?>>Paid</option>
                                            <option value="failed" <?php echo $order['payment_status'] === 'failed' ? 'selected' : ''; ?>>Failed</option>
                                        </select>
                                    </div>
                                </div>
                                
                                    <div class="flex justify-end">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="material-icons-outlined">save</span>
                                            <span>Update Order</span>
                                        </button>
                                    </div>
                                </form>
                                        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add any JavaScript functionality here
});
</script>
</body>
</html>