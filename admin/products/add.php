<?php
$page_title = "Add Product";
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Get categories for dropdown
$categories = fetchAll("SELECT * FROM categories ORDER BY name");

// Initialize variables
$product = [
    'name' => '',
    'description' => '',
    'price' => '',
    'sale_price' => '',
    'sku' => '',
    'stock_quantity' => '',
    'category_id' => '',
    'status' => 'active'
];

$errors = [];
$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $product['name'] = sanitizeInput($_POST['name'] ?? '');
    $product['description'] = sanitizeInput($_POST['description'] ?? '');
    $product['price'] = floatval($_POST['price'] ?? 0);
    $product['sale_price'] = !empty($_POST['sale_price']) ? floatval($_POST['sale_price']) : null;
    $product['sku'] = sanitizeInput($_POST['sku'] ?? '');
    $product['stock_quantity'] = intval($_POST['stock_quantity'] ?? 0);
    $product['category_id'] = intval($_POST['category_id'] ?? 0);
    $product['status'] = sanitizeInput($_POST['status'] ?? 'active');
    
    // Validate required fields
    if (empty($product['name'])) {
        $errors['name'] = 'Product name is required';
    }
    
    if (empty($product['price']) || $product['price'] <= 0) {
        $errors['price'] = 'Valid price is required';
    }
    
    if (empty($product['sku'])) {
        $errors['sku'] = 'SKU is required';
    } else {
        // Check if SKU already exists
        $existing = fetchOne("SELECT id FROM products WHERE sku = ?", [$product['sku']]);
        if ($existing) {
            $errors['sku'] = 'This SKU is already in use';
        }
    }
    
    // If no errors, proceed with saving
    if (empty($errors)) {
        // Generate slug from name
        $slug = createSlug($product['name']);
        
        // Check if slug exists and append number if needed
        $base_slug = $slug;
        $counter = 1;
        
        while (fetchOne("SELECT id FROM products WHERE slug = ?", [$slug])) {
            $slug = $base_slug . '-' . $counter;
            $counter++;
        }
        
        // Insert new product
        $result = executeQuery(
            "INSERT INTO products (name, description, slug, price, sale_price, sku, stock_quantity, category_id, status, created_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
            [
                $product['name'],
                $product['description'],
                $slug,
                $product['price'],
                $product['sale_price'],
                $product['sku'],
                $product['stock_quantity'],
                $product['category_id'] ?: null,
                $product['status']
            ]
        );
                
                if ($result) {
                    $product_id = getLastInsertId();
                    
            // Handle image upload
            if (!empty($_FILES['image']['name'])) {
                $upload_dir = '../../images/products/';
                $file_name = 'product_' . $product_id . '.jpg';
                $upload_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    // Update product with image path
                    executeQuery(
                        "UPDATE products SET image_path = ? WHERE id = ?",
                        [$file_name, $product_id]
                    );
                }
            }
                    
                    // Log activity
            logActivity('admin', getCurrentAdminId(), 'product_add', 'Added new product: ' . $product['name']);
            
            // Redirect to products list with success message
            setFlashMessage('success', 'Product added successfully');
            redirect('index.php');
                } else {
            $errors['general'] = 'Failed to add product. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link crossorigin="" href="https://fonts.gstatic.com/" rel="preconnect"/>
    <link as="style" href="https://fonts.googleapis.com/css2?display=swap&family=Inter%3Awght%40400%3B500%3B600%3B700%3B900&family=Noto+Sans%3Awght%40400%3B500%3B700%3B900" onload="this.rel='stylesheet'" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>
    <meta charset="utf-8"/>
    <title>ShaiBha Admin - Add New Product</title>
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
          .input-field {
        background-color: rgba(48, 48, 48, 0.7);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
          }
          .input-field:focus {
        border-color: white;
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.25);
      }
          .btn-primary {
        background-color: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
          }
          .btn-primary:hover {
        background-color: rgba(255, 255, 255, 0.2);
          }
          .btn-secondary {
        background-color: var(--background-secondary);
            color: var(--text-primary);
          }
          .btn-secondary:hover {
        background-color: rgba(75, 75, 75, 0.7);
          }
        </style>
</head>
<body class="bg-gradient-to-br from-black via-slate-900 to-black">
<div class="relative flex size-full min-h-screen flex-col bg-cover bg-center bg-fixed" style='font-family: Inter, "Noto Sans", sans-serif;'>
<div class="relative flex size-full min-h-screen flex-col dark group/design-root">
    <div class="layout-container flex h-full grow flex-col">
<header class="frosted-glass sticky top-0 z-50 flex items-center justify-between whitespace-nowrap border-b border-solid border-[var(--border-color)] bg-[var(--background-primary)] px-6 py-4 md:px-10">
    <div class="flex items-center gap-4">
<div class="text-[var(--text-primary)]">
<h2 class="text-xl font-semibold leading-tight tracking-[-0.015em]">
<span class="font-bold">ShaiBha</span> Admin Panel
</h2>
    </div>
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

<main class="flex-1 p-6 md:p-10 overflow-y-auto">
<div class="frosted-glass rounded-xl p-6">
<div class="flex flex-wrap justify-between items-center gap-3 mb-6">
    <h2 class="text-[var(--text-primary)] text-3xl font-bold tracking-tight">Add New Product</h2>
                    </div>
                
<?php if (!empty($errors)): ?>
<div class="bg-red-500/30 text-red-300 p-4 rounded-xl mb-6">
    <ul class="list-disc pl-5">
        <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
                    </div>
                <?php endif; ?>
                
<form method="POST" enctype="multipart/form-data">
<section class="frosted-glass p-6 rounded-xl shadow-xl bg-[var(--background-secondary)] border border-[var(--border-color)] mb-6">
    <h3 class="text-[var(--text-primary)] text-xl font-semibold mb-6">Product Information</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <label class="flex flex-col gap-2">
    <p class="text-[var(--text-primary)] text-sm font-medium">Product Name</p>
<input name="name" class="form-input input-field w-full rounded-lg text-[var(--text-primary)] focus:outline-none focus:ring-2 focus:ring-white/50 h-12 placeholder:text-[var(--text-secondary)] p-3 text-sm" placeholder="e.g. Wireless Headphones" value="<?php echo htmlspecialchars($product['name']); ?>" required/>
    </label>
    
    <label class="flex flex-col gap-2">
    <p class="text-[var(--text-primary)] text-sm font-medium">Category</p>
    <select name="category_id" class="form-select input-field w-full rounded-lg text-[var(--text-primary)] focus:outline-none focus:ring-2 focus:ring-white/50 h-12 placeholder:text-[var(--text-secondary)] p-3 text-sm">
                                            <option value="">Select Category</option>
                                            <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category['id']; ?>" <?php echo $product['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($category['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
    </label>
                                    
    <label class="flex flex-col gap-2 md:col-span-2">
    <p class="text-[var(--text-primary)] text-sm font-medium">Description</p>
<textarea name="description" class="form-textarea input-field w-full rounded-lg text-[var(--text-primary)] focus:outline-none focus:ring-2 focus:ring-white/50 min-h-32 placeholder:text-[var(--text-secondary)] p-3 text-sm resize-y" placeholder="Provide a detailed description of your product..."><?php echo htmlspecialchars($product['description']); ?></textarea>
    </label>
                                    </div>
    </section>
                                
<section class="frosted-glass p-6 rounded-xl shadow-xl bg-[var(--background-secondary)] border border-[var(--border-color)] mb-6">
    <h3 class="text-[var(--text-primary)] text-xl font-semibold mb-6">Product Images</h3>
<div class="flex flex-col items-center justify-center gap-4 rounded-lg border-2 border-dashed border-[var(--border-color)] px-6 py-10 hover:border-white/50 transition-colors">
<span class="material-icons-outlined text-[var(--text-secondary)]" style="font-size: 48px;">photo_library</span>
    <p class="text-[var(--text-primary)] text-base font-medium">Drag and drop images here, or click to browse</p>
    <p class="text-[var(--text-secondary)] text-xs">Supports JPG, PNG, GIF up to 5MB</p>
    <input type="file" name="image" id="image" class="hidden" accept="image/*" />
    <label for="image" class="btn-secondary mt-2 flex items-center justify-center rounded-md h-10 px-5 text-sm font-semibold tracking-wide transition-colors cursor-pointer">
    <span class="truncate">Browse Files</span>
    </label>
                                </div>
    </section>

<section class="frosted-glass p-6 rounded-xl shadow-xl bg-[var(--background-secondary)] border border-[var(--border-color)] mb-6">
    <h3 class="text-[var(--text-primary)] text-xl font-semibold mb-6">Pricing &amp; Inventory</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <label class="flex flex-col gap-2">
    <p class="text-[var(--text-primary)] text-sm font-medium">Price (INR)</p>
<input name="price" class="form-input input-field w-full rounded-lg text-[var(--text-primary)] focus:outline-none focus:ring-2 focus:ring-white/50 h-12 placeholder:text-[var(--text-secondary)] p-3 text-sm" placeholder="e.g. 7499.00" type="number" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required/>
    </label>
                                
    <label class="flex flex-col gap-2">
    <p class="text-[var(--text-primary)] text-sm font-medium">Sale Price (INR, optional)</p>
<input name="sale_price" class="form-input input-field w-full rounded-lg text-[var(--text-primary)] focus:outline-none focus:ring-2 focus:ring-white/50 h-12 placeholder:text-[var(--text-secondary)] p-3 text-sm" placeholder="e.g. 6999.00" type="number" step="0.01" value="<?php echo htmlspecialchars($product['sale_price'] ?? ''); ?>"/>
    </label>
    
    <label class="flex flex-col gap-2">
    <p class="text-[var(--text-primary)] text-sm font-medium">Stock Keeping Unit (SKU)</p>
<input name="sku" class="form-input input-field w-full rounded-lg text-[var(--text-primary)] focus:outline-none focus:ring-2 focus:ring-white/50 h-12 placeholder:text-[var(--text-secondary)] p-3 text-sm" placeholder="e.g. WH-001-BLK" value="<?php echo htmlspecialchars($product['sku']); ?>" required/>
    </label>
                                    
    <label class="flex flex-col gap-2">
    <p class="text-[var(--text-primary)] text-sm font-medium">Quantity in Stock</p>
<input name="stock_quantity" class="form-input input-field w-full rounded-lg text-[var(--text-primary)] focus:outline-none focus:ring-2 focus:ring-white/50 h-12 placeholder:text-[var(--text-secondary)] p-3 text-sm" placeholder="e.g. 500" type="number" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" required/>
    </label>
                                
    <label class="flex flex-col gap-2">
    <p class="text-[var(--text-primary)] text-sm font-medium">Status</p>
    <select name="status" class="form-select input-field w-full rounded-lg text-[var(--text-primary)] focus:outline-none focus:ring-2 focus:ring-white/50 h-12 placeholder:text-[var(--text-secondary)] p-3 text-sm">
        <option value="active" <?php echo $product['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
        <option value="inactive" <?php echo $product['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                    </label>
                                </div>
    </section>
                            
    <div class="flex justify-end gap-4 mt-4">
<a href="index.php" class="btn-secondary flex items-center justify-center rounded-lg h-11 px-6 text-sm font-semibold tracking-wide transition-colors">
    <span class="truncate">Cancel</span>
</a>
<button type="submit" class="btn-primary flex items-center justify-center rounded-lg h-11 px-6 text-sm font-semibold tracking-wide transition-colors">
    <span class="truncate">Add Product</span>
</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
</div>
    </div>

    <script>
    // Generate slug from product name
document.addEventListener('DOMContentLoaded', function() {
    const nameField = document.querySelector('input[name="name"]');
    if (nameField) {
        nameField.addEventListener('blur', function() {
            // This is just for client-side validation - the server will handle the actual slug creation
            console.log('Name field blurred:', this.value);
        });
        }
    });
    </script>
</body>
</html>