<?php
session_start();
include_once 'includes/header.php';
?>

<main class="main-content">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">
                    <span class="word" data-delay="0">ShaiBha</span>
                </h1>
                <p class="hero-subtitle">
                    <span class="word" data-delay="200">Pre-loved</span>
                    <span class="word" data-delay="400">Fashion</span>
                    <span class="word" data-delay="600">Reimagined</span>
                </p>
                <div class="hero-description">
                    <p>Discover unique, sustainable fashion pieces that tell a story. Every garment in our collection has been carefully curated for quality, style, and character.</p>
                </div>
                <div class="hero-actions">
                    <a href="shop/" class="btn btn-primary">
                        <span>Shop Collection</span>
                        <i class="icon-arrow-right"></i>
                    </a>
                    <a href="about.php" class="btn btn-secondary">
                        <span>Our Story</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="hero-image">
            <div class="image-container">
                <img src="images/products/product_1.jpg" alt="Curated Fashion Collection" loading="lazy">
            </div>
        </div>
    </section>

    <!-- Featured Categories -->
    <section class="featured-categories">
        <div class="container">
            <h2 class="section-title">
                <span class="title-line">Curated Collections</span>
            </h2>
            <div class="categories-grid">
                <div class="category-card" data-category="dresses">
                    <div class="card-inner">
                        <div class="card-image">
                            <img src="images/products/product_1.jpg" alt="Dresses Collection">
                        </div>
                        <div class="card-content">
                            <h3>Dresses</h3>
                            <p>Elegant pieces for every occasion</p>
                            <span class="card-link">Explore Collection</span>
                        </div>
                    </div>
                </div>
                <div class="category-card" data-category="tops">
                    <div class="card-inner">
                        <div class="card-image">
                            <img src="images/products/product_2.jpg" alt="Tops Collection">
                        </div>
                        <div class="card-content">
                            <h3>Tops & Blouses</h3>
                            <p>Versatile pieces for your wardrobe</p>
                            <span class="card-link">Explore Collection</span>
                        </div>
                    </div>
                </div>
                <div class="category-card" data-category="outerwear">
                    <div class="card-inner">
                        <div class="card-image">
                            <img src="images/products/product_3.jpg" alt="Outerwear Collection">
                        </div>
                        <div class="card-content">
                            <h3>Outerwear</h3>
                            <p>Timeless coats and jackets</p>
                            <span class="card-link">Explore Collection</span>
                        </div>
                    </div>
                </div>
                <div class="category-card" data-category="accessories">
                    <div class="card-inner">
                        <div class="card-image">
                            <img src="images/products/product_1.jpg" alt="Accessories Collection">
                        </div>
                        <div class="card-content">
                            <h3>Accessories</h3>
                            <p>Perfect finishing touches</p>
                            <span class="card-link">Explore Collection</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="featured-products">
        <div class="container">
            <h2 class="section-title">
                <span class="title-line">Featured Pieces</span>
            </h2>
            <div class="products-grid">
                <!-- Sample products -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="images/products/product_1.jpg" alt="Vintage Silk Dress">
                        <div class="product-overlay">
                            <a href="shop/product.php?slug=vintage-silk-dress" class="quick-view-btn">View Details</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title"><a href="shop/product.php?slug=vintage-silk-dress">Vintage Silk Dress</a></h3>
                        <p class="product-condition">Excellent Condition</p>
                        <div class="product-price">
                            <span class="current-price">₹2,499</span>
                            <span class="original-price">₹4,999</span>
                        </div>
                    </div>
                </div>
                
                <div class="product-card">
                    <div class="product-image">
                        <img src="images/products/product_2.jpg" alt="Designer Blazer">
                        <div class="product-overlay">
                            <a href="shop/product.php?slug=designer-blazer" class="quick-view-btn">View Details</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title"><a href="shop/product.php?slug=designer-blazer">Designer Blazer</a></h3>
                        <p class="product-condition">Very Good Condition</p>
                        <div class="product-price">
                            <span class="current-price">₹3,999</span>
                            <span class="original-price">₹7,999</span>
                        </div>
                    </div>
                </div>
                
                <div class="product-card">
                    <div class="product-image">
                        <img src="images/products/product_3.jpg" alt="Leather Handbag">
                        <div class="product-overlay">
                            <a href="shop/product.php?slug=leather-handbag" class="quick-view-btn">View Details</a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title"><a href="shop/product.php?slug=leather-handbag">Leather Handbag</a></h3>
                        <p class="product-condition">Good Condition</p>
                        <div class="product-price">
                            <span class="current-price">₹1,899</span>
                            <span class="original-price">₹3,499</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sustainability Message -->
    <section class="sustainability-section">
        <div class="container">
            <div class="sustainability-content">
                <div class="content-text">
                    <h2 class="section-title">
                        <span class="title-line">Sustainable Fashion</span>
                    </h2>
                    <p>Every purchase contributes to a more sustainable future. By choosing pre-loved fashion, you're not just getting unique pieces – you're making an environmental statement.</p>
                    <ul class="sustainability-list">
                        <li>Reduce textile waste</li>
                        <li>Extend garment lifecycle</li>
                        <li>Support circular economy</li>
                        <li>Discover unique pieces</li>
                    </ul>
                    <a href="about.php" class="btn btn-outline">Learn More</a>
                </div>
                <div class="content-image">
                    <div class="image-container">
                        <img src="images/products/product_3.jpg" alt="Sustainable Fashion">
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include_once 'includes/footer.php'; ?>