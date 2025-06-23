// Main JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    
    // Navigation functionality
    initializeNavigation();
    
    // Search functionality
    initializeSearch();
    
    // Mobile menu
    initializeMobileMenu();
    
    // Typography animations
    initializeAnimations();
    
    // Product interactions
    initializeProductInteractions();
    
    // Shop page controls
    initializeShopControls();
    
    // Cart functionality
    initializeCart();
    
    // Smooth scrolling
    initializeSmoothScrolling();
    
    // Scroll effects
    initializeScrollEffects();
});

// Navigation initialization
function initializeNavigation() {
    const navbar = document.querySelector('.navbar');
    let lastScroll = 0;
    
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll <= 0) {
            navbar.classList.remove('scrolled');
            return;
        }
        
        if (currentScroll > lastScroll && !navbar.classList.contains('scroll-down')) {
            // Scrolling down
            navbar.classList.remove('scroll-up');
            navbar.classList.add('scroll-down');
        } else if (currentScroll < lastScroll && navbar.classList.contains('scroll-down')) {
            // Scrolling up
            navbar.classList.remove('scroll-down');
            navbar.classList.add('scroll-up');
        }
        
        navbar.classList.add('scrolled');
        lastScroll = currentScroll;
    });
    
    // Active nav link highlighting
    const navLinks = document.querySelectorAll('.nav-link');
    const currentPath = window.location.pathname;
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath || 
            (currentPath === '/' && link.getAttribute('href') === '/')) {
            link.classList.add('active');
        }
    });
}

// Search functionality
function initializeSearch() {
    const searchBtn = document.getElementById('search-btn');
    const searchOverlay = document.getElementById('search-overlay');
    const searchClose = document.getElementById('search-close');
    const searchInput = document.querySelector('.search-input');
    
    if (searchBtn && searchOverlay) {
        searchBtn.addEventListener('click', () => {
            searchOverlay.classList.add('active');
            setTimeout(() => {
                searchInput.focus();
            }, 300);
        });
        
        searchClose.addEventListener('click', () => {
            searchOverlay.classList.remove('active');
        });
        
        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
                searchOverlay.classList.remove('active');
            }
        });
        
        // Close on overlay click
        searchOverlay.addEventListener('click', (e) => {
            if (e.target === searchOverlay) {
                searchOverlay.classList.remove('active');
            }
        });
    }
}

// Mobile menu functionality
function initializeMobileMenu() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const navMenu = document.getElementById('nav-menu');
    const body = document.body;

    if (mobileMenuBtn && navMenu) {
        // Main menu toggle
        mobileMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isActive = navMenu.classList.toggle('active');
            mobileMenuBtn.classList.toggle('active');
            body.classList.toggle('menu-open', isActive);
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (navMenu.classList.contains('active') && !navMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                navMenu.classList.remove('active');
                mobileMenuBtn.classList.remove('active');
                body.classList.remove('menu-open');
            }
        });

        // Handle dropdowns within the mobile menu
        const dropdownToggles = navMenu.querySelectorAll('.dropdown-toggle');
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                // Only activate for mobile
                if (window.innerWidth < 992) {
                    e.preventDefault();
                    const dropdownMenu = this.nextElementSibling;
                    
                    if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                        // Toggle the active class on the dropdown menu
                        dropdownMenu.classList.toggle('active');
                        // Toggle active class on the parent nav-item for styling
                        this.parentElement.classList.toggle('active');
                    }
                }
            });
        });

        // Close menu when a link is clicked
        navMenu.addEventListener('click', function(e) {
            // Check if the clicked element is a link, but not a dropdown toggle
            if (e.target.tagName === 'A' && !e.target.classList.contains('dropdown-toggle')) {
                navMenu.classList.remove('active');
                mobileMenuBtn.classList.remove('active');
                body.classList.remove('menu-open');
            }
        });
    }
}

// Typography animations
function initializeAnimations() {
    // Animate words with delay
    const words = document.querySelectorAll('.word[data-delay]');
    words.forEach(word => {
        const delay = parseInt(word.getAttribute('data-delay'));
        word.style.opacity = '0';
        word.style.transform = 'translateY(20px)';
        word.style.animation = `slideInUp 0.6s forwards ${delay}ms`;
    });
    
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    const animateElements = document.querySelectorAll(
        '.section-title, .category-card, .product-card, .sustainability-content'
    );
    
    animateElements.forEach(el => {
        observer.observe(el);
    });
}

// Product interactions
function initializeProductInteractions() {
    // Event delegation for category cards
    document.body.addEventListener('click', function(e) {
        const categoryCard = e.target.closest('.category-card');
        if (categoryCard) {
            const category = categoryCard.dataset.category;
            if (category) {
                window.location.href = `shop/category.php?cat=${category}`;
            }
        }
    });

    // Event delegation for add to cart buttons
    document.body.addEventListener('click', function(e) {
        if (e.target.matches('.add-to-cart-btn')) {
            e.preventDefault();
            const productCard = e.target.closest('.product-card');
            const productId = productCard ? productCard.dataset.productId : e.target.dataset.productId;
            if (productId) {
                addToCart(productId);
            }
        }
    });
}

// Shop page controls (sorting, etc.)
function initializeShopControls() {
    const sortSelect = document.getElementById('sort');
    if (sortSelect) {
        sortSelect.addEventListener('change', (e) => {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', e.target.value);
            url.searchParams.set('page', '1'); // Reset to first page on sort
            window.location.href = url.toString();
        });
    }
}

// Cart functionality
function initializeCart() {
    // Fetch initial cart count on page load
    fetch(getAjaxUrl('get-cart-count.php'))
        .then(response => response.json())
        .then(data => {
            if (data && typeof data.cart_count !== 'undefined') {
                updateCartCount(data.cart_count);
            }
        })
        .catch(error => {
            // This can fail silently if user has no cart session yet
            // console.error('Error loading initial cart count:', error);
        });
}

// --- Global Utility Functions ---

function getAjaxUrl(endpoint) {
    const isShopPage = window.location.pathname.includes('/shop/');
    const prefix = isShopPage ? '../' : '';
    return `${prefix}cart/ajax/${endpoint}`;
}

function updateCartCount(count) {
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
        cartCountElement.style.display = count > 0 ? 'flex' : 'none';
    }
}

function addToCart(productId, quantity = 1) {
    fetch(getAjaxUrl('add-to-cart.php'), {
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
            updateCartCount(data.cart_count);
            showNotification('Product added to cart!', 'success');
        } else {
            showNotification(data.message || 'Error adding product to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding product to cart', 'error');
    });
}

// Smooth scrolling
function initializeSmoothScrolling() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            
            const targetId = link.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const offsetTop = targetElement.offsetTop - 80; // Account for navbar
                
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Scroll effects
function initializeScrollEffects() {
    // Parallax effect for hero image
    const heroImage = document.querySelector('.hero-image');
    
    if (heroImage) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallax = scrolled * 0.5;
            
            heroImage.style.transform = `translateY(${parallax}px)`;
        });
    }
    
    // Fade in animation on scroll
    const fadeElements = document.querySelectorAll('.fade-in');
    
    const fadeObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1
    });
    
    fadeElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        fadeObserver.observe(el);
    });
}

// Utility functions
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Style the notification
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        font-weight: 500;
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Debounce function for performance
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Add debounced scroll listener
const debouncedScroll = debounce(() => {
    // Any expensive scroll operations can go here
}, 10);

window.addEventListener('scroll', debouncedScroll);

// Page loading animation
window.addEventListener('load', () => {
    document.body.classList.add('loaded');
    
    // Remove loading class after animations complete
    setTimeout(() => {
        document.body.classList.remove('loading');
    }, 1000);
});

// Add loading styles
document.head.insertAdjacentHTML('beforeend', `
    <style>
        .loading * {
            animation-play-state: paused !important;
        }
        
        .loaded .hero-title .word,
        .loaded .hero-subtitle .word,
        .loaded .hero-description,
        .loaded .hero-actions,
        .loaded .hero-image {
            animation-play-state: running;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
`);

// Initialize page as loading
document.body.classList.add('loading');