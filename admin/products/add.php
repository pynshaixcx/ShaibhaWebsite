<?php
$page_title = "Add New Product";
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Get categories for dropdown
$categories = fetchAll("SELECT id, name FROM categories WHERE status = 'active' ORDER BY name");

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
            // Check if slug already exists
            $existing_product = fetchOne("SELECT id FROM products WHERE slug = ?", [$slug]);
            
            if ($existing_product) {
                $error = 'A product with this slug already exists. Please choose a different slug.';
            } else {
                // Insert product
                $sql = "INSERT INTO products (
                    name, slug, description, short_description, category_id, price, sale_price, 
                    sku, stock_quantity, condition_rating, size, color, brand, material, 
                    care_instructions, featured, status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $params = [
                    $name, $slug, $description, $short_description, $category_id, $price, $sale_price,
                    $sku, $stock_quantity, $condition_rating, $size, $color, $brand, $material,
                    $care_instructions, $featured, $status
                ];
                
                $result = executeQuery($sql, $params);
                
                if ($result) {
                    $product_id = getLastInsertId();
                    
                    // Process image upload (placeholder for now)
                    // In a real implementation, you would handle file uploads here
                    
                    // Log activity
                    logActivity('admin', getCurrentAdminId(), 'product_created', "Product '{$name}' created");
                    
                    $success = 'Product added successfully!';
                    
                    // Redirect after a short delay
                    header("refresh:2;url=edit.php?id={$product_id}");
                } else {
                    $error = 'Failed to add product. Please try again.';
                }
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="../../css/admin.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="../../images/favicon.svg">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h1 class="sidebar-logo">ShaiBha</h1>
                <p class="sidebar-subtitle">Admin Panel</p>
            </div>
            
            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="../index.php" class="nav-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9,22 9,12 15,12 15,22"></polyline>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php" class="nav-link active">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                            <span>Products</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../orders/index.php" class="nav-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            </svg>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../customers/index.php" class="nav-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>Customers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../reports/sales.php" class="nav-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                                <line x1="3" y1="20" x2="21" y2="20"></line>
                            </svg>
                            <span>Reports</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="../logout.php" class="logout-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-content">
                    <h1 class="page-title">Add New Product</h1>
                    <div class="header-actions">
                        <a href="index.php" class="btn btn-outline">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            Back to Products
                        </a>
                    </div>
                </div>
            </header>

            <!-- Add Product Content -->
            <div class="admin-content">
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data" class="admin-form" data-validate>
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
                    <div class="form-layout">
                        <div class="form-main">
                            <!-- Basic Information -->
                            <div class="form-section">
                                <h2 class="section-title">Basic Information</h2>
                                
                                <div class="form-group">
                                    <label for="name">Product Name *</label>
                                    <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="slug">Slug</label>
                                    <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($_POST['slug'] ?? ''); ?>" placeholder="Leave empty to generate automatically">
                                    <div class="field-hint">URL-friendly version of the name (e.g., vintage-silk-dress)</div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="category_id">Category *</label>
                                        <select id="category_id" name="category_id" required>
                                            <option value="">Select Category</option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?php echo $category['id']; ?>" <?php echo isset($_POST['category_id']) && $_POST['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($category['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="sku">SKU</label>
                                        <input type="text" id="sku" name="sku" value="<?php echo htmlspecialchars($_POST['sku'] ?? ''); ?>">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="short_description">Short Description</label>
                                    <input type="text" id="short_description" name="short_description" value="<?php echo htmlspecialchars($_POST['short_description'] ?? ''); ?>" maxlength="500">
                                    <div class="field-hint">Brief description for product listings (max 500 characters)</div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="description">Full Description *</label>
                                    <textarea id="description" name="description" rows="6" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                            
                            <!-- Pricing & Inventory -->
                            <div class="form-section">
                                <h2 class="section-title">Pricing & Inventory</h2>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="price">Regular Price (₹) *</label>
                                        <input type="number" id="price" name="price" step="0.01" min="0" required value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="sale_price">Sale Price (₹)</label>
                                        <input type="number" id="sale_price" name="sale_price" step="0.01" min="0" value="<?php echo htmlspecialchars($_POST['sale_price'] ?? ''); ?>">
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="stock_quantity">Stock Quantity *</label>
                                        <input type="number" id="stock_quantity" name="stock_quantity" min="0" required value="<?php echo htmlspecialchars($_POST['stock_quantity'] ?? '1'); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="condition_rating">Condition *</label>
                                        <select id="condition_rating" name="condition_rating" required>
                                            <option value="excellent" <?php echo isset($_POST['condition_rating']) && $_POST['condition_rating'] === 'excellent' ? 'selected' : ''; ?>>Excellent</option>
                                            <option value="very_good" <?php echo isset($_POST['condition_rating']) && $_POST['condition_rating'] === 'very_good' ? 'selected' : ''; ?>>Very Good</option>
                                            <option value="good" <?php echo isset($_POST['condition_rating']) && $_POST['condition_rating'] === 'good' ? 'selected' : (!isset($_POST['condition_rating']) ? 'selected' : ''); ?>>Good</option>
                                            <option value="fair" <?php echo isset($_POST['condition_rating']) && $_POST['condition_rating'] === 'fair' ? 'selected' : ''; ?>>Fair</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Product Attributes -->
                            <div class="form-section">
                                <h2 class="section-title">Product Attributes</h2>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="size">Size</label>
                                        <input type="text" id="size" name="size" value="<?php echo htmlspecialchars($_POST['size'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="color">Color</label>
                                        <input type="text" id="color" name="color" value="<?php echo htmlspecialchars($_POST['color'] ?? ''); ?>">
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="brand">Brand</label>
                                        <input type="text" id="brand" name="brand" value="<?php echo htmlspecialchars($_POST['brand'] ?? ''); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="material">Material</label>
                                        <input type="text" id="material" name="material" value="<?php echo htmlspecialchars($_POST['material'] ?? ''); ?>">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="care_instructions">Care Instructions</label>
                                    <textarea id="care_instructions" name="care_instructions" rows="4"><?php echo htmlspecialchars($_POST['care_instructions'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-sidebar">
                            <!-- Product Status -->
                            <div class="form-section">
                                <h2 class="section-title">Product Status</h2>
                                
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status">
                                        <option value="active" <?php echo isset($_POST['status']) && $_POST['status'] === 'active' ? 'selected' : (!isset($_POST['status']) ? 'selected' : ''); ?>>Active</option>
                                        <option value="inactive" <?php echo isset($_POST['status']) && $_POST['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                        <option value="sold" <?php echo isset($_POST['status']) && $_POST['status'] === 'sold' ? 'selected' : ''; ?>>Sold</option>
                                    </select>
                                </div>
                                
                                <div class="form-group checkbox-group">
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="featured" <?php echo isset($_POST['featured']) ? 'checked' : ''; ?>>
                                        <span>Featured Product</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Product Image -->
                            <div class="form-section">
                                <h2 class="section-title">Product Image</h2>
                                
                                <div class="form-group">
                                    <label for="product_image">Main Image</label>
                                    <div class="image-preview-container">
                                        <img id="image-preview" src="../../images/placeholder.jpg" alt="Product Image Preview" class="image-preview">
                                    </div>
                                    <input type="file" id="product_image" name="product_image" accept="image/*" onchange="previewImage(this, 'image-preview')">
                                    <div class="field-hint">Recommended size: 800x800 pixels</div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="additional_images">Additional Images</label>
                                    <input type="file" id="additional_images" name="additional_images[]" accept="image/*" multiple>
                                    <div class="field-hint">You can select multiple images</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Add Product</button>
                        <a href="index.php" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script src="../../js/admin.js"></script>
    <script>
    // Generate slug from product name
    document.getElementById('name').addEventListener('blur', function() {
        const slugField = document.getElementById('slug');
        if (slugField.value === '') {
            const name = this.value;
            const slug = name.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            
            slugField.value = slug;
        }
    });
    </script>
</body>
</html>