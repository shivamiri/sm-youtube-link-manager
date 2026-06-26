<?php
// Include Main Configuration
require_once __DIR__ . '/../common/config.php';

// Check if already logged in
if (is_admin_logged_in()) {
    redirect('index.php');
}

$error = '';

// Handle Login Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $admin = $result->fetch_assoc();
                
                // Verify Password
                if (password_verify($password, $admin['password'])) {
                    // Regenerate session ID for security against session fixation
                    session_regenerate_id(true);
                    
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $admin['id'];
                    
                    redirect('index.php');
                } else {
                    $error = "Invalid username or password.";
                }
            } else {
                $error = "Invalid username or password.";
            }
            $stmt->close();
        } else {
            $error = "System error occurred. Please try again later.";
        }
    }
}

// Include Admin Header
require_once __DIR__ . '/common/header.php';
?>

<div class="flex-grow flex items-center justify-center -mt-10">
    <div class="bg-card w-full max-w-md p-8 rounded-2xl shadow-2xl border border-gray-800">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/20 text-primary mb-4">
                <i class="fa-solid fa-lock text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-white">Admin Login</h1>
            <p class="text-gray-400 mt-2">Secure Dashboard Access</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-500/20 text-red-400 border border-red-500/30 p-3 rounded-lg mb-6 text-sm flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-5">
                <label for="username" class="block text-sm font-medium text-gray-400 mb-2">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-user text-gray-500"></i>
                    </div>
                    <input type="text" name="username" id="username" required autocomplete="off"
                           class="w-full pl-10 pr-4 py-3 bg-dark border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                           placeholder="Enter admin username">
                </div>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-key text-gray-500"></i>
                    </div>
                    <input type="password" name="password" id="password" required
                           class="w-full pl-10 pr-4 py-3 bg-dark border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                           placeholder="Enter password">
                </div>
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 shadow-lg shadow-blue-500/30 flex justify-center items-center gap-2">
                Sign In <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="../index.php" class="text-sm text-gray-500 hover:text-white transition-colors flex items-center justify-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Back to Homepage
            </a>
        </div>
    </div>
</div>

<?php 
// Include Admin Bottom
require_once __DIR__ . '/common/bottom.php'; 
?>
