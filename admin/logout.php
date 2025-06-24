<?php
require_once '../includes/functions.php';

// Log activity if admin is logged in
if (isAdminLoggedIn()) {
    $admin_id = getCurrentAdminId();
    logActivity('admin', $admin_id, 'logout', 'Admin logged out');
}

// Destroy session
destroySession();

// Redirect to login page
header('Location: login.php');
exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out - ShaiBha Admin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="../css/admin.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
</head>
<body class="logout-page">
    <div class="logout-container">
        <div class="logout-icon">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
        </div>
        <h1>You've Been Logged Out</h1>
        <p>Thank you for using ShaiBha Admin Panel. You have been successfully logged out.</p>
        <div class="logout-actions">
            <a href="login.php" class="btn btn-primary">Log In Again</a>
            <a href="../index.php" class="btn btn-outline">Return to Website</a>
        </div>
    </div>
</body>
</html>