<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

$email = 'admin@shaibha.com';
$newPassword = 'ShaiBha@2025';

// Hash the new password
$hashedPassword = hashPassword($newPassword);

// Update the database
$sql = "UPDATE admin_users SET password = ? WHERE email = ?";
$stmt = executeQuery($sql, [$hashedPassword, $email]);

if ($stmt) {
    echo "Password updated successfully for $email.\n";
} else {
    echo "Failed to update password.\n";
}
?>
