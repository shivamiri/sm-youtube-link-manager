<?php
session_start();

// Database connection settings
$host = '127.0.0.1';
$user = 'root';
$pass = 'root';
$dbname = 'sm_yt_links';

$message = '';
$installed = false;

// Attempt connection to MySQL server
$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    $message = "<div class='bg-red-500/20 text-red-500 p-4 rounded-lg mb-4 text-center'>Database Connection Failed: " . htmlspecialchars($conn->connect_error) . "<br>Please check your credentials.</div>";
} else {
    // Check if database exists
    $db_selected = $conn->select_db($dbname);
    
    if ($db_selected) {
        // Check if admin table exists to prevent re-installation
        $check_table = $conn->query("SHOW TABLES LIKE 'admin'");
        if ($check_table && $check_table->num_rows > 0) {
            $installed = true;
        }
    }

    if (isset($_POST['install']) && !$installed) {
        // Create Database if not exists
        $conn->query("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $conn->select_db($dbname);

        // 1. Create Admin Table
        $sql_admin = "CREATE TABLE IF NOT EXISTS admin (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $conn->query($sql_admin);

        // 2. Create Channel Settings Table
        $sql_settings = "CREATE TABLE IF NOT EXISTS channel_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            channel_name VARCHAR(100) NOT NULL,
            channel_description TEXT,
            channel_url VARCHAR(255),
            logo_type ENUM('upload', 'url') DEFAULT 'url',
            logo_upload VARCHAR(255),
            logo_url VARCHAR(255),
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $conn->query($sql_settings);

        // 3. Create Videos Table
        $sql_videos = "CREATE TABLE IF NOT EXISTS videos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            youtube_url VARCHAR(255) NOT NULL,
            youtube_video_id VARCHAR(50) NOT NULL,
            title VARCHAR(255) NOT NULL,
            thumbnail_type ENUM('upload', 'url', 'auto') DEFAULT 'auto',
            thumbnail_upload VARCHAR(255),
            thumbnail_url VARCHAR(255),
            display_order INT DEFAULT 0,
            status ENUM('active', 'hidden') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $conn->query($sql_videos);

        // Insert Default Admin
        $check_admin = $conn->query("SELECT id FROM admin WHERE username='admin'");
        if ($check_admin->num_rows == 0) {
            $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
            $admin_user = 'admin';
            $stmt->bind_param("ss", $admin_user, $hashed_password);
            $stmt->execute();
            $stmt->close();
        }

        // Insert Default Channel Settings
        $check_settings = $conn->query("SELECT id FROM channel_settings");
        if ($check_settings->num_rows == 0) {
            $default_name = 'SM YouTube Link Manager';
            $default_desc = 'Welcome to my official YouTube Links page. Check out my latest videos below!';
            $default_url = 'https://youtube.com/';
            $default_logo_type = 'url';
            $default_logo_url = 'https://ui-avatars.com/api/?name=SM&background=2563eb&color=fff&size=256';
            
            $stmt = $conn->prepare("INSERT INTO channel_settings (channel_name, channel_description, channel_url, logo_type, logo_url) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $default_name, $default_desc, $default_url, $default_logo_type, $default_logo_url);
            $stmt->execute();
            $stmt->close();
        }

        // Create Upload Directory if it doesn't exist
        if (!file_exists(__DIR__ . '/upload')) {
            mkdir(__DIR__ . '/upload', 0755, true);
        }

        // Create Common Directory if it doesn't exist
        if (!file_exists(__DIR__ . '/common')) {
            mkdir(__DIR__ . '/common', 0755, true);
        }
        
        // Create Admin Directory if it doesn't exist
        if (!file_exists(__DIR__ . '/admin')) {
            mkdir(__DIR__ . '/admin', 0755, true);
        }

        // Redirect to admin login
        header("Location: admin/login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install - SM YouTube Link Manager</title>
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
        /* Disable text selection */
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
<body class="bg-darker text-gray-200 min-h-screen flex items-center justify-center p-4">

    <div class="bg-card w-full max-w-md p-8 rounded-2xl shadow-2xl border border-gray-800">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/20 text-primary mb-4">
                <i class="fa-brands fa-youtube text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">System Installation</h1>
            <p class="text-gray-400 mt-2">SM YouTube Link Manager</p>
        </div>

        <?= $message ?>

        <?php if ($installed): ?>
            <div class="bg-green-500/20 text-green-400 p-4 rounded-lg mb-6 text-center border border-green-500/30">
                <i class="fa-solid fa-circle-check mb-2 text-2xl"></i><br>
                System is already installed!
            </div>
            <a href="admin/login.php" class="block w-full text-center bg-gray-700 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                Go to Admin Panel
            </a>
            <a href="index.php" class="block w-full text-center mt-3 text-gray-400 hover:text-white transition duration-200">
                View Homepage
            </a>
        <?php else: ?>
            <form method="POST" action="">
                <div class="bg-dark p-4 rounded-lg mb-6 border border-gray-800">
                    <h3 class="text-white font-semibold mb-3 border-b border-gray-700 pb-2">Installation Details:</h3>
                    <ul class="text-sm text-gray-400 space-y-2">
                        <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Create Database (sm_yt_links)</li>
                        <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Create System Tables</li>
                        <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Insert Default Settings</li>
                        <li><i class="fa-solid fa-check text-green-500 mr-2"></i> Setup Admin Account</li>
                    </ul>
                </div>
                
                <div class="bg-blue-500/10 text-blue-400 p-4 rounded-lg mb-6 text-sm border border-blue-500/20">
                    <strong>Default Admin Login:</strong><br>
                    Username: <span class="text-white">admin</span><br>
                    Password: <span class="text-white">admin123</span>
                </div>

                <button type="submit" name="install" class="w-full bg-primary hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-lg shadow-blue-500/30 flex justify-center items-center gap-2">
                    <i class="fa-solid fa-rocket"></i> Install System Now
                </button>
            </form>
        <?php endif; ?>
    </div>

    <script>
        // Disable Right Click
        document.addEventListener('contextmenu', event => event.preventDefault());

        // Disable keyboard shortcuts (Ctrl+U, Ctrl+S, Ctrl+P, F12, Ctrl+Shift+I, Zoom)
        document.addEventListener('keydown', function(e) {
            // Disable F12 and Inspect Element shortcuts
            if(e.keyCode === 123 || 
              (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74 || e.keyCode === 67)) || 
              (e.ctrlKey && e.keyCode === 85)) {
                e.preventDefault();
                return false;
            }
            // Disable Ctrl+ / Ctrl- / Ctrl0 (Zoom)
            if (e.ctrlKey && (e.key === '+' || e.key === '-' || e.key === '0' || e.key === '=')) {
                e.preventDefault();
            }
        });

        // Disable Ctrl + Mouse Wheel Zoom
        document.addEventListener('wheel', function(e) {
            if (e.ctrlKey) {
                e.preventDefault();
            }
        }, { passive: false });
    </script>
</body>
</html>
