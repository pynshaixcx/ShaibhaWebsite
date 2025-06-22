<?php
require_once '../../includes/functions.php';
require_once '../../includes/cart-functions.php';

header('Content-Type: application/json');

$session_id = getSessionId();
$customer_id = getCurrentCustomerId();

$cart_count = getCartCount($session_id, $customer_id);

echo json_encode(['count' => $cart_count]);
?>