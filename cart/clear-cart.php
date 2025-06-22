<?php
require_once '../includes/functions.php';
require_once '../includes/cart-functions.php';

$session_id = getSessionId();
$customer_id = getCurrentCustomerId();

// Clear cart
$result = clearCart($session_id, $customer_id);

if ($result) {
    setFlashMessage('success', 'Cart cleared successfully');
} else {
    setFlashMessage('error', 'Failed to clear cart');
}

// Redirect back to cart
redirect('index.php');
?>