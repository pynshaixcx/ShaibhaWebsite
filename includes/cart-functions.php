<?php
require_once 'functions.php';

// Add item to cart
function addToCart($product_id, $quantity = 1, $session_id = null, $customer_id = null) {
    if (!$session_id) {
        $session_id = getSessionId();
    }
    
    // Check if product exists and is available
    $product = fetchOne("SELECT * FROM products WHERE id = ? AND status = 'active'", [$product_id]);
    if (!$product) {
        return false;
    }
    
    // Check if item already exists in cart
    $existing_item = fetchOne(
        "SELECT * FROM cart WHERE product_id = ? AND session_id = ? AND (customer_id = ? OR customer_id IS NULL)",
        [$product_id, $session_id, $customer_id]
    );
    
    if ($existing_item) {
        // Update quantity
        $new_quantity = $existing_item['quantity'] + $quantity;
        $sql = "UPDATE cart SET quantity = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        return executeQuery($sql, [$new_quantity, $existing_item['id']]);
    } else {
        // Add new item
        $sql = "INSERT INTO cart (session_id, customer_id, product_id, quantity) VALUES (?, ?, ?, ?)";
        return executeQuery($sql, [$session_id, $customer_id, $product_id, $quantity]);
    }
}

// Update cart item quantity
function updateCartQuantity($cart_id, $quantity) {
    if ($quantity <= 0) {
        return removeFromCart($cart_id);
    }
    
    $sql = "UPDATE cart SET quantity = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
    return executeQuery($sql, [$quantity, $cart_id]);
}

// Remove item from cart
function removeFromCart($cart_id) {
    $sql = "DELETE FROM cart WHERE id = ?";
    return executeQuery($sql, [$cart_id]);
}

// Get cart items
function getCartItems($session_id = null, $customer_id = null) {
    if (!$session_id) {
        $session_id = getSessionId();
    }
    
    $sql = "SELECT c.*, p.name, p.slug, p.price, p.sale_price, p.size, p.color, p.brand,
                   pi.image_path, pi.alt_text
            FROM cart c
            JOIN products p ON c.product_id = p.id
            LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
            WHERE c.session_id = ? AND (c.customer_id = ? OR c.customer_id IS NULL)
            AND p.status = 'active'
            ORDER BY c.added_at DESC";
    
    return fetchAll($sql, [$session_id, $customer_id]);
}

// Calculate cart total
function calculateCartTotal($session_id = null, $customer_id = null) {
    $items = getCartItems($session_id, $customer_id);
    $subtotal = 0;
    
    foreach ($items as $item) {
        $price = $item['sale_price'] ?: $item['price'];
        $subtotal += $price * $item['quantity'];
    }
    
    $shipping_cost = $subtotal >= FREE_SHIPPING_THRESHOLD ? 0 : SHIPPING_COST;
    $total = $subtotal + $shipping_cost;
    
    return [
        'subtotal' => $subtotal,
        'shipping_cost' => $shipping_cost,
        'total' => $total,
        'item_count' => count($items)
    ];
}

// Get cart count
function getCartCount($session_id = null, $customer_id = null) {
    if (!$session_id) {
        $session_id = getSessionId();
    }
    
    $sql = "SELECT SUM(quantity) as count FROM cart 
            WHERE session_id = ? AND (customer_id = ? OR customer_id IS NULL)";
    $result = fetchOne($sql, [$session_id, $customer_id]);
    
    return $result['count'] ?: 0;
}

// Clear cart
function clearCart($session_id = null, $customer_id = null) {
    try {
        if (!$session_id) {
            $session_id = getSessionId();
        }
        
        $sql = "DELETE FROM cart WHERE session_id = ? AND (customer_id = ? OR customer_id IS NULL)";
        return executeQuery($sql, [$session_id, $customer_id]);
    } catch (Exception $e) {
        error_log("Error clearing cart: " . $e->getMessage());
        return false;
    }
}

// Validate cart before checkout
function validateCart($session_id = null, $customer_id = null) {
    $items = getCartItems($session_id, $customer_id);
    $errors = [];
    
    if (empty($items)) {
        $errors[] = "Your cart is empty";
        return $errors;
    }
    
    foreach ($items as $item) {
        // Check if product is still available
        $product = fetchOne("SELECT * FROM products WHERE id = ? AND status = 'active'", [$item['product_id']]);
        if (!$product) {
            $errors[] = "Product '{$item['name']}' is no longer available";
            continue;
        }
        
        // Check stock quantity
        if ($product['stock_quantity'] < $item['quantity']) {
            $errors[] = "Insufficient stock for '{$item['name']}'";
        }
    }
    
    return $errors;
}
?>