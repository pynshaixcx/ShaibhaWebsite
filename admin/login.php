<?php
$page_title = "Admin Login";
require_once '../includes/functions.php';

// Redirect if already logged in
if (isAdminLoggedIn()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        // Check admin credentials
        $admin = fetchOne("SELECT * FROM admin_users WHERE username = ? AND status = 'active'", [$username]);
        
        if ($admin && verifyPassword($password, $admin['password'])) {
            // Login successful
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            $_SESSION['admin_role'] = $admin['role'];
            
            // Update last login
            executeQuery("UPDATE admin_users SET last_login = CURRENT_TIMESTAMP WHERE id = ?", [$admin['id']]);
            
            // Log activity
            logActivity('admin', $admin['id'], 'login', 'Admin logged in');
            
            // Regenerate session for security
            regenerateSession();
            
            // Redirect to dashboard
            redirect('index.php');
        } else {
            // Failed login attempt
            if ($admin) {
                // Increment login attempts
                $attempts = $admin['login_attempts'] + 1;
                $locked = $attempts >= 5 ? 'DATE_ADD(NOW(), INTERVAL 30 MINUTE)' : 'NULL';
                
                executeQuery(
                    "UPDATE admin_users SET login_attempts = ?, locked_until = {$locked} WHERE id = ?", 
                    [$attempts, $admin['id']]
                );
                
                if ($attempts >= 5) {
                    $error = 'Too many failed login attempts. Account locked for 30 minutes.';
                } else {
                    $error = 'Invalid username or password';
                }
            } else {
                $error = 'Invalid username or password';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - ShaiBha Admin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="../css/admin.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="../images/favicon.svg">
</head>
<body class="login-page">
    <div class="admin-login">
        <div class="login-container">
            <div class="login-header">
                <h1 class="login-logo">ShaiBha</h1>
                <p class="login-subtitle">Admin Panel</p>
            </div>
            
            <div class="login-card">
                <h2>Sign In</h2>
                <p>Enter your credentials to access the admin panel</p>
                
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="login-form">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-with-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <input type="text" id="username" name="username" required 
                                   value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                                   placeholder="Enter your username">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-with-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <input type="password" id="password" name="password" required 
                                   placeholder="Enter your password">
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary login-btn">
                        Sign In
                    </button>
                </form>
                
                <div class="login-footer">
                    <p>Return to <a href="../index.php">Website</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>