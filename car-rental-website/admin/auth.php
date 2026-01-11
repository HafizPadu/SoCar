<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simple authentication check
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Redirect to login if not authenticated
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location:index.php');
        exit();
    }
}

// Login function
function login($username, $password) {
    // Hardcoded credentials for demo
    $valid_username = 'admin';
    $valid_password = 'admin123';
    
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['login_time'] = time();
        return true;
    }
    
    return false;
}

// Logout function
function logout() {
    session_unset();
    session_destroy();
    header('Location:index.php');
    exit();
}
?>