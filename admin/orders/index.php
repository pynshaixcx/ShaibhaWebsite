<?php
$page_title = "Order Management";
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Get orders with pagination
$page = max(1, intval($_GET['page'] ?? 1));
$orders_per_page = 20;
$offset = ($page - 1) * $orders_per_page;

// Get sorting parameters
$sort = $_GET['sort'] ?? 'created_at';
$order = $_GET['order'] ?? 'desc';

// Get filter parameters
$status = $_GET['status'] ?? '';
$payment_status = $_GET['payment_status'] ?? '';
$search = $_GET['search'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Build query
$sql = "SELECT o.*, c.first_name, c.last_name, c.email 
        FROM orders o 
        LEFT JOIN customers c ON o.customer_id = c.id 
        WHERE 1=1";
$params = [];

if ($status) {
    $sql .= " AND o.order_status = ?";
    $params[] = $status;
}

if ($payment_status) {
    $sql .= " AND o.payment_status = ?";
    $params[] = $payment_status;
}

if ($search) {
    $sql .= " AND (o.order_number LIKE ? OR o.customer_email LIKE ? OR o.customer_phone LIKE ? OR 
              CONCAT(o.billing_first_name, ' ', o.billing_last_name) LIKE ?)";
    $search_term = "%{$search}%";
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
}

if ($date_from) {
    $sql .= " AND DATE(o.created_at) >= ?";
    $params[] = $date_from;
}

if ($date_to) {
    $sql .= " AND DATE(o.created_at) <= ?";
    $params[] = $date_to;
}

// Count total orders for pagination
$count_sql = str_replace("SELECT o.*, c.first_name, c.last_name, c.email", "SELECT COUNT(*) as total", $sql);
$total_orders = fetchOne($count_sql, $params)['total'];
$total_pages = ceil($total_orders / $orders_per_page);

// Add sorting and pagination
$valid_sort_columns = ['order_number', 'created_at', 'total_amount', 'order_status', 'payment_status'];
$sort = in_array($sort, $valid_sort_columns) ? $sort : 'created_at';
$order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

$sql .= " ORDER BY o.{$sort} {$order}";
$sql .= " LIMIT ? OFFSET ?";
$params[] = $orders_per_page;
$params[] = $offset;

$orders = fetchAll($sql, $params);
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
          --sidebar-glow: 0 0 20px 5px rgba(128, 128, 255, 0.2);}
        .frosted-glass {
          backdrop-filter: blur(var(--blur-intensity));
          -webkit-backdrop-filter: blur(var(--blur-intensity));
        }
        .sidebar-item:hover, .sidebar-item.active {
          background-color: var(--background-secondary) !important;
          border-radius: 0.5rem;}
        .icon-button:hover {
          background-color: rgba(75, 75, 75, 0.7) !important;
        }
        .sidebar-glow-effect {
          box-shadow: var(--sidebar-glow);
        }
        .table th {
          font-weight: 600;color: var(--text-primary);
        }
        .table td {
          color: var(--text-secondary);
        }
        .table tr:not(:last-child) {
          border-bottom: 1px solid var(--border-color);
        }
        .table tr:hover {
          background-color: rgba(255, 255, 255, 0.03);}
        .status-badge {
          padding: 0.25rem 0.75rem;
          border-radius: 9999px;font-size: 0.75rem;font-weight: 500;
        }
        .status-shipped {
          background-color: rgba(59, 130, 246, 0.2);color: #60a5fa;
        }
        .status-delivered {
          background-color: rgba(34, 197, 94, 0.2);color: #4ade80;
        }
        .status-processing {
          background-color: rgba(249, 115, 22, 0.2);color: #fb923c;
        }
        .status-pending {
          background-color: rgba(245, 158, 11, 0.2);color: #fbbf24;
        }
        .status-confirmed {
          background-color: rgba(16, 185, 129, 0.2);color: #34d399;
        }
        .status-cancelled {
          background-color: rgba(239, 68, 68, 0.2);color: #f87171;
        }
        .status-paid {
          background-color: rgba(34, 197, 94, 0.2);color: #4ade80;
        }
        .status-failed {
          background-color: rgba(239, 68, 68, 0.2);color: #f87171;
        }
        ::-webkit-scrollbar {
          width: 8px;
          height: 8px;
        }
        ::-webkit-scrollbar-track {
          background: var(--background-primary);
        }
        ::-webkit-scrollbar-thumb {
          background: #555;
          border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
          background: #777;
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

  <main class="flex-1 p-6 md:p-10 overflow-y-auto">
  <div class="mb-8">
  <p class="text-[var(--text-primary)] text-3xl font-bold leading-tight">All Orders</p>
  </div>
  <div class="mb-8">
  <h2 class="text-[var(--text-primary)] text-2xl font-semibold mb-4">Quick Actions</h2>
  <div class="flex flex-wrap gap-4">
  <a href="create.php" class="frosted-glass flex items-center justify-center rounded-lg h-12 px-6 bg-white/10 text-[var(--text-primary)] text-sm font-semibold transition-colors duration-200 hover:bg-white/20">
  <span class="material-icons-outlined mr-2">add_circle_outline</span>
  <span>Create New Order</span>
  </a>
  <button type="button" onclick="toggleFilters()" class="frosted-glass flex items-center justify-center rounded-lg h-12 px-6 bg-[var(--background-secondary)] text-[var(--text-primary)] text-sm font-semibold transition-colors duration-200 hover:bg-[rgba(75,75,75,0.7)]">
  <span class="material-icons-outlined mr-2">filter_list</span>
  <span>Filter Orders</span>
  </button>
  </div>
                </div>

  <!-- Filter Controls -->
  <div id="filterControls" class="mb-8 frosted-glass p-6 rounded-xl border border-[var(--border-color)] hidden">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
            <label for="search" class="block text-sm font-medium text-[var(--text-secondary)] mb-1">Search</label>
            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Order #, email, phone..." class="w-full rounded-lg border-none bg-[rgba(48,48,48,0.7)] text-[var(--text-primary)] text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        
        <div>
            <label for="status" class="block text-sm font-medium text-[var(--text-secondary)] mb-1">Order Status</label>
            <select id="status" name="status" class="w-full rounded-lg border-none bg-[rgba(48,48,48,0.7)] text-[var(--text-primary)] text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">All Statuses</option>
                                <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="confirmed" <?php echo $status === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="processing" <?php echo $status === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="shipped" <?php echo $status === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                <option value="delivered" <?php echo $status === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $status === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        
        <div>
            <label for="payment_status" class="block text-sm font-medium text-[var(--text-secondary)] mb-1">Payment Status</label>
            <select id="payment_status" name="payment_status" class="w-full rounded-lg border-none bg-[rgba(48,48,48,0.7)] text-[var(--text-primary)] text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">All Statuses</option>
                                <option value="pending" <?php echo $payment_status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="paid" <?php echo $payment_status === 'paid' ? 'selected' : ''; ?>>Paid</option>
                                <option value="failed" <?php echo $payment_status === 'failed' ? 'selected' : ''; ?>>Failed</option>
                            </select>
                        </div>
                        
        <div>
            <label for="date_from" class="block text-sm font-medium text-[var(--text-secondary)] mb-1">Date From</label>
            <input type="date" id="date_from" name="date_from" value="<?php echo htmlspecialchars($date_from); ?>" class="w-full rounded-lg border-none bg-[rgba(48,48,48,0.7)] text-[var(--text-primary)] text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        
        <div>
            <label for="date_to" class="block text-sm font-medium text-[var(--text-secondary)] mb-1">Date To</label>
            <input type="date" id="date_to" name="date_to" value="<?php echo htmlspecialchars($date_to); ?>" class="w-full rounded-lg border-none bg-[rgba(48,48,48,0.7)] text-[var(--text-primary)] text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        
        <div class="flex items-end gap-2">
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Apply Filters</button>
            <a href="index.php" class="rounded-lg border border-[var(--border-color)] bg-transparent px-4 py-2 text-sm font-medium text-[var(--text-secondary)] hover:bg-[rgba(48,48,48,0.7)]">Reset</a>
                        </div>
                    </form>
                </div>

  <div>
  <h2 class="text-[var(--text-primary)] text-2xl font-semibold mb-4">Order List</h2>
  <div class="frosted-glass @container overflow-hidden rounded-xl border border-[var(--border-color)] bg-[var(--background-primary)] shadow-lg">
  <div class="overflow-x-auto">
  <table class="min-w-full">
  <thead class="bg-white/5">
  <tr>
  <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">
    <a href="?sort=order_number&order=<?php echo ($sort === 'order_number' && $order === 'ASC') ? 'DESC' : 'ASC'; ?><?php echo $status ? '&status=' . htmlspecialchars($status) : ''; ?><?php echo $payment_status ? '&payment_status=' . htmlspecialchars($payment_status) : ''; ?><?php echo $search ? '&search=' . htmlspecialchars($search) : ''; ?><?php echo $date_from ? '&date_from=' . htmlspecialchars($date_from) : ''; ?><?php echo $date_to ? '&date_to=' . htmlspecialchars($date_to) : ''; ?>" class="flex items-center">
      Order ID
                                        <?php if ($sort === 'order_number'): ?>
        <span class="ml-1"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
                                        <?php endif; ?>
    </a>
                                    </th>
  <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Customer</th>
  <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">
    <a href="?sort=created_at&order=<?php echo ($sort === 'created_at' && $order === 'ASC') ? 'DESC' : 'ASC'; ?><?php echo $status ? '&status=' . htmlspecialchars($status) : ''; ?><?php echo $payment_status ? '&payment_status=' . htmlspecialchars($payment_status) : ''; ?><?php echo $search ? '&search=' . htmlspecialchars($search) : ''; ?><?php echo $date_from ? '&date_from=' . htmlspecialchars($date_from) : ''; ?><?php echo $date_to ? '&date_to=' . htmlspecialchars($date_to) : ''; ?>" class="flex items-center">
                                        Date
                                        <?php if ($sort === 'created_at'): ?>
        <span class="ml-1"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
                                        <?php endif; ?>
    </a>
                                    </th>
  <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">
    <a href="?sort=order_status&order=<?php echo ($sort === 'order_status' && $order === 'ASC') ? 'DESC' : 'ASC'; ?><?php echo $status ? '&status=' . htmlspecialchars($status) : ''; ?><?php echo $payment_status ? '&payment_status=' . htmlspecialchars($payment_status) : ''; ?><?php echo $search ? '&search=' . htmlspecialchars($search) : ''; ?><?php echo $date_from ? '&date_from=' . htmlspecialchars($date_from) : ''; ?><?php echo $date_to ? '&date_to=' . htmlspecialchars($date_to) : ''; ?>" class="flex items-center">
                                        Status
                                        <?php if ($sort === 'order_status'): ?>
        <span class="ml-1"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
      <?php endif; ?>
    </a>
  </th>
  <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">
    <a href="?sort=total_amount&order=<?php echo ($sort === 'total_amount' && $order === 'ASC') ? 'DESC' : 'ASC'; ?><?php echo $status ? '&status=' . htmlspecialchars($status) : ''; ?><?php echo $payment_status ? '&payment_status=' . htmlspecialchars($payment_status) : ''; ?><?php echo $search ? '&search=' . htmlspecialchars($search) : ''; ?><?php echo $date_from ? '&date_from=' . htmlspecialchars($date_from) : ''; ?><?php echo $date_to ? '&date_to=' . htmlspecialchars($date_to) : ''; ?>" class="flex items-center">
      Total
      <?php if ($sort === 'total_amount'): ?>
        <span class="ml-1"><?php echo $order === 'ASC' ? '↑' : '↓'; ?></span>
                                        <?php endif; ?>
    </a>
                                    </th>
  <th class="px-6 py-4 text-right text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
  <tbody class="divide-y divide-[var(--border-color)]">
                                <?php if (!empty($orders)): ?>
                                    <?php foreach ($orders as $order_item): ?>
                                        <tr>
              <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm font-medium">#<?php echo htmlspecialchars($order_item['order_number']); ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm">
                  <?php
                      $customer_name = $order_item['first_name'] && $order_item['last_name'] 
                          ? htmlspecialchars($order_item['first_name'] . ' ' . $order_item['last_name'])
                          : htmlspecialchars($order_item['billing_first_name'] . ' ' . $order_item['billing_last_name']);
                  ?>
                                                <?php if ($order_item['customer_id']): ?>
                      <a href="../customers/view.php?id=<?php echo $order_item['customer_id']; ?>" class="text-blue-400 hover:underline"><?php echo $customer_name; ?></a>
                                                <?php else: ?>
                      <?php echo $customer_name; ?>
                                                <?php endif; ?>
                                            </td>
              <td class="px-6 py-4 whitespace-nowrap text-[var(--text-secondary)] text-sm"><?php echo date('Y-m-d', strtotime($order_item['created_at'])); ?></td>
              <td class="px-6 py-4 whitespace-nowrap">
                  <span class="status-badge status-<?php echo strtolower($order_item['order_status']); ?>"><?php echo ucfirst($order_item['order_status']); ?></span>
                                            </td>
              <td class="px-6 py-4 whitespace-nowrap text-[var(--text-secondary)] text-sm"><?php echo formatPrice($order_item['total_amount']); ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                  <div class="flex justify-end space-x-2">
                      <a href="view.php?id=<?php echo $order_item['id']; ?>" class="text-[var(--text-primary)] hover:text-blue-300 font-medium text-xs">View</a>
                      <span class="text-gray-500">|</span>
                      <a href="update-status.php?id=<?php echo $order_item['id']; ?>" class="text-[var(--text-primary)] hover:text-blue-300 font-medium text-xs">Update Status</a>
                  </div>
              </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
          <td colspan="6" class="px-6 py-4 text-center text-[var(--text-secondary)] text-sm">No orders found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
  <div class="px-6 py-4 border-t border-[var(--border-color)] flex items-center justify-between">
  <p class="text-sm text-[var(--text-secondary)]">Showing <span class="font-medium"><?php echo $offset + 1; ?></span> to <span class="font-medium"><?php echo min($offset + $orders_per_page, $total_orders); ?></span> of <span class="font-medium"><?php echo $total_orders; ?></span> results</p>
  <div class="flex gap-2">
                            <?php if ($page > 1): ?>
      <a href="?page=<?php echo $page - 1; ?><?php echo $status ? '&status=' . htmlspecialchars($status) : ''; ?><?php echo $payment_status ? '&payment_status=' . htmlspecialchars($payment_status) : ''; ?><?php echo $search ? '&search=' . htmlspecialchars($search) : ''; ?><?php echo $date_from ? '&date_from=' . htmlspecialchars($date_from) : ''; ?><?php echo $date_to ? '&date_to=' . htmlspecialchars($date_to) : ''; ?><?php echo $sort !== 'created_at' ? '&sort=' . htmlspecialchars($sort) : ''; ?><?php echo $order !== 'desc' ? '&order=' . htmlspecialchars($order) : ''; ?>" class="px-3 py-1.5 rounded-md border border-[var(--border-color)] bg-[#2a2a2a] text-sm font-medium text-[var(--text-secondary)] hover:bg-[#363636] hover:text-[var(--text-primary)] transition-colors duration-150">Previous</a>
    <?php else: ?>
      <button class="px-3 py-1.5 rounded-md border border-[var(--border-color)] bg-[#2a2a2a] text-sm font-medium text-[var(--text-secondary)] opacity-50 cursor-not-allowed" disabled>Previous</button>
                            <?php endif; ?>
                            
                            <?php if ($page < $total_pages): ?>
      <a href="?page=<?php echo $page + 1; ?><?php echo $status ? '&status=' . htmlspecialchars($status) : ''; ?><?php echo $payment_status ? '&payment_status=' . htmlspecialchars($payment_status) : ''; ?><?php echo $search ? '&search=' . htmlspecialchars($search) : ''; ?><?php echo $date_from ? '&date_from=' . htmlspecialchars($date_from) : ''; ?><?php echo $date_to ? '&date_to=' . htmlspecialchars($date_to) : ''; ?><?php echo $sort !== 'created_at' ? '&sort=' . htmlspecialchars($sort) : ''; ?><?php echo $order !== 'desc' ? '&order=' . htmlspecialchars($order) : ''; ?>" class="px-3 py-1.5 rounded-md border border-[var(--border-color)] bg-[#2a2a2a] text-sm font-medium text-[var(--text-secondary)] hover:bg-[#363636] hover:text-[var(--text-primary)] transition-colors duration-150">Next</a>
    <?php else: ?>
      <button class="px-3 py-1.5 rounded-md border border-[var(--border-color)] bg-[#2a2a2a] text-sm font-medium text-[var(--text-secondary)] opacity-50 cursor-not-allowed" disabled>Next</button>
                            <?php endif; ?>
  </div>
  </div>
  </div>
  
  <!-- Mobile View for Orders -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:hidden gap-4 mt-6">
  <?php if (!empty($orders)): ?>
    <?php foreach ($orders as $order_item): ?>
      <div class="frosted-glass rounded-xl border border-[var(--border-color)] p-4 flex flex-col gap-3">
        <div class="flex justify-between items-start">
          <span class="text-lg font-semibold text-[var(--text-primary)]">#<?php echo htmlspecialchars($order_item['order_number']); ?></span>
          <span class="status-badge status-<?php echo strtolower($order_item['order_status']); ?>"><?php echo ucfirst($order_item['order_status']); ?></span>
        </div>
        <p class="text-sm">
          <span class="font-medium text-[var(--text-secondary)]">Customer:</span> 
          <?php
            $customer_name = $order_item['first_name'] && $order_item['last_name'] 
              ? htmlspecialchars($order_item['first_name'] . ' ' . $order_item['last_name'])
              : htmlspecialchars($order_item['billing_first_name'] . ' ' . $order_item['billing_last_name']);
            echo $customer_name;
          ?>
        </p>
        <p class="text-sm"><span class="font-medium text-[var(--text-secondary)]">Date:</span> <?php echo date('Y-m-d', strtotime($order_item['created_at'])); ?></p>
        <p class="text-sm"><span class="font-medium text-[var(--text-secondary)]">Total:</span> <?php echo formatPrice($order_item['total_amount']); ?></p>
        <div class="flex gap-2 mt-2">
            <a href="view.php?id=<?php echo $order_item['id']; ?>" class="flex-1 text-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-colors duration-150">View</a>
            <a href="update-status.php?id=<?php echo $order_item['id']; ?>" class="flex-1 text-center px-4 py-2 rounded-lg bg-[var(--background-secondary)] text-white text-sm font-semibold hover:bg-white/10 transition-colors duration-150">Update Status</a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="frosted-glass rounded-xl border border-[var(--border-color)] p-4 col-span-2 text-center text-[var(--text-secondary)]">
      No orders found
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
  </div>
  </div>
  
  <script>
  function toggleFilters() {
    const filterControls = document.getElementById('filterControls');
    if (filterControls.classList.contains('hidden')) {
      filterControls.classList.remove('hidden');
    } else {
      filterControls.classList.add('hidden');
    }
  }
  
  // Show filters if any filter is applied
  document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const hasFilters = urlParams.has('status') || urlParams.has('payment_status') || 
                      urlParams.has('search') || urlParams.has('date_from') || 
                      urlParams.has('date_to');
    
    if (hasFilters) {
      const filterControls = document.getElementById('filterControls');
      filterControls.classList.remove('hidden');
    }
  });
  </script>
</body>
</html>