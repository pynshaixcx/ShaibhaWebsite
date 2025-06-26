<?php
$page_title = "Admin Logout";
require_once '../includes/functions.php';

// Check if admin is logged in
if (isAdminLoggedIn()) {
    $admin_id = getCurrentAdminId();
    
    // Log activity
    logActivity('admin', $admin_id, 'logout', 'Admin logged out');

    // Clear admin session
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_name']);
    unset($_SESSION['admin_role']);
    
    // Regenerate session for security
    regenerateSession();
}

// Redirect to login page
redirect('login.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <link crossorigin="" href="https://fonts.gstatic.com/" rel="preconnect"/>
    <link as="style" href="https://fonts.googleapis.com/css2?display=swap&family=Inter%3Awght%40400%3B500%3B600%3B700%3B900&family=Noto+Sans%3Awght%40400%3B500%3B700%3B900" onload="this.rel='stylesheet'" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>
    <title>ShaiBha Admin - Logging Out</title>
    <link href="data:image/x-icon;base64," rel="icon" type="image/x-icon"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style type="text/tailwindcss">
        :root {
            --background-primary: rgba(20, 20, 20, 0.7);
            --background-secondary: rgba(48, 48, 48, 0.7);
            --border-color: rgba(48, 48, 48, 0.9);
            --text-primary: #ffffff;
            --text-secondary: #ababab;
            --blur-intensity: 10px;
        }
        .frosted-glass {
            backdrop-filter: blur(var(--blur-intensity));
            -webkit-backdrop-filter: blur(var(--blur-intensity));
        }
    </style>
</head>
<body class="bg-gradient-to-br from-black via-slate-900 to-black">
    <div class="relative flex size-full min-h-screen flex-col bg-cover bg-center bg-fixed" style='font-family: Inter, "Noto Sans", sans-serif;'>
        <div class="flex h-screen w-full items-center justify-center">
            <div class="frosted-glass flex flex-col items-center rounded-xl border border-[var(--border-color)] bg-[var(--background-primary)] p-8 text-center">
                <span class="material-icons-outlined mb-4 text-5xl text-[var(--text-primary)]">logout</span>
                <h1 class="mb-2 text-2xl font-bold text-[var(--text-primary)]">Logging Out...</h1>
                <p class="mb-6 text-[var(--text-secondary)]">You are being redirected to the login page.</p>
                <div class="flex justify-center">
                    <a class="rounded-lg bg-white px-6 py-2 font-medium text-black transition-colors hover:bg-opacity-90" href="login.php">
                        Return to Login
                    </a>
                </div>
        </div>
        </div>
    </div>
</body>
</html>