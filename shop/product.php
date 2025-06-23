<?php
$slug = $_GET['slug'] ?? '';
if (!$slug) {
    header('Location: index.php');
    exit;
}

require_once '../includes/functions.php';

$product = getProductBySlug($slug);
if (!$product) {
    header('Location: index.php');
    exit;
}

$page_title = $product['name'];
$page_description = $product['short_description'] ?: substr(strip_tags($product['description']), 0, 160);

include_once '../includes/header.php';

// Get product images
$product_images = getProductImages($product['id']);
$image_urls = [];

if (!empty($product_images)) {
    foreach ($product_images as $image) {
        $image_urls[] = '../' . $image['image_path'];
    }
} else {
    // Fallback to default images
    $image_urls = [
        "../images/products/product_1.jpg",
        "../images/products/product_2.jpg",
        "../images/products/product_3.jpg"
    ];
}

$current_price = $product['sale_price'] ?: $product['price'];
$discount_percent = $product['sale_price'] ? calculateDiscountPercentage($product['price'], $product['sale_price']) : 0;

// Get related products
$related_products = getProducts(['category_id' => $product['category_id'], 'limit' => 4]);
$related_products = array_filter($related_products, function($p) use ($product) {
    return $p['id'] !== $product['id'];
});
?>

<main class="main-content">
    <!-- Product Details -->
    <section class="product-details">
        <div class="container">
            <div class="product-layout">
                <!-- Product Images -->
                <div class="product-images">
                    <div class="main-image">
                        <img id="main-product-image" src="<?php echo $image_urls[0]; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <?php if ($discount_percent > 0): ?>
                            <span class="discount-badge"><?php echo $discount_percent; ?>% OFF</span>
                        <?php endif; ?>
                    </div>
                    <div class="image-thumbnails">
                        <?php foreach ($image_urls as $index => $url): ?>
                            <div class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" onclick="changeMainImage('<?php echo $url; ?>', this)">
                                <img src="<?php echo $url; ?>" alt="<?php echo htmlspecialchars($product['name']); ?> - Thumbnail <?php echo $index + 1; ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="product-info">
                    <div class="product-header">
                        <p class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></p>
                        <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                        <div class="product-rating">
                            <div class="stars">
                                <span>★★★★★</span>
                            </div>
                            <span class="rating-text">(Excellent Condition)</span>
                        </div>
                    </div>

                    <div class="product-price">
                        <span class="current-price"><?php echo formatPrice($current_price); ?></span>
                        <?php if ($product['sale_price']): ?>
                            <span class="original-price"><?php echo formatPrice($product['price']); ?></span>
                        <?php endif; ?>
                    </div>

                    <p class="product-short-description">
                        <?php echo htmlspecialchars($product['short_description']); ?>
                    </p>

                    <div class="product-actions">
                        <div class="quantity-selector">
                            <button type="button" onclick="decreaseQuantity()">-</button>
                            <input type="text" id="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" readonly>
                            <button type="button" onclick="increaseQuantity()">+</button>
                        </div>
                        <button class="btn btn-primary add-to-cart-btn" onclick="addToCart(<?php echo $product['id']; ?>)">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                            Add to Cart
                        </button>
                        <button class="btn btn-secondary wishlist-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                            Add to Wishlist
                        </button>
                    </div>

                    <div class="shipping-info">
                        <div class="shipping-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 3h5v5"></path><path d="M8 3H3v5"></path><path d="M12 22v-8.3a4 4 0 0 0-4-4H3"></path><path d="M21 9.5V8a2 2 0 0 0-2-2h-7"></path></svg>
                            <span>Free shipping on orders over ₹1,999</span>
                        </div>
                        <div class="shipping-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9,22 9,12 15,12 15,22"></polyline></svg>
                            <span>Cash on Delivery available</span>
                        </div>
                        <div class="shipping-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"></path><circle cx="12" cy="12" r="10"></circle></svg>
                            <span>7-day return policy</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Description -->
    <section class="product-description-section">
        <div class="container">
            <div class="description-tabs">
                <div class="tab-buttons">
                    <button class="tab-btn active" onclick="showTab('description')">Description</button>
                    <button class="tab-btn" onclick="showTab('care')">Care Instructions</button>
                    <button class="tab-btn" onclick="showTab('shipping')">Shipping & Returns</button>
                </div>
                
                <div class="tab-content">
                    <div id="description" class="tab-pane active">
                        <div class="description-content">
                            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                        </div>
                    </div>
                    
                    <div id="care" class="tab-pane">
                        <div class="care-content">
                            <?php if ($product['care_instructions']): ?>
                                <?php echo nl2br(htmlspecialchars($product['care_instructions'])); ?>
                            <?php else: ?>
                                <p>General care instructions for pre-loved clothing:</p>
                                <ul>
                                    <li>Follow the care label instructions</li>
                                    <li>Wash in cold water when possible</li>
                                    <li>Air dry to preserve fabric quality</li>
                                    <li>Store in a cool, dry place</li>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div id="shipping" class="tab-pane">
                        <div class="shipping-content">
                            <h4>Shipping Information</h4>
                            <ul>
                                <li>Free shipping on orders over ₹1,999</li>
                                <li>Standard shipping: 3-5 business days</li>
                                <li>Cash on Delivery available</li>
                                <li>Secure packaging for all items</li>
                            </ul>
                            
                            <h4>Return Policy</h4>
                            <ul>
                                <li>7-day return policy</li>
                                <li>Items must be in original condition</li>
                                <li>Return shipping costs apply</li>
                                <li>Refunds processed within 5-7 business days</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <?php if (!empty($related_products)): ?>
        <section class="related-products">
            <div class="container">
                <h2 class="section-title">
                    <span class="title-line">You Might Also Like</span>
                </h2>
                <div class="products-grid">
                    <?php foreach (array_slice($related_products, 0, 4) as $related): ?>
                        <?php
                        $related_image = "../images/products/product_" . rand(1, 3) . ".jpg";
                        $related_price = $related['sale_price'] ?: $related['price'];
                        ?>
                        <div class="product-card">
                            <div class="product-image">
                                <img src="<?php echo $related_image; ?>" alt="<?php echo htmlspecialchars($related['name']); ?>">
                                <div class="product-overlay">
                                    <a href="product.php?slug=<?php echo $related['slug']; ?>" class="quick-view-btn">View Product</a>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">
                                    <a href="product.php?slug=<?php echo $related['slug']; ?>">
                                        <?php echo htmlspecialchars($related['name']); ?>
                                    </a>
                                </h3>
                                <p class="product-brand"><?php echo htmlspecialchars($related['brand']); ?></p>
                                <div class="product-price">
                                    <span class="current-price"><?php echo formatPrice($related_price); ?></span>
                                    <?php if ($related['sale_price']): ?>
                                        <span class="original-price"><?php echo formatPrice($related['price']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>

<script>
function changeMainImage(src, element) {
    document.getElementById('main-product-image').src = src;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
    });
    element.classList.add('active');
}

function increaseQuantity() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max'));
    const current = parseInt(input.value);
    if (current < max) {
        input.value = current + 1;
    }
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    const current = parseInt(input.value);
    if (current > 1) {
        input.value = current - 1;
    }
}

function showTab(tabName) {
    // Hide all tab panes
    document.querySelectorAll('.tab-pane').forEach(pane => {
        pane.classList.remove('active');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName).classList.add('active');
    
    // Add active class to clicked button
    document.querySelector(`.tab-btn[onclick="showTab('${tabName}')"]`).classList.add('active');
}

function addToCart(productId) {
    const quantity = parseInt(document.getElementById('quantity').value);
    
    fetch('../cart/ajax/add-to-cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
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