<?php
session_start();
require_once __DIR__ . '/../../includes/db_config.php';

// Helper: Check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_token']) && validateAdminSession($_SESSION['admin_token']);
}

// Helper: Require admin login
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// Helper: Get current admin data
function getCurrentAdmin() {
    return $_SESSION['admin_data'] ?? null;
}

// Helper: Admin logout
function adminLogout() {
    session_destroy();
    header('Location: ../login.php');
    exit;
}

// Helper: Set flash message
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

// Helper: Get and clear flash message
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $msg = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $msg;
    }
    return null;
}

// Helper: Display flash message
function displayFlashMessage() {
    $flash = getFlashMessage();
    if ($flash) {
        $type = htmlspecialchars($flash['type']);
        $message = htmlspecialchars($flash['message']);
        echo "<div class='alert alert-{$type}'>{$message}</div>";
    }
}

// Helper: Validate admin session (stub, expand as needed)
function validateAdminSession($token) {
    // For demo: just check if token exists
    return isset($_SESSION['admin_data']);
}

// Helper: Authenticate admin (stub, expand as needed)
function authenticateAdmin($username, $password) {
    global $pdo;
    if ($pdo === null) {
        setFlashMessage('danger', 'Database not available. Please set up the database.');
        return false;
    }
    $stmt = $pdo->prepare('SELECT * FROM Admin WHERE username = ?');
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($admin && password_verify($password, $admin['password_hash'])) {
        return $admin;
    }
    return false;
} 