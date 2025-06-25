<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<header class="header">
    <div class="container">
        <div class="nav-menu-container">
            <a href="index.php" class="logo">
                <img src="img/logo.png" alt="ShaiBha">
            </a>

            <!-- Desktop Navigation -->
            <nav class="nav-menu" id="desktop-nav" aria-label="Main Navigation">
                <ul class="nav-list">
                    <li class="nav-item"><a href="index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Home</a></li>
                    <li class="nav-item dropdown">
                        <a href="shop.php" class="nav-link dropdown-toggle <?php echo ($current_page == 'shop.php' || $current_page == 'single-product.php') ? 'active' : ''; ?>">Shop</a>
                        <div class="dropdown-menu">
                            <a href="shop.php" class="dropdown-item">Shop</a>
                            <a href="cart.php" class="dropdown-item">Cart</a>
                            <a href="checkout.php" class="dropdown-item">Checkout</a>
                        </div>
                    </li>
                    <li class="nav-item"><a href="about.php" class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">About</a></li>
                    <li class="nav-item dropdown">
                        <a href="services.php" class="nav-link dropdown-toggle <?php echo ($current_page == 'services.php') ? 'active' : ''; ?>">Services</a>
                        <div class="dropdown-menu">
                            <a href="service-one.php" class="dropdown-item">Service One</a>
                            <a href="service-two.php" class="dropdown-item">Service Two</a>
                        </div>
                    </li>
                    <li class="nav-item"><a href="contact.php" class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">Contact</a></li>
                </ul>
            </nav>

            <div class="header-actions">
                <button class="search-btn" aria-label="Search"><i class="fas fa-search"></i></button>
                <?php if (isset($_SESSION['customer_id'])): ?>
                    <a href="customer/profile.php" class="user-link" aria-label="My Account"><i class="fas fa-user"></i></a>
                <?php else: ?>
                    <a href="customer/login.php" class="user-link" aria-label="My Account"><i class="fas fa-user"></i></a>
                    <a href="customer/login.php" class="user-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            <polyline points="10,17 15,12 10,7"></polyline>
                            <line x1="15" y1="12" x2="3" y2="12"></line>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="cart-toggle">
                <a href="cart/" class="cart-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <span class="cart-count" id="cart-count">0</span>
                </a>
            </div>

            <!-- Modern Mobile Navigation -->
            <div class="modern-mobile-nav">
                <!-- Menu Toggle -->
                <button class="modern-menu-toggle" id="modern-menu-toggle" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="menu-line"></span>
                    <span class="menu-line"></span>
                    <span class="menu-line"></span>
                </button>

                <!-- Navigation Drawer -->
                <nav class="modern-nav-drawer" id="modern-nav-drawer">
                    <!-- Branding -->
                    <div class="nav-brand">
                        <a href="/" class="brand-logo">
                            <img src="/assets/images/logo.png" alt="Brand Logo" class="logo-img">
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <ul class="modern-nav-list">
                        <li class="nav-item">
                            <a href="/" class="nav-link" data-text="Home">
                                <span class="link-text">Home</span>
                                <span class="link-underline"></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/shop" class="nav-link" data-text="Shop">
                                <span class="link-text">Shop</span>
                                <span class="link-underline"></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/about" class="nav-link" data-text="About">
                                <span class="link-text">About</span>
                                <span class="link-underline"></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/contact" class="nav-link" data-text="Contact">
                                <span class="link-text">Contact</span>
                                <span class="link-underline"></span>
                            </a>
                        </li>
                        <?php if (isset($_SESSION['customer_id'])): ?>
                        <li class="nav-item account-item">
                            <a href="/customer/profile.php" class="nav-link" data-text="My Account">
                                <span class="link-text">My Account</span>
                                <span class="link-underline"></span>
                            </a>
                        </li>
                        <?php else: ?>
                        <li class="nav-item account-item">
                            <a href="/customer/login.php" class="nav-link" data-text="Login / Register">
                                <span class="link-text">Login / Register</span>
                                <span class="link-underline"></span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>

                    <!-- Social Links -->
                    <div class="nav-social">
                        <a href="#" class="social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </nav>
                <div class="modern-nav-overlay" id="modern-nav-overlay"></div>
            </div>

            <!-- Search Overlay -->
            <div class="search-overlay" id="search-overlay">
                <div class="search-container">
                    <form class="search-form" action="shop/search.php" method="GET">
                        <input type="text" name="q" class="search-input" placeholder="Search for products..." autocomplete="off" required>
                        <button type="submit" class="search-submit">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                        </button>
                    </form>
                    <button class="search-close" id="search-close">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search Overlay -->
    <div class="search-overlay" id="search-overlay">
        <div class="search-container">
            <form class="search-form" action="shop/search.php" method="GET">
                <input type="text" name="q" class="search-input" placeholder="Search for products..." autocomplete="off" required>
                <button type="submit" class="search-submit">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </button>
            </form>
            <button class="search-close" id="search-close">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
    </div>
</nav>