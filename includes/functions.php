<?php
require_once 'config/database.php';
require_once 'config/config.php';
require_once 'config/session.php';

// Sanitize input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Generate slug from string
function generateSlug($string) {
    $slug = strtolower(trim($string));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

// Format price
function formatPrice($price) {
    return '₹' . number_format($price, 0);
}

// Calculate discount percentage
function calculateDiscountPercentage($original_price, $sale_price) {
    if ($original_price <= 0) return 0;
    return round((($original_price - $sale_price) / $original_price) * 100);
}

// Generate order number
function generateOrderNumber() {
    return 'SB' . date('Y') . date('m') . date('d') . rand(1000, 9999);
}

// Hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Upload image
function uploadImage($file, $directory, $max_size = MAX_IMAGE_SIZE) {
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return false;
    }
    
    $file_size = $file['size'];
    $file_tmp = $file['tmp_name'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Validate file size
    if ($file_size > $max_size) {
        return false;
    }
    
    // Validate file type
    if (!in_array($file_ext, ALLOWED_IMAGE_TYPES)) {
        return false;
    }
    
    // Generate unique filename
    $filename = uniqid() . '.' . $file_ext;
    $upload_path = $directory . $filename;
    
    // Create directory if it doesn't exist
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
    
    // Move uploaded file
    if (move_uploaded_file($file_tmp, $upload_path)) {
        return $filename;
    }
    
    return false;
}

// Get categories
function getCategories($parent_id = null) {
    $sql = "SELECT * FROM categories WHERE status = 'active'";
    $params = [];
    
    if ($parent_id !== null) {
        $sql .= " AND parent_id = ?";
        $params[] = $parent_id;
    } else {
        $sql .= " AND parent_id IS NULL";
    }
    
    $sql .= " ORDER BY name";
    
    return fetchAll($sql, $params);
}

// Get category by slug
function getCategoryBySlug($slug) {
    return fetchOne("SELECT * FROM categories WHERE slug = ? AND status = 'active'", [$slug]);
}

// Get products
function getProducts($filters = []) {
    $sql = "SELECT p.*, c.name as category_name FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.status = 'active'";
    $params = [];
    
    if (isset($filters['category_id'])) {
        $sql .= " AND p.category_id = ?";
        $params[] = $filters['category_id'];
    }
    
    if (isset($filters['featured'])) {
        $sql .= " AND p.featured = ?";
        $params[] = $filters['featured'];
    }
    
    if (isset($filters['search'])) {
        $sql .= " AND (p.name LIKE ? OR p.description LIKE ? OR p.brand LIKE ?)";
        $search_term = '%' . $filters['search'] . '%';
        $params[] = $search_term;
        $params[] = $search_term;
        $params[] = $search_term;
    }
    
    $sql .= " ORDER BY p.created_at DESC";
    
    if (isset($filters['limit'])) {
        $sql .= " LIMIT ?";
        $params[] = $filters['limit'];
    }
    
    return fetchAll($sql, $params);
}

// Get product by slug
function getProductBySlug($slug) {
    return fetchOne("SELECT p.*, c.name as category_name FROM products p 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     WHERE p.slug = ? AND p.status = 'active'", [$slug]);
}

// Get product images
function getProductImages($product_id) {
    return fetchAll("SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, sort_order", [$product_id]);
}

// Get primary product image
function getPrimaryProductImage($product_id) {
    $image = fetchOne("SELECT * FROM product_images WHERE product_id = ? AND is_primary = 1", [$product_id]);
    if (!$image) {
        $image = fetchOne("SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order LIMIT 1", [$product_id]);
    }
    return $image;
}

// Send email (placeholder for future implementation)
function sendEmail($to, $subject, $message, $headers = '') {
    // This is a placeholder. In production, use a proper email service
    return mail($to, $subject, $message, $headers);
}

// Log activity
function logActivity($user_type, $user_id, $action, $description = '') {
    $sql = "INSERT INTO activity_log (user_type, user_id, action, description, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $params = [
        $user_type,
        $user_id,
        $action,
        $description,
        $_SERVER['REMOTE_ADDR'] ?? '',
        $_SERVER['HTTP_USER_AGENT'] ?? ''
    ];
    
    executeQuery($sql, $params);
}

// Redirect function
function redirect($url) {
    header("Location: $url");
    exit;
}

// Flash messages
function setFlashMessage($type, $message) {
    $_SESSION['flash_messages'][] = ['type' => $type, 'message' => $message];
}

function getFlashMessages() {
    $messages = $_SESSION['flash_messages'] ?? [];
    unset($_SESSION['flash_messages']);
    return $messages;
}

// Pagination
function paginate($total_items, $items_per_page, $current_page) {
    $total_pages = ceil($total_items / $items_per_page);
    $offset = ($current_page - 1) * $items_per_page;
    
    return [
        'total_pages' => $total_pages,
        'current_page' => $current_page,
        'offset' => $offset,
        'has_prev' => $current_page > 1,
        'has_next' => $current_page < $total_pages,
        'prev_page' => $current_page - 1,
        'next_page' => $current_page + 1
    ];
}
?>