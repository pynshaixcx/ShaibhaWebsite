<?php
$page_title = "Customer Login";
$page_description = "Sign in to your ShaiBha account to access your orders and profile.";
require_once '../includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('profile.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } elseif (!validateEmail($email)) {
        $error = 'Please enter a valid email address';
    } else {
        // Check customer credentials
        $customer = fetchOne("SELECT * FROM customers WHERE email = ? AND status = 'active'", [$email]);
        
        if ($customer && verifyPassword($password, $customer['password'])) {
            // Login successful
            $_SESSION['customer_id'] = $customer['id'];
            $_SESSION['customer_name'] = $customer['first_name'] . ' ' . $customer['last_name'];
            
            // Update last login
            executeQuery("UPDATE customers SET last_login = CURRENT_TIMESTAMP WHERE id = ?", [$customer['id']]);
            
            // Log activity
            logActivity('customer', $customer['id'], 'login', 'Customer logged in');
            
            // Merge guest cart if exists
            $session_id = getSessionId();
            mergeGuestCartToCustomer($session_id, $customer['id']);
            
            // Regenerate session for security
            regenerateSession();
            
            // Redirect to intended page or profile
            $redirect_url = $_GET['redirect'] ?? 'profile.php';
            redirect($redirect_url);
        } else {
            $error = 'Invalid email or password';
        }
    }
}

include_once '../includes/header.php';
?>

<main class="main-content">
    <section class="login-section">
        <div class="container">
            <div class="login-container">
                <div class="login-card">
                    <div class="login-header">
                        <h1>Welcome Back</h1>
                        <p>Sign in to your ShaiBha account</p>
                    </div>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-error">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="login-form">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required 
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                   placeholder="Enter your email">
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required 
                                   placeholder="Enter your password">
                        </div>
                        
                        <div class="form-options">
                            <label class="checkbox-label">
                                <input type="checkbox" name="remember">
                                <span class="checkmark"></span>
                                Remember me
                            </label>
                            <a href="forgot-password.php" class="forgot-link">Forgot password?</a>
                        </div>
                        
                        <button type="submit" class="btn btn-primary login-btn">
                            Sign In
                        </button>
                    </form>
                    
                    <div class="login-footer">
                        <p>Don't have an account? <a href="register.php">Create one here</a></p>
                    </div>
                </div>
                
                <div class="login-benefits">
                    <h3>Why create an account?</h3>
                    <ul>
                        <li>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            Track your orders
                        </li>
                        <li>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            Save your addresses
                        </li>
                        <li>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            Faster checkout
                        </li>
                        <li>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            Exclusive offers
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Login Page Styles */
.login-section {
    padding: var(--spacing-3xl) 0;
    min-height: 80vh;
    display: flex;
    align-items: center;
}

.login-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-3xl);
    max-width: 1000px;
    margin: 0 auto;
    align-items: center;
}

.login-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-2xl);
    box-shadow: var(--glass-shadow);
}

.login-header {
    text-align: center;
    margin-bottom: var(--spacing-xl);
}

.login-header h1 {
    font-size: 2rem;
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.login-header p {
    color: var(--color-gray-600);
}

.alert {
    padding: var(--spacing-md);
    border-radius: var(--border-radius-md);
    margin-bottom: var(--spacing-lg);
}

.alert-error {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.form-group {
    margin-bottom: var(--spacing-lg);
}

.form-group label {
    display: block;
    margin-bottom: var(--spacing-xs);
    font-weight: 600;
    color: var(--color-black);
}

.form-group input {
    width: 100%;
    padding: var(--spacing-md);
    border: 1px solid var(--color-gray-300);
    border-radius: var(--border-radius-md);
    font-size: 1rem;
    transition: border-color var(--transition-fast);
    background: var(--color-white);
}

.form-group input:focus {
    outline: none;
    border-color: var(--color-black);
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
}

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-xl);
}

.checkbox-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: 0.9rem;
    color: var(--color-gray-700);
}

.checkbox-label input {
    margin-right: var(--spacing-xs);
}

.forgot-link {
    color: var(--color-black);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
}

.forgot-link:hover {
    text-decoration: underline;
}

.login-btn {
    width: 100%;
    justify-content: center;
    margin-bottom: var(--spacing-lg);
}

.login-footer {
    text-align: center;
    padding-top: var(--spacing-lg);
    border-top: 1px solid var(--color-gray-200);
}

.login-footer a {
    color: var(--color-black);
    font-weight: 600;
    text-decoration: none;
}

.login-footer a:hover {
    text-decoration: underline;
}

.login-benefits {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-2xl);
}

.login-benefits h3 {
    margin-bottom: var(--spacing-lg);
    color: var(--color-black);
}

.login-benefits ul {
    list-style: none;
}

.login-benefits li {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-md);
    color: var(--color-gray-700);
}

.login-benefits svg {
    color: #10b981;
    flex-shrink: 0;
}

/* Responsive */
@media (max-width: 992px) {
    .login-container {
        grid-template-columns: 1fr;
        gap: var(--spacing-xl);
    }
    
    .login-benefits {
        order: -1;
    }
}

@media (max-width: 768px) {
    .login-section {
        padding: var(--spacing-xl) 0;
    }
    
    .login-card,
    .login-benefits {
        padding: var(--spacing-xl);
    }
    
    .form-options {
        flex-direction: column;
        gap: var(--spacing-sm);
        align-items: flex-start;
    }
}
</style>

<?php include_once '../includes/footer.php'; ?>