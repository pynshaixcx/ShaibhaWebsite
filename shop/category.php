<?php
$category_slug = $_GET['cat'] ?? '';
if (!$category_slug) {
    header('Location: index.php');
    exit;
}

require_once '../includes/functions.php';

$category = getCategoryBySlug($category_slug);
if (!$category) {
    header('Location: index.php');
    exit;
}

$page_title = $category['name'];
$page_description = $category['description'] ?: "Browse our curated collection of {$category['name']}.";

// Get products in this category
$page = max(1, intval($_GET['page'] ?? 1));
$sort = $_GET['sort'] ?? 'newest';

$filters = ['category_id' => $category['id']];

// Get products count for pagination
$count_sql = "SELECT COUNT(*) as total FROM products WHERE category_id = ? AND status = 'active'";
$total_products = fetchOne($count_sql, [$category['id']])['total'] ?? 0;
$pagination = paginate($total_products, PRODUCTS_PER_PAGE, $page);

// Get products with pagination
$sql = "SELECT p.*, c.name as category_name FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.category_id = ? AND p.status = 'active'";
$params = [$category['id']];

// Add sorting
switch ($sort) {
    case 'price_low':
        $sql .= " ORDER BY COALESCE(p.sale_price, p.price) ASC";
        break;
    case 'price_high':
        $sql .= " ORDER BY COALESCE(p.sale_price, p.price) DESC";
        break;
    case 'name':
        $sql .= " ORDER BY p.name ASC";
        break;
    default:
        $sql .= " ORDER BY p.created_at DESC";
}

$sql .= " LIMIT ? OFFSET ?";
$params[] = PRODUCTS_PER_PAGE;
$params[] = $pagination['offset'];

$products = fetchAll($sql, $params);

include_once '../includes/header.php';
?>

<main class="main-content">
    <!-- Category Header -->
    <section class="shop-header category-header">
        <div class="container">
            <div class="shop-header-content">
                <h1 class="page-title"><?php echo htmlspecialchars($category['name']); ?></h1>
                <p class="page-subtitle"><?php echo htmlspecialchars($category['description']); ?></p>
                <p class="products-count"><?php echo $total_products; ?> products found</p>
            </div>
        </div>
    </section>

    <!-- Category Content -->
    <section class="shop-content">
        <div class="container">
            <!-- Sort Options -->
            <div class="shop-controls">
                <div class="sort-options">
                    <label for="sort">Sort by:</label>
                    <select id="sort" name="sort" onchange="updateSort(this.value)">
                        <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest First</option>
                        <option value="price_low" <?php echo $sort === 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_high" <?php echo $sort === 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                        <option value="name" <?php echo $sort === 'name' ? 'selected' : ''; ?>>Name A-Z</option>
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
            <?php if (!empty($products)): ?>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <?php
                        // Use local images
                        $image_path = "../images/products/product_" . rand(1, 3) . ".jpg";
                        $current_price = $product['sale_price'] ?: $product['price'];
                        $discount_percent = $product['sale_price'] ? calculateDiscountPercentage($product['price'], $product['sale_price']) : 0;
                        ?>
                        <div class="product-card" data-product-id="<?php echo $product['id']; ?>">
                            <div class="product-image">
                                <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy">
                                <?php if ($discount_percent > 0): ?>
                                    <span class="discount-badge"><?php echo $discount_percent; ?>% OFF</span>
                                <?php endif; ?>
                                <div class="product-overlay">
                                    <a href="product.php?slug=<?php echo $product['slug']; ?>" class="quick-view-btn">View Details</a>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">
                                    <a href="product.php?slug=<?php echo $product['slug']; ?>">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </a>
                                </h3>
                                <p class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></p>
                                <p class="product-condition"><?php echo ucfirst(str_replace('_', ' ', $product['condition_rating'])); ?> Condition</p>
                                <div class="product-price">
                                    <span class="current-price"><?php echo formatPrice($current_price); ?></span>
                                    <?php if ($product['sale_price']): ?>
                                        <span class="original-price"><?php echo formatPrice($product['price']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <button class="btn btn-primary add-to-cart-btn" onclick="addToCart(<?php echo $product['id']; ?>)">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($pagination['total_pages'] > 1): ?>
                    <div class="pagination">
                        <?php if ($pagination['has_prev']): ?>
                            <a href="?cat=<?php echo $category_slug; ?>&page=<?php echo $pagination['prev_page']; ?>&sort=<?php echo $sort; ?>" class="pagination-link">
                                Previous
                            </a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                            <a href="?cat=<?php echo $category_slug; ?>&page=<?php echo $i; ?>&sort=<?php echo $sort; ?>" 
                               class="pagination-link <?php echo $i === $pagination['current_page'] ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($pagination['has_next']): ?>
                            <a href="?cat=<?php echo $category_slug; ?>&page=<?php echo $pagination['next_page']; ?>&sort=<?php echo $sort; ?>" class="pagination-link">
                                Next
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-products">
                    <h3>No products found in this category</h3>
                    <p>Check back soon for new arrivals!</p>
                    <a href="../shop/" class="btn btn-primary">Browse All Products</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<script>
function updateSort(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', value);
    window.location.href = url.toString();
}

function addToCart(productId) {
    fetch('../cart/ajax/add-to-cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update cart count
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                cartCount.textContent = data.cart_count;
                cartCount.style.display = 'flex';
            }
            
            // Show success message
            alert('Product added to cart!');
        } else {
            alert(data.message || 'Error adding product to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding product to cart');
    });
}
</script>

<?php include_once '../includes/footer.php'; ?>