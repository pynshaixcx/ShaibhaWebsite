<?php
$page_title = "Sales Reports";
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Get report parameters
$report_type = $_GET['type'] ?? 'daily';
$date_from = $_GET['date_from'] ?? date('Y-m-d', strtotime('-30 days'));
$date_to = $_GET['date_to'] ?? date('Y-m-d');

// Build query based on report type
$sql = "";
$params = [];

switch ($report_type) {
    case 'daily':
        $sql = "SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as order_count,
                    SUM(total_amount) as total_sales,
                    AVG(total_amount) as average_order_value
                FROM orders
                WHERE created_at BETWEEN ? AND ? AND payment_status = 'paid'
                GROUP BY DATE(created_at)
                ORDER BY date DESC";
        $params = [$date_from, $date_to . ' 23:59:59'];
        break;
        
    case 'weekly':
        $sql = "SELECT 
                    YEARWEEK(created_at, 1) as year_week,
                    MIN(DATE(created_at)) as week_start,
                    COUNT(*) as order_count,
                    SUM(total_amount) as total_sales,
                    AVG(total_amount) as average_order_value
                FROM orders
                WHERE created_at BETWEEN ? AND ? AND payment_status = 'paid'
                GROUP BY YEARWEEK(created_at, 1)
                ORDER BY year_week DESC";
        $params = [$date_from, $date_to . ' 23:59:59'];
        break;
        
    case 'monthly':
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    DATE_FORMAT(created_at, '%M %Y') as month_name,
                    COUNT(*) as order_count,
                    SUM(total_amount) as total_sales,
                    AVG(total_amount) as average_order_value
                FROM orders
                WHERE created_at BETWEEN ? AND ? AND payment_status = 'paid'
                GROUP BY DATE_FORMAT(created_at, '%Y-%m'), DATE_FORMAT(created_at, '%M %Y')
                ORDER BY month DESC";
        $params = [$date_from, $date_to . ' 23:59:59'];
        break;
        
    case 'product':
        $sql = "SELECT 
                    p.id,
                    p.name,
                    p.sku,
                    COUNT(oi.id) as order_count,
                    SUM(oi.quantity) as quantity_sold,
                    SUM(oi.total) as total_sales
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                JOIN orders o ON oi.order_id = o.id
                WHERE o.created_at BETWEEN ? AND ? AND o.payment_status = 'paid'
                GROUP BY p.id, p.name, p.sku
                ORDER BY quantity_sold DESC";
        $params = [$date_from, $date_to . ' 23:59:59'];
        break;
}

$report_data = fetchAll($sql, $params);

// Calculate totals
$total_orders = 0;
$total_sales = 0;
$total_items = 0;

foreach ($report_data as $row) {
    $total_orders += $row['order_count'];
    $total_sales += $row['total_sales'] ?? 0;
    $total_items += $row['quantity_sold'] ?? 0;
}

// Get average order value
$average_order_value = $total_orders > 0 ? $total_sales / $total_orders : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <link crossorigin="" href="https://fonts.gstatic.com/" rel="preconnect"/>
    <link as="style" href="https://fonts.googleapis.com/css2?display=swap&family=Inter%3Awght%40400%3B500%3B600%3B700%3B900&family=Noto+Sans%3Awght%40400%3B500%3B700%3B900" onload="this.rel='stylesheet'" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>
    <title><?php echo $page_title; ?> - ShaiBha Admin Panel</title>
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
            --chart-bar-color: #4A4A4A;
            --chart-bar-hover-color: #6A6A6A;
            --button-active-bg: #ffffff;
            --button-active-text: #111111;
            --button-inactive-bg: rgba(54, 54, 54, 0.5);
            --button-inactive-text: #a0a0a0;
            --button-hover-bg: rgba(74, 74, 74, 0.7);
            --positive-color: #34C759;
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
            .chart-text {
                fill: var(--text-secondary);
                font-size: 12px;
                font-weight: 500;
            }
            .active-nav-link {
                color: var(--text-primary);
                position: relative;
            }
            .active-nav-link::after {
                content: '';
                position: absolute;
                left: 0;
                bottom: -4px;
                width: 100%;
                height: 2px;
                background-color: var(--text-primary);
            }
            .tooltip {
                position: absolute;
                background-color: var(--background-primary);
                color: var(--text-primary);
                padding: 8px 12px;
                border-radius: 6px;
                font-size: 13px;
                font-weight: 500;
                box-shadow: 0 4px 12px rgba(0,0,0,0.4);
                opacity: 0;
                transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
                pointer-events: none;
                transform: translate(-50%, -110%);white-space: nowrap;
                border: 1px solid var(--border-color);
                backdrop-filter: blur(4px);
                -webkit-backdrop-filter: blur(4px);
                z-index: 10;
            }
            .grid-line {
                stroke: var(--border-color);
                stroke-width: 0.5;
                stroke-dasharray: 3 3;
            }
            .chart-bar {
                fill: var(--chart-bar-color);
                transition: fill 0.2s ease-in-out;
                cursor: pointer;
            }
            .chart-bar:hover {
                fill: var(--chart-bar-hover-color);
            }
            .chart-bar-group:hover .tooltip {
                opacity: 1;
                transform: translate(-50%, -130%);
            }
            .time-filter-button {
              padding: 6px 12px;
              border-radius: 6px;
              font-size: 13px;
              font-weight: 500;
              transition: background-color 0.2s, color 0.2s;
            }
            .time-filter-button.active {
              background-color: var(--button-active-bg);
              color: var(--button-active-text);
            }
            .time-filter-button.inactive {
              background-color: var(--button-inactive-bg);
              color: var(--button-inactive-text);
            }
            .time-filter-button:hover:not(.active) {
              background-color: var(--button-hover-bg);
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
                        <div class="mb-8">
                            <p class="text-[var(--text-primary)] text-3xl font-bold leading-tight">Sales Reports</p>
                </div>

                <!-- Report Filters -->
                        <div class="frosted-glass rounded-xl p-6 bg-[var(--background-secondary)] shadow-lg mb-8">
                            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="type" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Report Type</label>
                                    <select id="type" name="type" onchange="this.form.submit()" class="w-full rounded-lg border-none bg-[rgba(48,48,48,0.7)] text-[var(--text-primary)] text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="daily" <?php echo $report_type === 'daily' ? 'selected' : ''; ?>>Daily Sales</option>
                                <option value="weekly" <?php echo $report_type === 'weekly' ? 'selected' : ''; ?>>Weekly Sales</option>
                                <option value="monthly" <?php echo $report_type === 'monthly' ? 'selected' : ''; ?>>Monthly Sales</option>
                                <option value="product" <?php echo $report_type === 'product' ? 'selected' : ''; ?>>Product Sales</option>
                            </select>
                        </div>
                        
                                <div>
                                    <label for="date_from" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Date From</label>
                                    <input type="date" id="date_from" name="date_from" value="<?php echo htmlspecialchars($date_from); ?>" class="w-full rounded-lg border-none bg-[rgba(48,48,48,0.7)] text-[var(--text-primary)] text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                                <div>
                                    <label for="date_to" class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Date To</label>
                                    <input type="date" id="date_to" name="date_to" value="<?php echo htmlspecialchars($date_to); ?>" class="w-full rounded-lg border-none bg-[rgba(48,48,48,0.7)] text-[var(--text-primary)] text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                                <div class="flex items-end space-x-2 md:col-span-3">
                                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700">Generate Report</button>
                                    <a href="sales.php" class="rounded-lg border border-[var(--border-color)] bg-transparent px-4 py-2.5 text-sm font-medium text-[var(--text-secondary)] hover:bg-[rgba(48,48,48,0.7)]">Reset</a>
                        </div>
                    </form>
                </div>

                        <!-- Report Summary Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div class="frosted-glass rounded-xl p-6 bg-[var(--background-secondary)] shadow-lg">
                                <div class="flex items-center justify-between mb-4">
                                    <p class="text-[var(--text-secondary)] text-sm font-medium">Total Orders</p>
                                    <span class="material-icons-outlined text-blue-400 bg-blue-400/10 p-2 rounded-full">shopping_cart</span>
                        </div>
                                <p class="text-[var(--text-primary)] text-2xl font-bold"><?php echo $total_orders; ?></p>
                                <p class="text-[var(--text-secondary)] text-sm mt-2">
                                    <?php echo date('M j, Y', strtotime($date_from)); ?> - <?php echo date('M j, Y', strtotime($date_to)); ?>
                                </p>
                    </div>
                    
                            <div class="frosted-glass rounded-xl p-6 bg-[var(--background-secondary)] shadow-lg">
                                <div class="flex items-center justify-between mb-4">
                                    <p class="text-[var(--text-secondary)] text-sm font-medium">Total Sales</p>
                                    <span class="material-icons-outlined text-green-400 bg-green-400/10 p-2 rounded-full">payments</span>
                        </div>
                                <p class="text-[var(--text-primary)] text-2xl font-bold"><?php echo formatPrice($total_sales); ?></p>
                                <p class="text-[var(--text-secondary)] text-sm mt-2">
                                    <?php echo date('M j, Y', strtotime($date_from)); ?> - <?php echo date('M j, Y', strtotime($date_to)); ?>
                                </p>
                    </div>
                    
                            <div class="frosted-glass rounded-xl p-6 bg-[var(--background-secondary)] shadow-lg">
                                <div class="flex items-center justify-between mb-4">
                                    <p class="text-[var(--text-secondary)] text-sm font-medium">Average Order Value</p>
                                    <span class="material-icons-outlined text-purple-400 bg-purple-400/10 p-2 rounded-full">analytics</span>
                        </div>
                                <p class="text-[var(--text-primary)] text-2xl font-bold"><?php echo formatPrice($average_order_value); ?></p>
                                <p class="text-[var(--text-secondary)] text-sm mt-2">
                                    <?php echo date('M j, Y', strtotime($date_from)); ?> - <?php echo date('M j, Y', strtotime($date_to)); ?>
                            </p>
                    </div>
                </div>

                        <!-- Report Data Table -->
                        <div class="frosted-glass rounded-xl bg-[var(--background-secondary)] shadow-lg overflow-hidden">
                            <div class="p-6 border-b border-[var(--border-color)]">
                                <h2 class="text-[var(--text-primary)] text-xl font-semibold">
                            <?php
                            switch ($report_type) {
                                case 'daily':
                                    echo 'Daily Sales Report';
                                    break;
                                case 'weekly':
                                    echo 'Weekly Sales Report';
                                    break;
                                case 'monthly':
                                    echo 'Monthly Sales Report';
                                    break;
                                case 'product':
                                    echo 'Product Sales Report';
                                    break;
                            }
                            ?>
                        </h2>
                        </div>
                            <div class="overflow-x-auto">
                                <?php if (!empty($report_data)): ?>
                                    <table class="min-w-full">
                                        <thead class="bg-white/5">
                                <tr>
                                    <?php if ($report_type === 'daily'): ?>
                                                    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Date</th>
                                                    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Orders</th>
                                                    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Sales</th>
                                                    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Average Order</th>
                                    <?php elseif ($report_type === 'weekly'): ?>
                                                    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Week Starting</th>
                                                    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Orders</th>
                                                    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Sales</th>
                                                    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Average Order</th>
                                    <?php elseif ($report_type === 'monthly'): ?>
                                                    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Month</th>
                                                    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Orders</th>
                                                    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Sales</th>
                                                    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Average Order</th>
                                    <?php elseif ($report_type === 'product'): ?>
                                                    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Product</th>
                                                    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">SKU</th>
                                                    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Orders</th>
                                                    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Quantity</th>
                                                    <th class="px-6 py-4 text-left text-[var(--text-secondary)] text-xs font-medium uppercase tracking-wider">Sales</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                                        <tbody class="divide-y divide-[var(--border-color)]">
                                    <?php foreach ($report_data as $row): ?>
                                        <tr>
                                            <?php if ($report_type === 'daily'): ?>
                                                        <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm"><?php echo date('M j, Y', strtotime($row['date'])); ?></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm"><?php echo $row['order_count']; ?></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm"><?php echo formatPrice($row['total_sales']); ?></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm"><?php echo formatPrice($row['average_order_value']); ?></td>
                                            <?php elseif ($report_type === 'weekly'): ?>
                                                        <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm"><?php echo date('M j, Y', strtotime($row['week_start'])); ?></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm"><?php echo $row['order_count']; ?></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm"><?php echo formatPrice($row['total_sales']); ?></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm"><?php echo formatPrice($row['average_order_value']); ?></td>
                                            <?php elseif ($report_type === 'monthly'): ?>
                                                        <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm"><?php echo $row['month_name']; ?></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm"><?php echo $row['order_count']; ?></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm"><?php echo formatPrice($row['total_sales']); ?></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm"><?php echo formatPrice($row['average_order_value']); ?></td>
                                            <?php elseif ($report_type === 'product'): ?>
                                                        <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm">
                                                            <a href="../products/edit.php?id=<?php echo $row['id']; ?>" class="text-blue-400 hover:underline"><?php echo htmlspecialchars($row['name']); ?></a>
                                                </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm"><?php echo htmlspecialchars($row['sku']); ?></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm"><?php echo $row['order_count']; ?></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm"><?php echo $row['quantity_sold']; ?></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-[var(--text-primary)] text-sm"><?php echo formatPrice($row['total_sales']); ?></td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <div class="px-6 py-12 text-center">
                                        <p class="text-[var(--text-secondary)] text-sm">No data available for the selected period</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($report_type !== 'product' && !empty($report_data)): ?>
                            <!-- Visual Chart for non-product reports -->
                            <div class="frosted-glass rounded-xl p-6 bg-[var(--background-secondary)] shadow-lg mt-8">
                                <h2 class="text-[var(--text-primary)] text-xl font-semibold mb-4">Sales Visualization</h2>
                                <div class="bg-[var(--background-primary)] rounded-xl p-4 overflow-x-auto">
                                    <div class="min-h-[300px] min-w-[800px]">
                                        <!-- Sales chart will be rendered here using JavaScript -->
                                        <div id="sales-chart" class="w-full h-full"></div>
                                    </div>
                                </div>
                    </div>
                        <?php endif; ?>
                        
                    </main>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($report_type !== 'product' && !empty($report_data)): ?>
                // Prepare data for chart
                const labels = [];
                const data = [];
                
                <?php foreach ($report_data as $row): ?>
                    <?php if ($report_type === 'daily'): ?>
                        labels.push('<?php echo date('M j', strtotime($row['date'])); ?>');
                    <?php elseif ($report_type === 'weekly'): ?>
                        labels.push('<?php echo date('M j', strtotime($row['week_start'])); ?>');
                    <?php elseif ($report_type === 'monthly'): ?>
                        labels.push('<?php echo $row['month_name']; ?>');
                    <?php endif; ?>
                    data.push(<?php echo $row['total_sales']; ?>);
                <?php endforeach; ?>
                
                // Reverse the data to show chronological order
                labels.reverse();
                data.reverse();
                
                // Create chart
                const ctx = document.getElementById('sales-chart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Sales',
                            data: data,
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: 'rgba(171, 171, 171, 0.8)',
                                    callback: function(value) {
                                        return '₹' + value.toLocaleString();
                                    }
                                },
                                grid: {
                                    color: 'rgba(48, 48, 48, 0.5)',
                                }
                            },
                            x: {
                                ticks: {
                                    color: 'rgba(171, 171, 171, 0.8)',
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return '₹' + context.parsed.y.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>