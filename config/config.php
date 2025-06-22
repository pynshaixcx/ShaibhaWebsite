<?php
// Site configuration
define('BASE_URL', 'https://shaibha.yzz.me/');
define('SITE_NAME', 'ShaiBha');
define('SITE_DESCRIPTION', 'Pre-loved Fashion Reimagined');
define('SITE_URL', 'https://shaibha.yzz.me');
define('SITE_EMAIL', 'hello@shaibha.com');
define('SITE_PHONE', '+91 9876543210');

// Paths
define('ROOT_PATH', dirname(__DIR__));
define('UPLOAD_PATH', ROOT_PATH . '/uploads/');
define('PRODUCT_IMAGE_PATH', ROOT_PATH . '/images/products/');

// Settings
define('PRODUCTS_PER_PAGE', 12);
define('SHIPPING_COST', 99.00);
define('FREE_SHIPPING_THRESHOLD', 1999.00);
define('MAX_COD_AMOUNT', 10000.00);

// Security
define('CSRF_TOKEN_NAME', 'csrf_token');
define('SESSION_LIFETIME', 3600); // 1 hour

// Email settings (for future implementation)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');

// Image settings
define('MAX_IMAGE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'webp']);
define('THUMBNAIL_WIDTH', 300);
define('THUMBNAIL_HEIGHT', 300);

// Pagination
define('ADMIN_ITEMS_PER_PAGE', 20);

// Order statuses
define('ORDER_STATUS_PENDING', 'pending');
define('ORDER_STATUS_CONFIRMED', 'confirmed');
define('ORDER_STATUS_PROCESSING', 'processing');
define('ORDER_STATUS_SHIPPED', 'shipped');
define('ORDER_STATUS_DELIVERED', 'delivered');
define('ORDER_STATUS_CANCELLED', 'cancelled');

// Payment statuses
define('PAYMENT_STATUS_PENDING', 'pending');
define('PAYMENT_STATUS_PAID', 'paid');
define('PAYMENT_STATUS_FAILED', 'failed');
define('PAYMENT_STATUS_REFUNDED', 'refunded');
?>