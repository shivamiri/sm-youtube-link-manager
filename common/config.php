<?php
// Session Start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Configuration
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'sm_yt_links');

// Create Connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// --- Helper Functions ---

// Redirect Function
function redirect($url) {
    header("Location: $url");
    exit;
}

// Sanitize Input (XSS & SQLi protection layer)
function sanitize($input) {
    global $conn;
    return htmlspecialchars(strip_tags($conn->real_escape_string(trim($input))));
}

// Authentication Check
function is_admin_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Extract YouTube Video ID from URL
function get_youtube_id($url) {
    $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i';
    if (preg_match($pattern, $url, $match)) {
        return $match[1]; // Returns the 11 character ID
    }
    return false;
}

// Image Upload Function
// Returns array with status and filename/message
function upload_image($file, $target_dir = '../upload/') {
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB limit

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['status' => false, 'message' => 'Upload error or no file selected.'];
    }

    if (!in_array($file['type'], $allowed_types)) {
        return ['status' => false, 'message' => 'Invalid file type. Allowed: JPG, PNG, WEBP.'];
    }

    if ($file['size'] > $max_size) {
        return ['status' => false, 'message' => 'File size exceeds 5MB limit.'];
    }

    // Generate unique filename
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = uniqid('sm_') . '_' . time() . '.' . $ext;
    
    // Ensure absolute path handling if needed, based on caller location
    $destination = __DIR__ . '/../upload/' . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['status' => true, 'filename' => $new_filename];
    } else {
        return ['status' => false, 'message' => 'Failed to move uploaded file. Check folder permissions.'];
    }
}

// Fetch Channel Settings Function
function get_channel_settings() {
    global $conn;
    $result = $conn->query("SELECT * FROM channel_settings LIMIT 1");
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return [];
}

// Global variable for settings so we don't query multiple times
$settings = get_channel_settings();
?>
