<?php
$page_title = "Edit Product";
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Get product ID
$product_id = intval($_GET['id'] ?? 0);
if (!$product_id) {
    redirect('index.php');
}

// Get product data
$product = fetchOne("SELECT * FROM products WHERE id = ?", [$product_id]);
if (!$product) {
    redirect('index.php');
}

// Get categories for dropdown
$categories = fetchAll("SELECT id, name FROM categories WHERE status = 'active' ORDER BY name");

// Get product images
$product_images = fetchAll("SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, sort_order", [$product_id]);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        // Get form data
        $name = sanitizeInput($_POST['name'] ?? '');
        $slug = sanitizeInput($_POST['slug'] ?? '');
        $description = sanitizeInput($_POST['description'] ?? '');
        $short_description = sanitizeInput($_POST['short_description'] ?? '');
        $category_id = intval($_POST['category_id'] ?? 0);
        $price = floatval($_POST['price'] ?? 0);
        $sale_price = !empty($_POST['sale_price']) ? floatval($_POST['sale_price']) : null;
        $sku = sanitizeInput($_POST['sku'] ?? '');
        $stock_quantity = intval($_POST['stock_quantity'] ?? 1);
        $condition_rating = sanitizeInput($_POST['condition_rating'] ?? 'good');
        $size = sanitizeInput($_POST['size'] ?? '');
        $color = sanitizeInput($_POST['color'] ?? '');
        $brand = sanitizeInput($_POST['brand'] ?? '');
        $material = sanitizeInput($_POST['material'] ?? '');
        $care_instructions = sanitizeInput($_POST['care_instructions'] ?? '');
        $featured = isset($_POST['featured']) ? 1 : 0;
        $status = sanitizeInput($_POST['status'] ?? 'active');
        
        // Generate slug if empty
        if (empty($slug)) {
            $slug = generateSlug($name);
        }
        
        // Validation
        if (empty($name) || empty($description) || $category_id <= 0 || $price <= 0) {
            $error = 'Please fill in all required fields';
        } elseif ($sale_price !== null && $sale_price >= $price) {
            $error = 'Sale price must be less than regular price';
        } else {
            // Check if slug already exists for other products
            $existing_product = fetchOne("SELECT id FROM products WHERE slug = ? AND id != ?", [$slug, $product_id]);
            
            if ($existing_product) {
                $error = 'A product with this slug already exists. Please choose a different slug.';
            } else {
                // Update product
                $sql = "UPDATE products SET 
                    name = ?, slug = ?, description = ?, short_description = ?, category_id = ?, 
                    price = ?, sale_price = ?, sku = ?, stock_quantity = ?, condition_rating = ?, 
                    size = ?, color = ?, brand = ?, material = ?, care_instructions = ?, 
                    featured = ?, status = ?, updated_at = CURRENT_TIMESTAMP 
                    WHERE id = ?";
                
                $params = [
                    $name, $slug, $description, $short_description, $category_id, 
                    $price, $sale_price, $sku, $stock_quantity, $condition_rating, 
                    $size, $color, $brand, $material, $care_instructions, 
                    $featured, $status, $product_id
                ];
                
                $result = executeQuery($sql, $params);
                
                if ($result) {
                    // Process image upload (placeholder for now)
                    // In a real implementation, you would handle file uploads here
                    
                    // Log activity
                    logActivity('admin', getCurrentAdminId(), 'product_updated', "Product '{$name}' updated");
                    
                    $success = 'Product updated successfully!';
                    
                    // Refresh product data
                    $product = fetchOne("SELECT * FROM products WHERE id = ?", [$product_id]);
                } else {
                    $error = 'Failed to update product. Please try again.';
                }
            }
        }
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
    <title>ShaiBha Admin - Edit Product</title>
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
        .form-input {
        @apply flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-[var(--text-primary)] focus:outline-0 focus:ring-2 focus:ring-inset focus:ring-white/50 border border-[var(--border-color)] bg-[var(--background-secondary)] focus:border-white/50 h-14 placeholder:text-[var(--text-secondary)] p-[15px] text-base font-normal leading-normal transition-colors duration-200 ease-in-out;
          }
          .form-input:hover {
            @apply border-[var(--text-secondary)];
          }
          .btn {
        @apply flex items-center justify-center overflow-hidden rounded-lg h-10 px-6 text-sm font-bold leading-normal tracking-[0.015em] transition-colors duration-200 ease-in-out;
          }
          .btn-primary {
        @apply bg-white/10 text-[var(--text-primary)] hover:bg-white/20;
          }
          .btn-secondary {
        @apply bg-[var(--background-secondary)] text-[var(--text-primary)] hover:bg-[rgba(75,75,75,0.7)];
          }
          .nav-link {
            @apply text-[var(--text-primary)] text-sm font-medium leading-normal hover:text-[var(--text-secondary)] transition-colors duration-200 ease-in-out;
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
    <header class="mb-8">
    <h1 class="text-[var(--text-primary)] text-4xl font-bold leading-tight">Edit Product: <?php echo htmlspecialchars($product['name']); ?></h1>
                <?php if ($error): ?>
        <div class="bg-red-500/30 text-red-300 p-4 rounded-xl mt-4">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
        <div class="bg-green-500/30 text-green-300 p-4 rounded-xl mt-4">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
    </header>
                
    <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
    <div class="md:col-span-2">
    <label class="flex flex-col">
    <p class="text-[var(--text-primary)] text-base font-medium leading-normal pb-2">Product Name</p>
    <input name="name" class="form-input" type="text" value="<?php echo htmlspecialchars($product['name']); ?>" required/>
    </label>
    </div>
    
    <div class="md:col-span-2">
    <label class="flex flex-col">
    <p class="text-[var(--text-primary)] text-base font-medium leading-normal pb-2">Description</p>
    <textarea name="description" class="form-input min-h-36" required><?php echo htmlspecialchars($product['description']); ?></textarea>
    </label>
    </div>
    
    <div>
    <label class="flex flex-col">
    <p class="text-[var(--text-primary)] text-base font-medium leading-normal pb-2">Price (INR)</p>
    <input name="price" class="form-input" type="number" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required/>
    </label>
                                </div>
                                
    <div>
    <label class="flex flex-col">
    <p class="text-[var(--text-primary)] text-base font-medium leading-normal pb-2">Sale Price (INR, optional)</p>
    <input name="sale_price" class="form-input" type="number" step="0.01" value="<?php echo htmlspecialchars($product['sale_price'] ?? ''); ?>"/>
    </label>
                                </div>
                                
    <div>
    <label class="flex flex-col">
    <p class="text-[var(--text-primary)] text-base font-medium leading-normal pb-2">Category</p>
    <select name="category_id" class="form-input bg-[var(--background-secondary)]" required>
                                            <option value="">Select Category</option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?php echo $category['id']; ?>" <?php echo $product['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($category['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
    </label>
                                    </div>
                                    
    <div>
    <label class="flex flex-col">
    <p class="text-[var(--text-primary)] text-base font-medium leading-normal pb-2">SKU</p>
    <input name="sku" class="form-input" type="text" value="<?php echo htmlspecialchars($product['sku']); ?>" required/>
    </label>
                                </div>
                                
    <div>
    <label class="flex flex-col">
    <p class="text-[var(--text-primary)] text-base font-medium leading-normal pb-2">Size</p>
    <input name="size" class="form-input" type="text" value="<?php echo htmlspecialchars($product['size'] ?? ''); ?>"/>
    </label>
                                </div>
                                
    <div>
    <label class="flex flex-col">
    <p class="text-[var(--text-primary)] text-base font-medium leading-normal pb-2">Color</p>
    <input name="color" class="form-input" type="text" value="<?php echo htmlspecialchars($product['color'] ?? ''); ?>"/>
    </label>
                            </div>
                            
    <div>
    <label class="flex flex-col">
    <p class="text-[var(--text-primary)] text-base font-medium leading-normal pb-2">Brand</p>
    <input name="brand" class="form-input" type="text" value="<?php echo htmlspecialchars($product['brand'] ?? ''); ?>"/>
    </label>
                                    </div>
                                    
    <div>
    <label class="flex flex-col">
    <p class="text-[var(--text-primary)] text-base font-medium leading-normal pb-2">Stock Quantity</p>
    <input name="stock_quantity" class="form-input" type="number" value="<?php echo htmlspecialchars($product['stock_quantity']); ?>" required/>
    </label>
                                </div>
                                
    <div>
    <label class="flex flex-col">
    <p class="text-[var(--text-primary)] text-base font-medium leading-normal pb-2">Status</p>
    <select name="status" class="form-input bg-[var(--background-secondary)]">
                                        <option value="active" <?php echo $product['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo $product['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                        <option value="sold" <?php echo $product['status'] === 'sold' ? 'selected' : ''; ?>>Sold</option>
                                    </select>
    </label>
                                </div>
                                
    <div>
    <label class="flex flex-col">
    <p class="text-[var(--text-primary)] text-base font-medium leading-normal pb-2">Featured Product</p>
    <div class="flex items-center space-x-2 mt-2">
        <input type="checkbox" name="featured" id="featured" class="w-5 h-5 rounded bg-[var(--background-secondary)] border-[var(--border-color)]" <?php echo ($product['featured'] ?? 0) ? 'checked' : ''; ?>>
        <label for="featured" class="text-[var(--text-primary)]">Mark as featured product</label>
    </div>
                                    </label>
                            </div>
                            
    <div class="md:col-span-2 mt-4">
    <h3 class="text-[var(--text-primary)] text-xl font-semibold leading-tight tracking-[-0.015em] mb-3">Product Images</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        <?php if (!empty($product_images)): ?>
                                            <?php foreach ($product_images as $image): ?>
                <div class="relative group aspect-square">
                <div class="w-full h-full bg-center bg-no-repeat bg-cover rounded-lg border border-[var(--border-color)]" style='background-image: url("../../images/products/<?php echo htmlspecialchars($image['file_name']); ?>");'></div>
                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 ease-in-out">
                <button type="button" class="text-white p-2 rounded-full hover:bg-white/20">
                <span class="material-icons-outlined">edit</span>
                </button>
                <button type="button" class="text-white p-2 rounded-full hover:bg-white/20">
                <span class="material-icons-outlined">delete</span>
                </button>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
        
        <div class="relative group aspect-square flex items-center justify-center border-2 border-dashed border-[var(--border-color)] rounded-lg hover:border-[var(--text-secondary)] transition-colors duration-200 ease-in-out cursor-pointer">
        <div class="text-center">
        <span class="material-icons-outlined text-[var(--text-secondary)] text-4xl mb-2 group-hover:text-[var(--text-primary)] transition-colors duration-200 ease-in-out">add</span>
        <p class="text-sm text-[var(--text-secondary)] group-hover:text-[var(--text-primary)] transition-colors duration-200 ease-in-out">Add Image</p>
                                    </div>
        <input name="product_image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" type="file" accept="image/*"/>
                            </div>
                        </div>
                    </div>
                    
    <div class="md:col-span-2 flex justify-end gap-4 mt-8">
    <a href="delete.php?id=<?php echo $product_id; ?>" onclick="return confirm('Are you sure you want to delete this product?')" class="btn btn-secondary min-w-[140px]">
    <span class="truncate">Delete Product</span>
    </a>
    <button class="btn btn-primary min-w-[140px]" type="submit">
    <span class="truncate">Update Product</span>
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
// Client-side validation and other functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add any JavaScript functionality here
    });
    </script>
</body>
</html>