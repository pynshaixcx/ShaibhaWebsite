<?php
$search_query = $_GET['q'] ?? '';
$page_title = $search_query ? "Search Results for '{$search_query}'" : "Search Products";
$page_description = "Search our curated collection of pre-loved fashion pieces.";

require_once '../includes/functions.php';

$page = max(1, intval($_GET['page'] ?? 1));
$sort = $_GET['sort'] ?? 'newest';

$products = [];
$total_products = 0;
$pagination = null;

if ($search_query) {
    // Get products count for pagination
    $count_sql = "SELECT COUNT(*) as total FROM products p WHERE p.status = 'active' 
                  AND (p.name LIKE ? OR p.description LIKE ? OR p.brand LIKE ?)";
    $search_term = '%' . $search_query . '%';
    $total_products = fetchOne($count_sql, [$search_term, $search_term, $search_term])['total'] ?? 0;
    
    if ($total_products > 0) {
        $pagination = paginate($total_products, PRODUCTS_PER_PAGE, $page);
        
        // Get products with pagination
        $sql = "SELECT p.*, c.name as category_name FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 'active' 
                AND (p.name LIKE ? OR p.description LIKE ? OR p.brand LIKE ?)";
        $params = [$search_term, $search_term, $search_term];
        
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
    }
}

include_once '../includes/header.php';
?>

<main class="main-content">
    <!-- Search Header -->
    <section class="shop-header search-header">
        <div class="container">
            <div class="shop-header-content">
                <h1 class="page-title">
                    <?php if ($search_query): ?>
                        Search Results for "<?php echo htmlspecialchars($search_query); ?>"
                    <?php else: ?>
                        Search Products
                    <?php endif; ?>
                </h1>
                <?php if ($search_query): ?>
                    <p class="page-subtitle products-count"><?php echo $total_products; ?> products found</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Search Content -->
    <section class="shop-content search-content">
        <div class="container">
            <!-- Search Form -->
            <div class="search-form-section">
                <form method="GET" class="search-form">
                    <div class="search-input-group">
                        <input type="text" name="q" value="<?php echo htmlspecialchars($search_query); ?>" 
                               placeholder="Search for products, brands, or categories..." required>
                        <button type="submit" class="search-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <?php if ($search_query && $total_products > 0): ?>
                <!-- Sort Options -->
                <div class="shop-controls search-controls">
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
                <?php if ($pagination && $pagination['total_pages'] > 1): ?>
                    <div class="pagination">
                        <?php if ($pagination['has_prev']): ?>
                            <a href="?q=<?php echo urlencode($search_query); ?>&page=<?php echo $pagination['prev_page']; ?>&sort=<?php echo $sort; ?>" class="pagination-link">
                                Previous
                            </a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                            <a href="?q=<?php echo urlencode($search_query); ?>&page=<?php echo $i; ?>&sort=<?php echo $sort; ?>" 
                               class="pagination-link <?php echo $i === $pagination['current_page'] ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($pagination['has_next']): ?>
                            <a href="?q=<?php echo urlencode($search_query); ?>&page=<?php echo $pagination['next_page']; ?>&sort=<?php echo $sort; ?>" class="pagination-link">
                                Next
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php elseif ($search_query && $total_products === 0): ?>
                <!-- No Results -->
                <div class="no-products no-results">
                    <h3>No products found</h3>
                    <p>We couldn't find any products matching "<?php echo htmlspecialchars($search_query); ?>"</p>
                    <div class="search-suggestions">
                        <h4>Try searching for:</h4>
                        <ul>
                            <li><a href="?q=dresses">Dresses</a></li>
                            <li><a href="?q=tops">Tops</a></li>
                            <li><a href="?q=outerwear">Outerwear</a></li>
                            <li><a href="?q=accessories">Accessories</a></li>
                        </ul>
                    </div>
                    <a href="../shop/" class="btn btn-primary">Browse All Products</a>
                </div>
            <?php else: ?>
                <!-- Search Suggestions -->
                <div class="search-suggestions-section">
                    <h3>Popular Searches</h3>
                    <div class="popular-searches">
                        <a href="?q=vintage" class="search-tag">Vintage</a>
                        <a href="?q=designer" class="search-tag">Designer</a>
                        <a href="?q=silk" class="search-tag">Silk</a>
                        <a href="?q=leather" class="search-tag">Leather</a>
                        <a href="?q=denim" class="search-tag">Denim</a>
                        <a href="?q=formal" class="search-tag">Formal</a>
                        <a href="?q=casual" class="search-tag">Casual</a>
                        <a href="?q=evening" class="search-tag">Evening</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>



<?php include_once '../includes/footer.php'; ?>