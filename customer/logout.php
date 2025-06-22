<?php
require_once '../includes/functions.php';

// Log activity if user is logged in
if (isLoggedIn()) {
    $customer_id = getCurrentCustomerId();
    logActivity('customer', $customer_id, 'logout', 'Customer logged out');
}

// Destroy session
destroySession();

// Redirect to homepage
redirect('../index.php');
?>