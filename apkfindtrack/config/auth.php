<?php
// Helper untuk authentication
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../login.php');
        exit();
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: ../user/index.php');
        exit();
    }
}

function requireUser() {
    requireLogin();
    if (isAdmin()) {
        header('Location: ../admin/index.php');
        exit();
    }
}
?>

