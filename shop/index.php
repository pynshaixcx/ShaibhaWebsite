<?php
$page_title = "Shop";
$page_description = "Browse our curated collection of pre-loved fashion pieces.";
require_once '../includes/functions.php';
include_once '../includes/header.php';

// Get filters
$category_slug = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'newest';
$page = max(1, intval($_GET['page'] ?? 1));

// Build filters
$filters = [];
if ($category_slug) {
    $category = getCategoryBySlug($category_slug);
    if ($category) {
        $filters['category_id'] = $category['id'];
    }
}
if ($search) {
    $filters['search'] = $search;
}

// Get products count for pagination
$count_sql = "SELECT COUNT(*) as total FROM products p WHERE p.status = 'active'";
$count_params = [];

if (isset($filters['category_id'])) {
    $count_sql .= " AND p.category_id = ?";
    $count_params[] = $filters['category_id'];
}

if (isset($filters['search'])) {
    $count_sql .= " AND (p.name LIKE ? OR p.description LIKE ? OR p.brand LIKE ?)";
    $search_term = '%' . $filters['search'] . '%';
    $count_params[] = $search_term;
    $count_params[] = $search_term;
    $count_params[] = $search_term;
}

$total_products = fetchOne($count_sql, $count_params)['total'] ?? 0;
$pagination = paginate($total_products, PRODUCTS_PER_PAGE, $page);

// Get products with pagination
$sql = "SELECT p.*, c.name as category_name FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.status = 'active'";
$params = [];

if (isset($filters['category_id'])) {
    $sql .= " AND p.category_id = ?";
    $params[] = $filters['category_id'];
}

if (isset($filters['search'])) {
    $sql .= " AND (p.name LIKE ? OR p.description LIKE ? OR p.brand LIKE ?)";
    $search_term = '%' . $filters['search'] . '%';
    $params[] = $search_term;
    $params[] = $search_term;
    $params[] = $search_term;
}

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

// Get categories for filter
$categories = getCategories();
?>

<main class="main-content">
    <!-- Shop Header -->
    <section class="shop-header">
        <div class="container">
            <div class="shop-header-content">
                <h1 class="page-title">
                    <?php if ($category_slug && isset($category)): ?>
                        <?php echo htmlspecialchars($category['name']); ?>
                    <?php elseif ($search): ?>
                        Search Results for "<?php echo htmlspecialchars($search); ?>"
                    <?php else: ?>
                        Shop Collection
                    <?php endif; ?>
                </h1>
                <p class="page-subtitle">
                    <?php echo $total_products; ?> products found
                </p>
            </div>
        </div>
    </section>

    <!-- Shop Content -->
    <section class="shop-content">
        <div class="container">
            <div class="shop-layout">
                <!-- Products Grid -->
                <div class="shop-main">
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
                                $primary_image = getPrimaryProductImage($product['id']);
                                $image_url = $primary_image ? '/uploads/products/' . $primary_image['filename'] : '/images/placeholder-product.jpg';
                                $current_price = $product['sale_price'] ?: $product['price'];
                                $discount_percent = $product['sale_price'] ? calculateDiscountPercentage($product['price'], $product['sale_price']) : 0;
                                ?>
                                <div class="product-card" data-product-id="<?php echo $product['id']; ?>">
                                    <div class="product-image">
                                        <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy">
                                        <?php if ($discount_percent > 0): ?>
                                            <span class="discount-badge"><?php echo $discount_percent; ?>% OFF</span>
                                        <?php endif; ?>
                                        <div class="product-overlay">
                                            <button class="quick-view-btn" onclick="quickView(<?php echo $product['id']; ?>)">Quick View</button>
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
                                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $pagination['prev_page']])); ?>" class="pagination-link">
                                        Previous
                                    </a>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" 
                                       class="pagination-link <?php echo $i === $pagination['current_page'] ? 'active' : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <?php if ($pagination['has_next']): ?>
                                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $pagination['next_page']])); ?>" class="pagination-link">
                                        Next
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="no-products">
                            <h3>No products found</h3>
                            <p>Try adjusting your search or browse our categories.</p>
                            <a href="index.php" class="btn btn-primary">View All Products</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
function updateSort(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', value);
    window.location.href = url.toString();
}

function quickView(productId) {
    // Implement quick view functionality
    fetch(`/shop/ajax/quick-view.php?id=${productId}`)
        .then(response => response.json())
        .then(data => {
            // Display quick view modal
            console.log('Quick view data:', data);
        })
        .catch(error => console.error('Error fetching quick view data:', error));
}

function addToCart(productId) {
    // Implement add to cart functionality
    fetch('/cart/ajax/add.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}&quantity=1`
    })
    .then(response => response.json())
    .then(data => {
        // Update cart count
        const cartCount = document.getElementById('cart-count');
        if (cartCount && data.count) {
            cartCount.textContent = data.count;
            cartCount.classList.add('has-items');
        }
        
        // Show success message
        alert('Product added to cart!');
    })
    .catch(error => console.error('Error adding product to cart:', error));
}
</script>

<?php include_once '../includes/footer.php'; ?>