<?php
$page_title = "My Profile";
$page_description = "Manage your ShaiBha account profile and preferences.";
require_once '../includes/functions.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$customer_id = getCurrentCustomerId();
$customer = fetchOne("SELECT * FROM customers WHERE id = ?", [$customer_id]);

if (!$customer) {
    destroySession();
    redirect('login.php');
}

$error = '';
$success = '';

// Check for success message in session
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']); // Clear the message after displaying it
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $first_name = sanitizeInput($_POST['first_name'] ?? '');
        $last_name = sanitizeInput($_POST['last_name'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $phone = sanitizeInput($_POST['phone'] ?? '');
        $date_of_birth = $_POST['date_of_birth'] ?? '';
        $gender = $_POST['gender'] ?? '';
        
        // Validation
        if (empty($first_name) || empty($last_name) || empty($email)) {
            $error = 'Please fill in all required fields';
        } elseif (!validateEmail($email)) {
            $error = 'Please enter a valid email address';
        } else {
            // Check if email is already taken by another user
            $existing_customer = fetchOne("SELECT id FROM customers WHERE email = ? AND id != ?", [$email, $customer_id]);
            
            if ($existing_customer) {
                $error = 'This email address is already in use';
            } else {
                // Update customer profile
                $sql = "UPDATE customers SET first_name = ?, last_name = ?, email = ?, phone = ?, date_of_birth = ?, gender = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
                $params = [$first_name, $last_name, $email, $phone, $date_of_birth ?: null, $gender ?: null, $customer_id];
                
                $result = executeQuery($sql, $params);
                
                if ($result) {
                    // Update session name
                    $_SESSION['customer_name'] = $first_name . ' ' . $last_name;
                    
                    // Log activity
                    logActivity('customer', $customer_id, 'profile_updated', 'Customer profile updated');
                    
                    $success = 'Profile updated successfully!';
                    
                    // Refresh customer data
                    $customer = fetchOne("SELECT * FROM customers WHERE id = ?", [$customer_id]);
                } else {
                    $error = 'Sorry, there was an error updating your profile. Please try again.';
                }
            }
        }
    }
}

include_once '../includes/header.php';
?>

<main class="main-content">
    <!-- Profile Header -->
    <section class="profile-header">
        <div class="container">
            <div class="profile-header-content">
                <div class="profile-header-row">
                    <div>
                        <h1 class="page-title">My Profile</h1>
                        <p class="page-subtitle">Manage your account information and preferences</p>
                    </div>
                    <a href="logout.php" class="btn btn-outline">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Profile Content -->
    <section class="profile-content">
        <div class="container">
            <div class="profile-layout">
                <!-- Profile Sidebar -->
                <aside class="profile-sidebar">
                    <div class="profile-nav">
                        <h3>Account</h3>
                        <ul class="nav-list">
                            <li><a href="profile.php" class="nav-link active">Profile Information</a></li>
                            <li><a href="orders.php" class="nav-link">Order History</a></li>
                            <li><a href="logout.php" class="nav-link">Logout</a></li>
                        </ul>
                    </div>
                </aside>

                <!-- Profile Form -->
                <div class="profile-main">
                    <div class="profile-card">
                        <div class="card-header">
                            <h2>Profile Information</h2>
                            <p>Update your personal information and contact details</p>
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
                        
                        <form method="POST" class="profile-form">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="first_name">First Name *</label>
                                    <input type="text" id="first_name" name="first_name" required 
                                           value="<?php echo htmlspecialchars($customer['first_name']); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="last_name">Last Name *</label>
                                    <input type="text" id="last_name" name="last_name" required 
                                           value="<?php echo htmlspecialchars($customer['last_name']); ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" required 
                                       value="<?php echo htmlspecialchars($customer['email']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($customer['phone']); ?>"
                                       placeholder="+91 9876543210">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="date_of_birth">Date of Birth</label>
                                    <input type="date" id="date_of_birth" name="date_of_birth" 
                                           value="<?php echo $customer['date_of_birth']; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" <?php echo $customer['gender'] === 'male' ? 'selected' : ''; ?>>Male</option>
                                        <option value="female" <?php echo $customer['gender'] === 'female' ? 'selected' : ''; ?>>Female</option>
                                        <option value="other" <?php echo $customer['gender'] === 'other' ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                                <a href="orders.php" class="btn btn-secondary">View Orders</a>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Account Stats -->
                    <div class="account-stats">
                        <h3>Account Summary</h3>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                    </svg>
                                </div>
                                <div class="stat-content">
                                    <h4>Total Orders</h4>
                                    <p><?php echo fetchOne("SELECT COUNT(*) as count FROM orders WHERE customer_id = ?", [$customer_id])['count']; ?></p>
                                </div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12,6 12,12 16,14"></polyline>
                                    </svg>
                                </div>
                                <div class="stat-content">
                                    <h4>Member Since</h4>
                                    <p><?php echo date('M Y', strtotime($customer['created_at'])); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Profile Page Styles */
.profile-header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.profile-header-row .btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.2rem;
    border: 1px solid var(--color-primary);
    color: var(--color-primary);
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.profile-header-row .btn-outline:hover {
    background-color: var(--color-primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.profile-header-row .btn-outline i {
    font-size: 0.9em;
}

@media (max-width: 576px) {
    .profile-header-row {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .profile-header-row .btn-outline {
        width: 100%;
        justify-content: center;
    }
}

.profile-header {
    padding: var(--spacing-2xl) 0;
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-white) 100%);
    text-align: center;
}

.profile-content {
    padding: var(--spacing-3xl) 0;
}

.profile-layout {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: var(--spacing-3xl);
}

.profile-sidebar {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
    height: fit-content;
    position: sticky;
    top: 100px;
}

.profile-nav h3 {
    margin-bottom: var(--spacing-md);
    color: var(--color-black);
}

.profile-nav .nav-list {
    list-style: none;
}

.profile-nav .nav-list li {
    margin-bottom: var(--spacing-xs);
}

.profile-nav .nav-link {
    display: block;
    padding: var(--spacing-sm) var(--spacing-md);
    color: var(--color-gray-600);
    text-decoration: none;
    border-radius: var(--border-radius-md);
    transition: all var(--transition-fast);
}

.profile-nav .nav-link:hover,
.profile-nav .nav-link.active {
    background-color: var(--color-black);
    color: var(--color-white);
}

.profile-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-2xl);
    margin-bottom: var(--spacing-xl);
}

.card-header {
    margin-bottom: var(--spacing-xl);
    text-align: center;
}

.card-header h2 {
    margin-bottom: var(--spacing-sm);
    color: var(--color-black);
}

.card-header p {
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

.form-group input,
.form-group select {
    width: 100%;
    padding: var(--spacing-md);
    border: 1px solid var(--color-gray-300);
    border-radius: var(--border-radius-md);
    font-size: 1rem;
    transition: border-color var(--transition-fast);
    background: var(--color-white);
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--color-black);
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
}

.form-actions {
    display: flex;
    gap: var(--spacing-md);
    justify-content: center;
    margin-top: var(--spacing-xl);
}

.account-stats {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
}

.account-stats h3 {
    margin-bottom: var(--spacing-lg);
    color: var(--color-black);
    text-align: center;
}

.stats-grid {
    display: grid;
    gap: var(--spacing-lg);
}

.stat-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding: var(--spacing-md);
    background: var(--color-white);
    border-radius: var(--border-radius-md);
    border: 1px solid var(--color-gray-200);
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: var(--color-black);
    color: var(--color-white);
    border-radius: var(--border-radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-content h4 {
    margin-bottom: var(--spacing-xs);
    color: var(--color-black);
    font-size: 0.9rem;
}

.stat-content p {
    color: var(--color-gray-600);
    font-weight: 600;
}

/* Responsive */
@media (max-width: 992px) {
    .profile-layout {
        grid-template-columns: 1fr;
        gap: var(--spacing-xl);
    }
    
    .profile-sidebar {
        position: static;
        order: 2;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
}

@media (max-width: 768px) {
    .profile-card,
    .account-stats {
        padding: var(--spacing-lg);
    }
    
    .form-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .form-actions .btn {
        width: 100%;
        max-width: 280px;
    }
}
</style>

<?php include_once '../includes/footer.php'; ?>