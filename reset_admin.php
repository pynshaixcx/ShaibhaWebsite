<?php
// Include database configuration
require_once __DIR__ . '/config/database.php';

// Function to hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

// New admin credentials
$admin_username = 'admin';
$admin_password = 'ShaiBha@2025';
$hashed_password = hashPassword($admin_password);

try {
    // Check if admin user exists
    $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE username = ?");
    $stmt->execute([$admin_username]);
    $admin = $stmt->fetch();
    
    if ($admin) {
        // Update existing admin
        $stmt = $pdo->prepare("UPDATE admin_users SET password = ?, login_attempts = 0, status = 'active', locked_until = NULL WHERE username = ?");
        $result = $stmt->execute([$hashed_password, $admin_username]);
        $message = "Admin password updated successfully!";
    } else {
        // Create new admin user
        $full_name = 'Administrator';
        $email = 'admin@shaibha.com';
        $role = 'admin';
        
        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password, email, full_name, role, status) VALUES (?, ?, ?, ?, ?, 'active')");
        $result = $stmt->execute([$admin_username, $hashed_password, $email, $full_name, $role]);
        $message = "Admin user created successfully!";
    }
    
    if ($result) {
        echo "<h1>Success!</h1>";
        echo "<p>$message</p>";
        echo "<p>Username: <strong>$admin_username</strong></p>";
        echo "<p>Password: <strong>$admin_password</strong></p>";
        echo "<p>Please <a href='admin/login.php'>login here</a> and change your password immediately.</p>";
        
        // Log the action
        error_log("Admin password was reset at " . date('Y-m-d H:i:s'));
    } else {
        echo "<h1>Error</h1>";
        echo "<p>Failed to update admin user. Please check the database connection and try again.</p>";
    }
    
} catch (PDOException $e) {
    echo "<h1>Database Error</h1>";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
?>

<style>
body { 
    font-family: Arial, sans-serif; 
    line-height: 1.6; 
    max-width: 800px; 
    margin: 0 auto; 
    padding: 20px;
}
h1 { color: #4CAF50; }
pre { 
    background: #f4f4f4; 
    padding: 10px; 
    border: 1px solid #ddd; 
    overflow-x: auto;
}
</style>
