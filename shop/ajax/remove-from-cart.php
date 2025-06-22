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

if (!isset($input['cart_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing cart ID']);
    exit;
}

$cart_id = intval($input['cart_id']);

// Verify CSRF token if provided
if (isset($input['csrf_token']) && !verifyCSRFToken($input['csrf_token'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

$session_id = getSessionId();
$customer_id = getCurrentCustomerId();

// Verify cart item belongs to current user/session
$cart_item = fetchOne(
    "SELECT * FROM cart WHERE id = ? AND session_id = ? AND (customer_id = ? OR customer_id IS NULL)",
    [$cart_id, $session_id, $customer_id]
);

if (!$cart_item) {
    echo json_encode(['success' => false, 'message' => 'Cart item not found']);
    exit;
}

// Remove from cart
$result = removeFromCart($cart_id);

if ($result) {
    $cart_count = getCartCount($session_id, $customer_id);
    $cart_totals = calculateCartTotal($session_id, $customer_id);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Item removed from cart',
        'cart_count' => $cart_count,
        'cart_totals' => $cart_totals
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to remove item from cart']);
}
?>