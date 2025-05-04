<?php
// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session handling with improved security
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 1800, // 30 minutes
        'cookie_secure'   => isset($_SERVER['HTTPS']),
        'cookie_httponly' => true,
        'use_strict_mode' => true
    ]);
}

// Session expiration logic
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header("Location: ../users/login.php");
    exit();
}
$_SESSION['last_activity'] = time();

// Regenerate session ID periodically to prevent fixation
if (!isset($_SESSION['regenerated']) || (time() - $_SESSION['regenerated']) > 300) {
    session_regenerate_id(true);
    $_SESSION['regenerated'] = time();
}

// Database connection with error handling
try {
    $conn = new mysqli("localhost", "root", "", "fasts");
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("System temporarily unavailable. Please try again later.");
}
?>