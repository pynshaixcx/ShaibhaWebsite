<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// Update admin password
$sql = "UPDATE admin_users SET 
        password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
        login_attempts = 0, 
        locked_until = NULL 
        WHERE username = 'admin'";

$result = executeQuery($sql);

if ($result) {
    echo "Admin password reset successfully. You can now login with username 'admin' and password 'admin123'.";
} else {
    echo "Failed to reset admin password.";
}
?>