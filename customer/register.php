<?php
$page_title = "Create Account";
$page_description = "Join ShaiBha to access exclusive pre-loved fashion pieces and track your orders.";
require_once '../includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('profile.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = sanitizeInput($_POST['first_name'] ?? '');
    $last_name = sanitizeInput($_POST['last_name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $agree_terms = isset($_POST['agree_terms']);
    
    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $error = 'Please fill in all required fields';
    } elseif (!validateEmail($email)) {
        $error = 'Please enter a valid email address';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (!$agree_terms) {
        $error = 'Please agree to the terms and conditions';
    } else {
        // Check if email already exists
        $existing_customer = fetchOne("SELECT id FROM customers WHERE email = ?", [$email]);
        
        if ($existing_customer) {
            $error = 'An account with this email already exists';
        } else {
            // Create new customer
            $hashed_password = hashPassword($password);
            $sql = "INSERT INTO customers (first_name, last_name, email, phone, password, status) VALUES (?, ?, ?, ?, ?, 'active')";
            $result = executeQuery($sql, [$first_name, $last_name, $email, $phone, $hashed_password]);
            
            if ($result) {
                $customer_id = getLastInsertId();
                
                // Log activity
                logActivity('customer', $customer_id, 'register', 'Customer registered');
                
                // Auto login
                $_SESSION['customer_id'] = $customer_id;
                $_SESSION['customer_name'] = $first_name . ' ' . $last_name;
                
                // Merge guest cart if exists
                $session_id = getSessionId();
                mergeGuestCartToCustomer($session_id, $customer_id);
                
                // Regenerate session for security
                session_regenerate_id(true);
                
                // Set success message in session
                $_SESSION['success'] = 'Account created successfully! Welcome to ShaiBha.';
                
                // Redirect immediately
                header("Location: profile.php");
                exit();
            } else {
                $error = 'Sorry, there was an error creating your account. Please try again.';
            }
        }
    }
}

include_once '../includes/header.php';
?>

<main class="main-content">
    <section class="register-section">
        <div class="container">
            <div class="register-container">
                <div class="register-card">
                    <div class="register-header">
                        <h1>Create Your Account</h1>
                        <p>Join ShaiBha and discover unique pre-loved fashion</p>
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
                    
                    <form method="POST" class="register-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">First Name *</label>
                                <input type="text" id="first_name" name="first_name" required 
                                       value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>"
                                       placeholder="Enter your first name">
                            </div>
                            
                            <div class="form-group">
                                <label for="last_name">Last Name *</label>
                                <input type="text" id="last_name" name="last_name" required 
                                       value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>"
                                       placeholder="Enter your last name">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required 
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                   placeholder="Enter your email address">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                                   placeholder="+91 9876543210">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="password">Password *</label>
                                <input type="password" id="password" name="password" required 
                                       placeholder="Create a password (min. 6 characters)">
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password *</label>
                                <input type="password" id="confirm_password" name="confirm_password" required 
                                       placeholder="Confirm your password">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="agree_terms" required>
                                <span class="checkmark"></span>
                                I agree to the <a href="../pages/terms-conditions.php" target="_blank">Terms & Conditions</a> and <a href="../pages/privacy-policy.php" target="_blank">Privacy Policy</a>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary register-btn">
                            Create Account
                        </button>
                    </form>
                    
                    <div class="register-footer">
                        <p>Already have an account? <a href="login.php">Sign in here</a></p>
                    </div>
                </div>
                
                <div class="register-benefits">
                    <h3>Why join ShaiBha?</h3>
                    <ul>
                        <li>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            Access to exclusive pre-loved pieces
                        </li>
                        <li>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            Track your orders and delivery
                        </li>
                        <li>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            Save your favorite items
                        </li>
                        <li>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            Faster checkout process
                        </li>
                        <li>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            Early access to new arrivals
                        </li>
                        <li>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            Personalized styling recommendations
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Register Page Styles */
.register-section {
    padding: var(--spacing-3xl) 0;
    min-height: 80vh;
    display: flex;
    align-items: center;
}

.register-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-3xl);
    max-width: 1200px;
    margin: 0 auto;
    align-items: start;
}

.register-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-2xl);
    box-shadow: var(--glass-shadow);
}

.register-header {
    text-align: center;
    margin-bottom: var(--spacing-xl);
}

.register-header h1 {
    font-size: 2rem;
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.register-header p {
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

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-lg);
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

.checkbox-label {
    display: flex;
    align-items: flex-start;
    cursor: pointer;
    font-size: 0.9rem;
    color: var(--color-gray-700);
    line-height: 1.5;
}

.checkbox-label input {
    margin-right: var(--spacing-sm);
    margin-top: 2px;
}

.checkbox-label a {
    color: var(--color-black);
    font-weight: 600;
}

.register-btn {
    width: 100%;
    justify-content: center;
    margin-bottom: var(--spacing-lg);
}

.register-footer {
    text-align: center;
    padding-top: var(--spacing-lg);
    border-top: 1px solid var(--color-gray-200);
}

.register-footer a {
    color: var(--color-black);
    font-weight: 600;
    text-decoration: none;
}

.register-footer a:hover {
    text-decoration: underline;
}

.register-benefits {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-2xl);
    position: sticky;
    top: 100px;
}

.register-benefits h3 {
    margin-bottom: var(--spacing-lg);
    color: var(--color-black);
}

.register-benefits ul {
    list-style: none;
}

.register-benefits li {
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-md);
    color: var(--color-gray-700);
    line-height: 1.5;
}

.register-benefits svg {
    color: #10b981;
    flex-shrink: 0;
    margin-top: 2px;
}

/* Responsive */
@media (max-width: 992px) {
    .register-container {
        grid-template-columns: 1fr;
        gap: var(--spacing-xl);
    }
    
    .register-benefits {
        position: static;
        order: -1;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
}

@media (max-width: 768px) {
    .register-section {
        padding: var(--spacing-xl) 0;
    }
    
    .register-card,
    .register-benefits {
        padding: var(--spacing-xl);
    }
}
</style>

<?php include_once '../includes/footer.php'; ?>