<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ShaiBha' : 'ShaiBha - Pre-loved Fashion Reimagined'; ?></title>
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'Discover unique, sustainable fashion pieces. Curated pre-loved clothing with character and style.'; ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/responsive.css">
    
    <?php 
    // Include shop.css for shop pages
    $current_path = $_SERVER['SCRIPT_NAME'] ?? '';
    if (strpos($current_path, '/shop/') !== false): 
    ?>
    <link rel="stylesheet" href="/css/shop.css">
    <?php endif; ?>
    
    <?php if (isset($additional_styles)): ?>
        <?php foreach($additional_styles as $style): ?>
        <link rel="stylesheet" href="<?php echo $style; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/images/favicon.svg">
</head>
<body>
    <?php
    // Get all categories for the dropdown menu
    require_once __DIR__ . '/functions.php';
    $nav_categories = getCategories();
    ?>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <a href="/" class="brand-link">
                    <span class="brand-text">ShaiBha</span>
                </a>
            </div>
            
            <div class="mobile-menu-toggle">
                <button class="mobile-menu-btn" id="mobile-menu-btn" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>

            <div class="nav-menu" id="nav-menu">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="/" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="/shop/" class="nav-link dropdown-toggle">Shop</a>
                        <div class="dropdown-menu">
                            <a href="/shop/" class="dropdown-link">All Products</a>
                            <?php foreach ($nav_categories as $category): ?>
                            <a href="/shop/category.php?cat=<?php echo $category['slug']; ?>" class="dropdown-link"><?php echo htmlspecialchars($category['name']); ?></a>
                            <?php endforeach; ?>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="/about.php" class="nav-link">About</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="/services/" class="nav-link dropdown-toggle">Services</a>
                        <div class="dropdown-menu">
                            <a href="/services/size-guide.php" class="dropdown-link">Size Guide</a>
                            <a href="/services/care-instructions.php" class="dropdown-link">Care Instructions</a>
                            <a href="/services/styling-tips.php" class="dropdown-link">Styling Tips</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="/pages/contact.php" class="nav-link">Contact</a>
                    </li>
                </ul>
            </div>
            
            <div class="nav-actions">
                <div class="search-toggle">
                    <button class="search-btn" id="search-btn">
                        <span class="sr-only">Search</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="user-menu">
                    <?php if (isset($_SESSION['customer_id'])): ?>
                        <a href="/customer/profile.php" class="user-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </a>
                    <?php else: ?>
                        <a href="/customer/login.php" class="user-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                <polyline points="10,17 15,12 10,7"></polyline>
                                <line x1="15" y1="12" x2="3" y2="12"></line>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
                
                <div class="cart-toggle">
                    <a href="/cart/" class="cart-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        <span class="cart-count" id="cart-count">0</span>
                    </a>
                </div>
            </div>
            
            <!-- Search Overlay -->
            <div class="search-overlay" id="search-overlay">
                <div class="search-container">
                    <form class="search-form" action="/shop/search.php" method="GET">
                        <input type="text" name="q" class="search-input" placeholder="Search for products..." autocomplete="off" required>
                        <button type="submit" class="search-submit">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                        </button>
                    </form>
                    <button class="search-close" id="search-close">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update cart count
    function updateCartCount() {
        fetch('/cart/ajax/get-cart-count.php')
            .then(response => response.json())
            .then(data => {
                const cartCount = document.getElementById('cart-count');
                if (cartCount) {
                    cartCount.textContent = data.count;
                    if (data.count > 0) {
                        cartCount.classList.add('has-items');
                    } else {
                        cartCount.classList.remove('has-items');
                    }
                }
            })
            .catch(error => console.error('Error fetching cart count:', error));
    }
    
    // Initial cart count update
    updateCartCount();
    
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const navMenu = document.getElementById('nav-menu');
    
    if (mobileMenuBtn && navMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
            document.body.classList.toggle('menu-open');
        });
    }
    
    // Dropdown toggles for mobile
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            if (window.innerWidth < 992) {
                e.preventDefault();
                const parent = this.parentElement;
                const dropdown = parent.querySelector('.dropdown-menu');
                
                // Close all other dropdowns
                document.querySelectorAll('.dropdown-menu.active').forEach(menu => {
                    if (menu !== dropdown) {
                        menu.classList.remove('active');
                    }
                });
                
                // Toggle this dropdown
                dropdown.classList.toggle('active');
            }
        });
    });
    
    // Search toggle
    const searchBtn = document.getElementById('search-btn');
    const searchOverlay = document.getElementById('search-overlay');
    const searchClose = document.getElementById('search-close');
    
    if (searchBtn && searchOverlay && searchClose) {
        searchBtn.addEventListener('click', function() {
            searchOverlay.classList.add('active');
            document.querySelector('.search-input').focus();
        });
        
        searchClose.addEventListener('click', function() {
            searchOverlay.classList.remove('active');
        });
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
                searchOverlay.classList.remove('active');
            }
        });
        
        // Close on overlay click
        searchOverlay.addEventListener('click', function(e) {
            if (e.target === searchOverlay) {
                searchOverlay.classList.remove('active');
            }
        });
    }
});
</script>