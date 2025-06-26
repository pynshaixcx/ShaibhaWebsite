<?php
$page_title = "Create New Order";
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Get all customers for dropdown
$customers = fetchAll("SELECT id, first_name, last_name, email, phone FROM customers ORDER BY first_name, last_name");

// Get all active products for selection
$products = fetchAll("
    SELECT p.*, 
           (SELECT i.image_path FROM product_images i WHERE i.product_id = p.id AND i.is_primary = 1 LIMIT 1) AS primary_image
    FROM products p 
    WHERE p.status = 'active' 
    ORDER BY p.name
");

$error = '';
$success = '';
$order_items = [];
$customer_addresses = [];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $customer_id = !empty($_POST['customer_id']) ? intval($_POST['customer_id']) : null;
        $customer_email = sanitizeInput($_POST['customer_email'] ?? '');
        $customer_phone = sanitizeInput($_POST['customer_phone'] ?? '');

        // Billing information
        $billing_first_name = sanitizeInput($_POST['billing_first_name'] ?? '');
        $billing_last_name = sanitizeInput($_POST['billing_last_name'] ?? '');
        $billing_address_line_1 = sanitizeInput($_POST['billing_address_line_1'] ?? '');
        $billing_address_line_2 = sanitizeInput($_POST['billing_address_line_2'] ?? '');
        $billing_city = sanitizeInput($_POST['billing_city'] ?? '');
        $billing_state = sanitizeInput($_POST['billing_state'] ?? '');
        $billing_postal_code = sanitizeInput($_POST['billing_postal_code'] ?? '');
        $billing_country = sanitizeInput($_POST['billing_country'] ?? 'India');

        // Shipping information
        $shipping_first_name = sanitizeInput($_POST['shipping_first_name'] ?? '');
        $shipping_last_name = sanitizeInput($_POST['shipping_last_name'] ?? '');
        $shipping_address_line_1 = sanitizeInput($_POST['shipping_address_line_1'] ?? '');
        $shipping_address_line_2 = sanitizeInput($_POST['shipping_address_line_2'] ?? '');
        $shipping_city = sanitizeInput($_POST['shipping_city'] ?? '');
        $shipping_state = sanitizeInput($_POST['shipping_state'] ?? '');
        $shipping_postal_code = sanitizeInput($_POST['shipping_postal_code'] ?? '');
        $shipping_country = sanitizeInput($_POST['shipping_country'] ?? 'India');

        // Order details
        $order_status = sanitizeInput($_POST['order_status'] ?? 'pending');
        $payment_status = sanitizeInput($_POST['payment_status'] ?? 'pending');
        $payment_method = sanitizeInput($_POST['payment_method'] ?? 'cod');
        $notes = sanitizeInput($_POST['notes'] ?? '');
        $admin_notes = sanitizeInput($_POST['admin_notes'] ?? '');
        
        // Calculate totals
        $subtotal = 0;
        $shipping_cost = floatval(sanitizeInput($_POST['shipping_cost'] ?? '0'));
        $tax_amount = 0; // For future implementation
        $discount_amount = 0; // For future implementation

        // Order items
        $items = [];
        
        if (isset($_POST['product_id']) && is_array($_POST['product_id'])) {
            foreach ($_POST['product_id'] as $key => $product_id) {
                if (empty($product_id)) continue;
                
                $product_id = intval($product_id);
                $quantity = max(1, intval($_POST['quantity'][$key] ?? 1));
                $price = floatval($_POST['price'][$key] ?? 0);
                
                // Get product details
                $product = fetchOne("SELECT * FROM products WHERE id = ?", [$product_id]);
                
                if (!$product) {
                    $error = 'One or more selected products are invalid.';
                    break;
                }
                
                // Use submitted price or product price
                $price = ($price > 0) ? $price : ((float)$product['sale_price'] > 0 ? (float)$product['sale_price'] : (float)$product['price']);
                $total = $price * $quantity;
                
                $items[] = [
                    'product_id' => $product_id,
                    'product_name' => $product['name'],
                    'product_sku' => $product['sku'],
                    'product_slug' => $product['slug'],
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total,
                    'product_brand' => $product['brand'] ?? '',
                ];
                
                $subtotal += $total;
            }
        }
        
        $total_amount = $subtotal + $shipping_cost - $discount_amount;
        
        // Validation
        $validation_errors = [];
        
        if (empty($customer_email)) {
            $validation_errors[] = 'Customer email is required.';
        } elseif (!validateEmail($customer_email)) {
            $validation_errors[] = 'Customer email is invalid.';
        }
        
        if (empty($customer_phone)) {
            $validation_errors[] = 'Customer phone is required.';
        }
        
        if (empty($billing_first_name) || empty($billing_last_name)) {
            $validation_errors[] = 'Billing name is required.';
        }
        
        if (empty($billing_address_line_1)) {
            $validation_errors[] = 'Billing address is required.';
        }
        
        if (empty($billing_city) || empty($billing_state) || empty($billing_postal_code)) {
            $validation_errors[] = 'Complete billing address is required.';
        }
        
        if (empty($shipping_first_name) || empty($shipping_last_name)) {
            $validation_errors[] = 'Shipping name is required.';
        }
        
        if (empty($shipping_address_line_1)) {
            $validation_errors[] = 'Shipping address is required.';
        }
        
        if (empty($shipping_city) || empty($shipping_state) || empty($shipping_postal_code)) {
            $validation_errors[] = 'Complete shipping address is required.';
        }
        
        if (empty($items)) {
            $validation_errors[] = 'At least one product is required.';
        }
        
        if (!empty($validation_errors)) {
            $error = implode('<br>', $validation_errors);
            $order_items = $items; // Keep items for form redisplay
        } else {
            // Generate order number
            $order_number = generateOrderNumber();
            
            // Begin transaction
            $pdo->beginTransaction();
            
            try {
                // Create order
                $sql = "INSERT INTO orders (
                    order_number, customer_id, customer_email, customer_phone,
                    billing_first_name, billing_last_name, billing_address_line_1, billing_address_line_2,
                    billing_city, billing_state, billing_postal_code, billing_country,
                    shipping_first_name, shipping_last_name, shipping_address_line_1, shipping_address_line_2,
                    shipping_city, shipping_state, shipping_postal_code, shipping_country,
                    subtotal, shipping_cost, tax_amount, discount_amount, total_amount,
                    payment_method, payment_status, order_status, notes, admin_notes
                ) VALUES (
                    ?, ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?
                )";
                
                $params = [
                    $order_number, $customer_id, $customer_email, $customer_phone,
                    $billing_first_name, $billing_last_name, $billing_address_line_1, $billing_address_line_2,
                    $billing_city, $billing_state, $billing_postal_code, $billing_country,
                    $shipping_first_name, $shipping_last_name, $shipping_address_line_1, $shipping_address_line_2,
                    $shipping_city, $shipping_state, $shipping_postal_code, $shipping_country,
                    $subtotal, $shipping_cost, $tax_amount, $discount_amount, $total_amount,
                    $payment_method, $payment_status, $order_status, $notes, $admin_notes
                ];
                
                executeQuery($sql, $params);
                $order_id = getLastInsertId();
                
                // Insert order items
                foreach ($items as $item) {
                    $sql = "INSERT INTO order_items (
                        order_id, product_id, product_name, product_sku, product_slug,
                        quantity, price, total, product_brand
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    $params = [
                        $order_id, $item['product_id'], $item['product_name'], $item['product_sku'], $item['product_slug'],
                        $item['quantity'], $item['price'], $item['total'], $item['product_brand']
                    ];
                    
                    executeQuery($sql, $params);
                }
                
                // Log activity
                logActivity('admin', getCurrentAdminId(), 'order_created', "Order {$order_number} created with " . count($items) . " items");
                
                // Commit transaction
                $pdo->commit();
                
                $success = 'Order created successfully! Redirecting to order details...';
                
                // Redirect after a short delay
                header("refresh:2;url=view.php?id={$order_id}");
                
            } catch (Exception $e) {
                // Rollback transaction
                $pdo->rollBack();
                
                // Log error
                error_log("Error creating order: " . $e->getMessage());
                
                $error = 'Failed to create order. Please try again.';
            }
        }
    }
}

// Load customer addresses if customer selected
if (isset($_GET['customer_id']) && intval($_GET['customer_id']) > 0) {
    $customer_id = intval($_GET['customer_id']);
    $customer = fetchOne("SELECT * FROM customers WHERE id = ?", [$customer_id]);
    
    if ($customer) {
        // Get customer addresses
        $customer_addresses = fetchAll("SELECT * FROM customer_addresses WHERE customer_id = ?", [$customer_id]);
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
        .form-select {
            @apply frosted-glass rounded-lg border border-[var(--border-color)] bg-[var(--background-primary)] px-4 py-2 text-[var(--text-primary)] focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500;
        }
        .form-group {
            @apply mb-4;
        }
        .form-label {
            @apply mb-1 block text-sm font-medium text-[var(--text-secondary)];
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
            @apply bg-red-500/10 text-red-300 hover:bg-red-500/20;
        }
        .section-title {
            @apply mb-4 text-lg font-medium text-[var(--text-primary)];
        }
        .card {
            @apply frosted-glass rounded-xl border border-[var(--border-color)] bg-[var(--background-primary)];
        }
        .card-header {
            @apply border-b border-[var(--border-color)] p-4;
        }
        .card-body {
            @apply p-6;
        }
        .separator {
            @apply my-6 border-t border-[var(--border-color)];
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
                <h1 class="text-[var(--text-primary)] text-3xl font-bold leading-tight">Create New Order</h1>
                <div class="flex gap-2">
                    <a class="frosted-glass flex items-center gap-2 rounded-lg px-4 py-2 bg-[var(--background-secondary)] text-[var(--text-primary)] text-sm font-semibold transition-colors duration-200 hover:bg-white/10" href="index.php">
                        <span class="material-icons-outlined">arrow_back</span>
                        <span>Back to Orders</span>
                    </a>
                </div>
            </div>

            <?php if ($error): ?>
                <div class="mt-4 p-4 rounded-xl bg-red-500/30 text-red-300">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="mt-4 p-4 rounded-xl bg-green-500/30 text-green-300">
                    <?php echo $success; ?>
                </div>

                <form method="POST" action="" class="mt-8">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
                    <div class="card mb-6">
                        <div class="card-header">
                            <h2 class="text-lg font-medium text-[var(--text-primary)]">Customer Information</h2>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label" for="customer_id">Select Customer (Optional)</label>
                                <select class="form-select w-full" id="customer_id" name="customer_id" onchange="loadCustomerDetails(this.value)">
                                    <option value="">-- Guest Order --</option>
                                    <?php foreach ($customers as $customer): ?>
                                        <option value="<?php echo $customer['id']; ?>" <?php echo (isset($_GET['customer_id']) && $customer['id'] == $_GET['customer_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name'] . ' (' . $customer['email'] . ')'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label class="form-label" for="customer_email">Customer Email <span class="text-red-500">*</span></label>
                                    <input type="email" id="customer_email" name="customer_email" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="customer_phone">Customer Phone <span class="text-red-500">*</span></label>
                                    <input type="text" id="customer_phone" name="customer_phone" class="form-input" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Billing Information -->
                        <div class="card mb-6">
                            <div class="card-header">
                                <h2 class="text-lg font-medium text-[var(--text-primary)]">Billing Information</h2>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($customer_addresses)): ?>
                                    <div class="form-group">
                                        <label class="form-label" for="billing_address_id">Select Saved Address</label>
                                        <select class="form-select w-full" id="billing_address_id" onchange="fillBillingAddress(this.value)">
                                            <option value="">-- Select Address --</option>
                                            <?php foreach ($customer_addresses as $address): ?>
                                                <option value="<?php echo $address['id']; ?>" 
                                                    data-first-name="<?php echo htmlspecialchars($address['first_name']); ?>"
                                                    data-last-name="<?php echo htmlspecialchars($address['last_name']); ?>"
                                                    data-address-line-1="<?php echo htmlspecialchars($address['address_line_1']); ?>"
                                                    data-address-line-2="<?php echo htmlspecialchars($address['address_line_2'] ?? ''); ?>"
                                                    data-city="<?php echo htmlspecialchars($address['city']); ?>"
                                                    data-state="<?php echo htmlspecialchars($address['state']); ?>"
                                                    data-postal-code="<?php echo htmlspecialchars($address['postal_code']); ?>"
                                                    data-country="<?php echo htmlspecialchars($address['country']); ?>"
                                                    data-is-default="<?php echo $address['is_default']; ?>">
                                                    <?php echo htmlspecialchars($address['first_name'] . ' ' . $address['last_name'] . ' - ' . $address['address_line_1'] . ', ' . $address['city']); ?>
                                                    <?php echo $address['is_default'] ? ' (Default)' : ''; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <label class="form-label" for="billing_first_name">First Name <span class="text-red-500">*</span></label>
                                        <input type="text" id="billing_first_name" name="billing_first_name" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="billing_last_name">Last Name <span class="text-red-500">*</span></label>
                                        <input type="text" id="billing_last_name" name="billing_last_name" class="form-input" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label" for="billing_address_line_1">Address Line 1 <span class="text-red-500">*</span></label>
                                    <input type="text" id="billing_address_line_1" name="billing_address_line_1" class="form-input" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label" for="billing_address_line_2">Address Line 2</label>
                                    <input type="text" id="billing_address_line_2" name="billing_address_line_2" class="form-input">
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="form-group">
                                        <label class="form-label" for="billing_city">City <span class="text-red-500">*</span></label>
                                        <input type="text" id="billing_city" name="billing_city" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="billing_state">State <span class="text-red-500">*</span></label>
                                        <input type="text" id="billing_state" name="billing_state" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="billing_postal_code">Postal Code <span class="text-red-500">*</span></label>
                                        <input type="text" id="billing_postal_code" name="billing_postal_code" class="form-input" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label" for="billing_country">Country <span class="text-red-500">*</span></label>
                                    <input type="text" id="billing_country" name="billing_country" class="form-input" value="India" required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Shipping Information -->
                        <div class="card mb-6">
                            <div class="card-header flex items-center justify-between">
                                <h2 class="text-lg font-medium text-[var(--text-primary)]">Shipping Information</h2>
                                <div class="flex items-center">
                                    <input type="checkbox" id="same_as_billing" class="form-checkbox h-4 w-4 rounded border-gray-600 bg-gray-700 text-blue-500 focus:ring-transparent focus:ring-offset-transparent" onchange="copyBillingToShipping()">
                                    <label for="same_as_billing" class="ml-2 text-sm text-[var(--text-secondary)]">Same as billing</label>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($customer_addresses)): ?>
                                    <div class="form-group">
                                        <label class="form-label" for="shipping_address_id">Select Saved Address</label>
                                        <select class="form-select w-full" id="shipping_address_id" onchange="fillShippingAddress(this.value)">
                                            <option value="">-- Select Address --</option>
                                            <?php foreach ($customer_addresses as $address): ?>
                                                <option value="<?php echo $address['id']; ?>" 
                                                    data-first-name="<?php echo htmlspecialchars($address['first_name']); ?>"
                                                    data-last-name="<?php echo htmlspecialchars($address['last_name']); ?>"
                                                    data-address-line-1="<?php echo htmlspecialchars($address['address_line_1']); ?>"
                                                    data-address-line-2="<?php echo htmlspecialchars($address['address_line_2'] ?? ''); ?>"
                                                    data-city="<?php echo htmlspecialchars($address['city']); ?>"
                                                    data-state="<?php echo htmlspecialchars($address['state']); ?>"
                                                    data-postal-code="<?php echo htmlspecialchars($address['postal_code']); ?>"
                                                    data-country="<?php echo htmlspecialchars($address['country']); ?>"
                                                    data-is-default="<?php echo $address['is_default']; ?>">
                                                    <?php echo htmlspecialchars($address['first_name'] . ' ' . $address['last_name'] . ' - ' . $address['address_line_1'] . ', ' . $address['city']); ?>
                                                    <?php echo $address['is_default'] ? ' (Default)' : ''; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <label class="form-label" for="shipping_first_name">First Name <span class="text-red-500">*</span></label>
                                        <input type="text" id="shipping_first_name" name="shipping_first_name" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="shipping_last_name">Last Name <span class="text-red-500">*</span></label>
                                        <input type="text" id="shipping_last_name" name="shipping_last_name" class="form-input" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label" for="shipping_address_line_1">Address Line 1 <span class="text-red-500">*</span></label>
                                    <input type="text" id="shipping_address_line_1" name="shipping_address_line_1" class="form-input" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label" for="shipping_address_line_2">Address Line 2</label>
                                    <input type="text" id="shipping_address_line_2" name="shipping_address_line_2" class="form-input">
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="form-group">
                                        <label class="form-label" for="shipping_city">City <span class="text-red-500">*</span></label>
                                        <input type="text" id="shipping_city" name="shipping_city" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="shipping_state">State <span class="text-red-500">*</span></label>
                                        <input type="text" id="shipping_state" name="shipping_state" class="form-input" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="shipping_postal_code">Postal Code <span class="text-red-500">*</span></label>
                                        <input type="text" id="shipping_postal_code" name="shipping_postal_code" class="form-input" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label" for="shipping_country">Country <span class="text-red-500">*</span></label>
                                    <input type="text" id="shipping_country" name="shipping_country" class="form-input" value="India" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Products -->
                    <div class="card mb-6">
                        <div class="card-header flex items-center justify-between">
                            <h2 class="text-lg font-medium text-[var(--text-primary)]">Order Products</h2>
                            <button type="button" class="frosted-glass rounded-md p-2 bg-white/10 text-white hover:bg-white/20" onclick="addProductRow()">
                                <span class="material-icons-outlined">add</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b border-[var(--border-color)]">
                                            <th class="px-4 py-2 text-left text-xs font-medium text-[var(--text-secondary)] uppercase tracking-wider">Product</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-[var(--text-secondary)] uppercase tracking-wider">Price</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-[var(--text-secondary)] uppercase tracking-wider">Quantity</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-[var(--text-secondary)] uppercase tracking-wider">Total</th>
                                            <th class="px-4 py-2 text-center text-xs font-medium text-[var(--text-secondary)] uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productRows">
                                        <!-- Product rows will be added here -->
                                        <tr id="noProductsRow">
                                            <td colspan="5" class="px-4 py-4 text-center text-sm text-[var(--text-secondary)]">
                                                No products added to this order yet. Click the + button to add products.
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-t border-[var(--border-color)]">
                                            <td colspan="3" class="px-4 py-3 text-right text-sm font-medium text-[var(--text-secondary)]">Subtotal:</td>
                                            <td class="px-4 py-3 text-left text-sm font-medium text-[var(--text-primary)]">
                                                <span id="subtotal">₹0.00</span>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h2 class="text-lg font-medium text-[var(--text-primary)]">Order Details</h2>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <div class="col-span-1">
                                    <div class="form-group">
                                        <label class="form-label" for="order_status">Order Status</label>
                                        <select id="order_status" name="order_status" class="form-select w-full">
                                            <option value="pending">Pending</option>
                                            <option value="confirmed">Confirmed</option>
                                            <option value="processing">Processing</option>
                                            <option value="shipped">Shipped</option>
                                            <option value="delivered">Delivered</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label" for="payment_method">Payment Method</label>
                                        <select id="payment_method" name="payment_method" class="form-select w-full">
                                            <option value="cod">Cash on Delivery</option>
                                            <!-- Add other payment methods when available -->
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label" for="payment_status">Payment Status</label>
                                        <select id="payment_status" name="payment_status" class="form-select w-full">
                                            <option value="pending">Pending</option>
                                            <option value="paid">Paid</option>
                                            <option value="failed">Failed</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-span-1">
                                    <div class="form-group">
                                        <label class="form-label" for="shipping_cost">Shipping Cost (₹)</label>
                                        <input type="number" min="0" step="0.01" id="shipping_cost" name="shipping_cost" value="0" class="form-input" onchange="updateTotals()">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label" for="tax_amount">Tax Amount (₹)</label>
                                        <input type="number" min="0" step="0.01" id="tax_amount" name="tax_amount" value="0" class="form-input" onchange="updateTotals()" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label" for="discount_amount">Discount Amount (₹)</label>
                                        <input type="number" min="0" step="0.01" id="discount_amount" name="discount_amount" value="0" class="form-input" onchange="updateTotals()" readonly>
                                    </div>
                                </div>
                                
                                <div class="col-span-1">
                                    <div class="form-group h-full">
                                        <label class="form-label" for="notes">Order Notes</label>
                                        <textarea id="notes" name="notes" class="form-input h-32 resize-y" placeholder="Enter any notes from the customer..."></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mt-4">
                                <label class="form-label" for="admin_notes">Admin Notes</label>
                                <textarea id="admin_notes" name="admin_notes" class="form-input" placeholder="Enter any internal notes for this order..."></textarea>
                            </div>
                            
                            <div class="mt-6 border-t border-[var(--border-color)] pt-6 flex justify-between items-center">
                                <div class="text-lg font-medium text-[var(--text-primary)]">
                                    Total Amount: <span id="totalAmount" class="font-bold">₹0.00</span>
                                </div>
                                <div class="flex gap-2">
                                    <a href="index.php" class="btn btn-outline">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <span class="material-icons-outlined">save</span>
                                        <span>Create Order</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </main>
</div>
</div>
</div>

<div id="productTemplate" class="hidden">
    <tr class="product-row border-b border-[var(--border-color)]">
        <td class="px-4 py-2">
            <select name="product_id[]" class="form-select w-full product-select" onchange="updateProductDetails(this)">
                <option value="">-- Select Product --</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?php echo $product['id']; ?>" 
                        data-price="<?php echo $product['sale_price'] > 0 ? $product['sale_price'] : $product['price']; ?>" 
                        data-name="<?php echo htmlspecialchars($product['name']); ?>" 
                        data-sku="<?php echo htmlspecialchars($product['sku'] ?? ''); ?>"
                        data-brand="<?php echo htmlspecialchars($product['brand'] ?? ''); ?>">
                        <?php echo htmlspecialchars($product['name'] . ' (' . ($product['sku'] ?? 'No SKU') . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td class="px-4 py-2">
            <input type="number" min="0" step="0.01" name="price[]" class="form-input price-input" onchange="updateRowTotal(this.parentNode.parentNode)">
        </td>
        <td class="px-4 py-2">
            <input type="number" min="1" name="quantity[]" value="1" class="form-input quantity-input" onchange="updateRowTotal(this.parentNode.parentNode)">
        </td>
        <td class="px-4 py-2">
            <span class="row-total">₹0.00</span>
        </td>
        <td class="px-4 py-2 text-center">
            <button type="button" class="frosted-glass rounded-md p-1 bg-red-500/10 text-red-300 hover:bg-red-500/20" onclick="removeProductRow(this)">
                <span class="material-icons-outlined">delete</span>
            </button>
        </td>
    </tr>
</div>

<script>
// Function to load customer details
function loadCustomerDetails(customerId) {
    if (!customerId) {
        // Clear fields if no customer is selected
        document.getElementById('customer_email').value = '';
        document.getElementById('customer_phone').value = '';
        return;
    }
    
    // Redirect to the same page with customer ID in query string
    window.location.href = 'create.php?customer_id=' + customerId;
}

// Function to fill billing address from saved address
function fillBillingAddress(addressId) {
    if (!addressId) return;
    
    const select = document.getElementById('billing_address_id');
    const option = select.options[select.selectedIndex];
    
    document.getElementById('billing_first_name').value = option.getAttribute('data-first-name');
    document.getElementById('billing_last_name').value = option.getAttribute('data-last-name');
    document.getElementById('billing_address_line_1').value = option.getAttribute('data-address-line-1');
    document.getElementById('billing_address_line_2').value = option.getAttribute('data-address-line-2') || '';
    document.getElementById('billing_city').value = option.getAttribute('data-city');
    document.getElementById('billing_state').value = option.getAttribute('data-state');
    document.getElementById('billing_postal_code').value = option.getAttribute('data-postal-code');
    document.getElementById('billing_country').value = option.getAttribute('data-country');
}

// Function to fill shipping address from saved address
function fillShippingAddress(addressId) {
    if (!addressId) return;
    
    const select = document.getElementById('shipping_address_id');
    const option = select.options[select.selectedIndex];
    
    document.getElementById('shipping_first_name').value = option.getAttribute('data-first-name');
    document.getElementById('shipping_last_name').value = option.getAttribute('data-last-name');
    document.getElementById('shipping_address_line_1').value = option.getAttribute('data-address-line-1');
    document.getElementById('shipping_address_line_2').value = option.getAttribute('data-address-line-2') || '';
    document.getElementById('shipping_city').value = option.getAttribute('data-city');
    document.getElementById('shipping_state').value = option.getAttribute('data-state');
    document.getElementById('shipping_postal_code').value = option.getAttribute('data-postal-code');
    document.getElementById('shipping_country').value = option.getAttribute('data-country');
}

// Function to copy billing address to shipping address
function copyBillingToShipping() {
    const sameAsBilling = document.getElementById('same_as_billing').checked;
    
    if (sameAsBilling) {
        document.getElementById('shipping_first_name').value = document.getElementById('billing_first_name').value;
        document.getElementById('shipping_last_name').value = document.getElementById('billing_last_name').value;
        document.getElementById('shipping_address_line_1').value = document.getElementById('billing_address_line_1').value;
        document.getElementById('shipping_address_line_2').value = document.getElementById('billing_address_line_2').value;
        document.getElementById('shipping_city').value = document.getElementById('billing_city').value;
        document.getElementById('shipping_state').value = document.getElementById('billing_state').value;
        document.getElementById('shipping_postal_code').value = document.getElementById('billing_postal_code').value;
        document.getElementById('shipping_country').value = document.getElementById('billing_country').value;
    }
}

// Function to add a new product row
function addProductRow() {
    const template = document.getElementById('productTemplate');
    const productRows = document.getElementById('productRows');
    const noProductsRow = document.getElementById('noProductsRow');
    
    if (noProductsRow) {
        noProductsRow.style.display = 'none';
    }
    
    const clone = template.firstElementChild.cloneNode(true);
    clone.classList.remove('hidden');
    productRows.appendChild(clone);
}

// Function to remove a product row
function removeProductRow(button) {
    const row = button.closest('.product-row');
    row.parentNode.removeChild(row);
    
    // Show "no products" row if there are no more product rows
    const productRows = document.querySelectorAll('.product-row');
    const noProductsRow = document.getElementById('noProductsRow');
    
    if (productRows.length === 0 && noProductsRow) {
        noProductsRow.style.display = '';
    }
    
    updateTotals();
}

// Function to update product details when a product is selected
function updateProductDetails(select) {
    const row = select.closest('.product-row');
    const option = select.options[select.selectedIndex];
    const priceInput = row.querySelector('.price-input');
    
    if (option.value) {
        priceInput.value = parseFloat(option.getAttribute('data-price')).toFixed(2);
    } else {
        priceInput.value = '';
    }
    
    updateRowTotal(row);
}

// Function to update the row total
function updateRowTotal(row) {
    const priceInput = row.querySelector('.price-input');
    const quantityInput = row.querySelector('.quantity-input');
    const rowTotalSpan = row.querySelector('.row-total');
    
    const price = parseFloat(priceInput.value) || 0;
    const quantity = parseInt(quantityInput.value) || 0;
    const total = price * quantity;
    
    rowTotalSpan.textContent = '₹' + total.toFixed(2);
    
    updateTotals();
}

// Function to update order totals
function updateTotals() {
    const rows = document.querySelectorAll('.product-row');
    let subtotal = 0;
    
    rows.forEach(row => {
        const priceInput = row.querySelector('.price-input');
        const quantityInput = row.querySelector('.quantity-input');
        
        const price = parseFloat(priceInput.value) || 0;
        const quantity = parseInt(quantityInput.value) || 0;
        
        subtotal += price * quantity;
    });
    
    const shippingCost = parseFloat(document.getElementById('shipping_cost').value) || 0;
    const taxAmount = parseFloat(document.getElementById('tax_amount').value) || 0;
    const discountAmount = parseFloat(document.getElementById('discount_amount').value) || 0;
    
    const total = subtotal + shippingCost + taxAmount - discountAmount;
    
    document.getElementById('subtotal').textContent = '₹' + subtotal.toFixed(2);
    document.getElementById('totalAmount').textContent = '₹' + total.toFixed(2);
}

// Initialize the form
document.addEventListener('DOMContentLoaded', function() {
    // Initialize customer details if customer ID is provided
    <?php if (isset($customer) && $customer): ?>
        document.getElementById('customer_email').value = '<?php echo htmlspecialchars($customer['email']); ?>';
        document.getElementById('customer_phone').value = '<?php echo htmlspecialchars($customer['phone']); ?>';
        
        // Auto-select default address if available
        <?php if (!empty($customer_addresses)): ?>
            const defaultBillingAddress = Array.from(document.getElementById('billing_address_id').options)
                .find(option => option.getAttribute('data-is-default') === '1');
            
            if (defaultBillingAddress) {
                document.getElementById('billing_address_id').value = defaultBillingAddress.value;
                fillBillingAddress(defaultBillingAddress.value);
            }
            
            const defaultShippingAddress = Array.from(document.getElementById('shipping_address_id').options)
                .find(option => option.getAttribute('data-is-default') === '1');
            
            if (defaultShippingAddress) {
                document.getElementById('shipping_address_id').value = defaultShippingAddress.value;
                fillShippingAddress(defaultShippingAddress.value);
            }
        <?php endif; ?>
    <?php endif; ?>
    
    // Add a product row to start with
    addProductRow();
});
</script>
</body>
</html> 