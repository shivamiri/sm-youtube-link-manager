<?php
// Handle Logout Action
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

// Security: Check if admin is logged in (skip check on login.php)
$current_page = basename($_SERVER['PHP_SELF']);
if ($current_page !== 'login.php') {
    if (!function_exists('is_admin_logged_in') || !is_admin_logged_in()) {
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Admin Dashboard - SM YouTube Link Manager</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: '#0f172a',
                        darker: '#020617',
                        card: '#1e293b',
                        primary: '#3b82f6'
                    }
                }
            }
        }
    </script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Globally Disable Text Selection */
        body {
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>
</head>
<body class="bg-darker text-gray-200 min-h-screen font-sans antialiased flex flex-col">

    <?php if ($current_page !== 'login.php'): ?>
    <nav class="bg-card border-b border-gray-800 sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/20 text-primary flex items-center justify-center">
                        <i class="fa-solid fa-shield-halved text-xl"></i>
                    </div>
                    <span class="font-bold text-white text-lg tracking-wide hidden sm:block">Admin Panel</span>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="index.php" class="<?= $current_page == 'index.php' ? 'text-primary' : 'text-gray-400 hover:text-white' ?> transition duration-200 text-sm flex items-center gap-1 font-medium">
                        <i class="fa-solid fa-video"></i> <span class="hidden sm:inline">Videos</span>
                    </a>
                    <a href="setting.php" class="<?= $current_page == 'setting.php' ? 'text-primary' : 'text-gray-400 hover:text-white' ?> transition duration-200 text-sm flex items-center gap-1 font-medium">
                        <i class="fa-solid fa-gear"></i> <span class="hidden sm:inline">Settings</span>
                    </a>
                    
                    <div class="h-5 w-px bg-gray-700 mx-1"></div>
                    
                    <a href="../index.php" target="_blank" class="text-gray-400 hover:text-white transition duration-200 text-sm flex items-center gap-1">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> <span class="hidden lg:inline">View Site</span>
                    </a>
                    <a href="?logout=true" onclick="return confirm('Are you sure you want to logout?');" class="bg-red-500/10 hover:bg-red-500/20 text-red-500 px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-sm font-medium transition duration-200 border border-red-500/20 flex items-center gap-2 ml-1">
                        <i class="fa-solid fa-right-from-bracket"></i> <span class="hidden sm:inline">Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <main class="flex-grow w-full max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
