<?php
// config.php - Base configuration and database connection

// Database file path
$db_file = __DIR__ . '/database.sqlite';

try {
    // Create (if not exists) and connect to SQLite database
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Helper to get settings from database
 */
function get_settings($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
        return $stmt ? $stmt->fetch() : false;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Helper to check if admin is logged in
 */
function is_admin() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Redirect to login if not admin
 */
function require_admin() {
    if (!is_admin()) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Normalize URL to ensure it starts with http:// or https://
 */
function normalize_url($url) {
    if (empty($url)) return '';
    if (!preg_match("~^(?:f|ht)tps?://~i", $url) && !str_starts_with($url, '//')) {
        $url = "https://" . $url;
    }
    return $url;
}
?>
