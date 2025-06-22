<?php
require_once '../../includes/functions.php';
require_once '../../includes/cart-functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['product_id']) || !isset($input['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

$product_id = intval($input['product_id']);
$quantity = intval($input['quantity']);

if ($quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid quantity']);
    exit;
}

// Verify CSRF token if provided
if (isset($input['csrf_token']) && !verifyCSRFToken($input['csrf_token'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

$session_id = getSessionId();
$customer_id = getCurrentCustomerId();

// Check if product exists and is available
$product = fetchOne("SELECT * FROM products WHERE id = ? AND status = 'active'", [$product_id]);
if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

// Check stock availability
if ($product['stock_quantity'] < $quantity) {
    echo json_encode(['success' => false, 'message' => 'Insufficient stock']);
    exit;
}

// Add to cart
$result = addToCart($product_id, $quantity, $session_id, $customer_id);

if ($result) {
    $cart_count = getCartCount($session_id, $customer_id);
    echo json_encode([
        'success' => true, 
        'message' => 'Product added to cart',
        'cart_count' => $cart_count
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add product to cart']);
}
?>