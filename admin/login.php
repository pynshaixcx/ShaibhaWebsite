<?php
$page_title = "Admin Login";
require_once '../includes/functions.php';

// Redirect if already logged in
if (isAdminLoggedIn()) {
    redirect('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    


    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        // Check admin credentials
        $admin = fetchOne("SELECT * FROM admin_users WHERE username = ? AND status = 'active'", [$username]);
        
        if ($admin && password_verify($password, $admin['password'])) {
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
            // For testing purposes, let's check if the admin exists but password doesn't match
            if ($admin) {
                $error = 'Invalid password. Please try again.';
            } else {
                $error = 'Invalid username or password';
            }
            
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
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <link crossorigin="" href="https://fonts.gstatic.com/" rel="preconnect"/>
  <link as="style" href="https://fonts.googleapis.com/css2?display=swap&family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&family=Plus+Jakarta+Sans%3Awght%40400%3B500%3B600%3B700%3B800&family=Lexend%3Awght%40400%3B500%3B600%3B700" onload="this.rel='stylesheet'" rel="stylesheet"/>
  <title>ShaiBha Login</title>
  <link href="data:image/x-icon;base64," rel="icon" type="image/x-icon"/>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <style type="text/tailwindcss">
        :root {
          --primary-color: #ffffff;
          --secondary-color: #141414;
          --accent-color: #303030;
          --text-primary: #ffffff;
          --text-secondary: #ababab;
          --card-background: rgba(48, 48, 48, 0.6);}
        body {
          font-family: 'Plus Jakarta Sans', 'Noto Sans', sans-serif;
        }
        .frosted-glass {
          background: var(--card-background);
          backdrop-filter: blur(15px);
          -webkit-backdrop-filter: blur(15px);border: 1px solid rgba(255, 255, 255, 0.1);}
      </style>
</head>
  <body class="bg-[var(--secondary-color)] flex items-center justify-center min-h-screen p-4">
  <div class="relative flex size-full min-h-screen flex-col bg-[var(--secondary-color)] dark group/design-root overflow-x-hidden items-center justify-center">
  <div class="frosted-glass rounded-3xl p-8 md:p-12 shadow-2xl w-full max-w-md">
  <div class="text-center mb-8">
  <h1 class="text-[var(--primary-color)] text-4xl md:text-5xl font-bold" style="font-family: 'Lexend', sans-serif;">ShaiBha</h1>
            </div>
                
                <?php if ($error): ?>
  <div class="bg-red-500/30 text-red-300 p-4 rounded-xl mb-6 text-center">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
  <div class="bg-green-500/30 text-green-300 p-4 rounded-xl mb-6 text-center">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
  <form class="space-y-6" method="POST">
  <div>
  <label class="sr-only" for="username">Username</label>
  <input class="form-input w-full rounded-xl border-none bg-[var(--accent-color)] bg-opacity-70 text-[var(--text-primary)] placeholder:text-[var(--text-secondary)] focus:ring-2 focus:ring-[var(--primary-color)] focus:ring-opacity-50 h-14 p-4 text-base transition-all duration-300 ease-in-out" 
         id="username" 
         name="username" 
         placeholder="Username" 
         required 
         type="text"
         value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"/>
  </div>
  <div>
  <label class="sr-only" for="password">Password</label>
  <input class="form-input w-full rounded-xl border-none bg-[var(--accent-color)] bg-opacity-70 text-[var(--text-primary)] placeholder:text-[var(--text-secondary)] focus:ring-2 focus:ring-[var(--primary-color)] focus:ring-opacity-50 h-14 p-4 text-base transition-all duration-300 ease-in-out" 
         id="password" 
         name="password" 
         placeholder="Password" 
         required 
         type="password"/>
  </div>
  <div>
  <button class="w-full rounded-xl bg-[var(--primary-color)] text-[var(--secondary-color)] h-12 md:h-14 px-4 text-base md:text-lg font-semibold tracking-wide hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] focus:ring-offset-2 focus:ring-offset-[var(--secondary-color)] transition-all duration-300 ease-in-out" type="submit">
                Login
              </button>
                        </div>
  <div class="text-center mt-4">
    <p class="text-[var(--text-secondary)] text-sm">Return to <a href="../index.php" class="text-[var(--primary-color)] hover:underline">Website</a></p>
                    </div>
  </form>
                        </div>
                    </div>
                    
</body>
</html>