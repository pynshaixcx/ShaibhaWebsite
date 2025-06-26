<?php
$page_title = "Update Order Status";
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
$order = fetchOne("SELECT * FROM orders WHERE id = ?", [$order_id]);
if (!$order) {
    redirect('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $new_status = sanitizeInput($_POST['order_status'] ?? '');
        $new_payment_status = sanitizeInput($_POST['payment_status'] ?? '');
        $tracking_number = sanitizeInput($_POST['tracking_number'] ?? '');
        $admin_notes = sanitizeInput($_POST['admin_notes'] ?? '');
        
        // Validation
        if (empty($new_status) || empty($new_payment_status)) {
            $error = 'Please select both order status and payment status';
        } else {
            // Update order status
            $sql = "UPDATE orders SET order_status = ?, payment_status = ?, tracking_number = ?, admin_notes = ?, updated_at = CURRENT_TIMESTAMP";
            $params = [$new_status, $new_payment_status, $tracking_number, $admin_notes];
            
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
                
                $success = 'Order status updated successfully!';
                
                // Redirect after a short delay
                header("refresh:2;url=view.php?id={$order_id}");
            } else {
                $error = 'Failed to update order status. Please try again.';
            }
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
        .form-input {
            @apply frosted-glass w-full rounded-lg border border-[var(--border-color)] bg-[var(--background-primary)] px-4 py-2 text-[var(--text-primary)] focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500;
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
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <h1 class="text-[var(--text-primary)] text-3xl font-bold leading-tight">Update Order Status</h1>
                <div class="flex gap-2">
                    <a class="frosted-glass flex items-center gap-2 rounded-lg px-4 py-2 bg-[var(--background-secondary)] text-[var(--text-primary)] text-sm font-semibold transition-colors duration-200 hover:bg-white/10" href="view.php?id=<?php echo $order_id; ?>">
                        <span class="material-icons-outlined">arrow_back</span>
                        <span>Back to Order</span>
                        </a>
                    </div>
                </div>

                <?php if ($error): ?>
                <div class="mt-4 p-4 rounded-xl bg-red-500/30 text-red-300">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                <div class="mt-4 p-4 rounded-xl bg-green-500/30 text-green-300">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
            <div class="frosted-glass rounded-xl p-6 bg-[var(--background-secondary)] shadow-lg border border-[var(--border-color)] mt-6">
                <div class="mb-6 flex items-center justify-between border-b border-[var(--border-color)] pb-4">
                    <div>
                        <h2 class="text-[var(--text-primary)] text-xl font-bold">Order #<?php echo htmlspecialchars($order['order_number']); ?></h2>
                        <p class="text-[var(--text-secondary)] text-sm">Placed on: <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                            </div>
                    <div class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
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

                <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2" for="order_status">Order Status</label>
                        <select class="form-input" id="order_status" name="order_status" required>
                                        <option value="pending" <?php echo $order['order_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="confirmed" <?php echo $order['order_status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                        <option value="processing" <?php echo $order['order_status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="shipped" <?php echo $order['order_status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="delivered" <?php echo $order['order_status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="cancelled" <?php echo $order['order_status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </div>
                                
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2" for="payment_status">Payment Status</label>
                        <select class="form-input" id="payment_status" name="payment_status" required>
                                        <option value="pending" <?php echo $order['payment_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="paid" <?php echo $order['payment_status'] === 'paid' ? 'selected' : ''; ?>>Paid</option>
                                        <option value="failed" <?php echo $order['payment_status'] === 'failed' ? 'selected' : ''; ?>>Failed</option>
                                    </select>
                            </div>
                            
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2" for="tracking_number">Tracking Number</label>
                        <input type="text" id="tracking_number" name="tracking_number" class="form-input" value="<?php echo htmlspecialchars($order['tracking_number'] ?? ''); ?>">
                            </div>
                            
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2" for="admin_notes">Admin Notes</label>
                        <textarea class="form-input" id="admin_notes" name="admin_notes" rows="4" placeholder="Add any notes about this status update"><?php echo htmlspecialchars($order['admin_notes'] ?? ''); ?></textarea>
                            </div>
                            
                    <div class="flex justify-end gap-3">
                        <a class="btn btn-outline" href="view.php?id=<?php echo $order_id; ?>">
                            <span class="material-icons-outlined">cancel</span>
                            <span>Cancel</span>
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <span class="material-icons-outlined">check_circle</span>
                            <span>Update Status</span>
                        </button>
                            </div>
                        </form>
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