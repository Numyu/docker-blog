<?php
session_start();
require("includes/pdo.inc.php");

if(!isset($_SESSION['id'])) {

    // Create admin user
    // Verify if the admin user already exists
    $admin = $db->query("SELECT * FROM users WHERE username = 'admin'");
    $admin = $admin->fetch(PDO::FETCH_ASSOC);
    if(!$admin) {
        $passwordHash = password_hash('admin', PASSWORD_DEFAULT);
        $addUser = $db->query("INSERT INTO users(username, password, permission, created_at, updated_at) VALUES ('admin', '$passwordHash', 1, NOW(), NOW())");
    }
    
    header("Location: pages/login.php");
    exit;
} else if (isset($_SESSION['id'])) {
    header("Location: pages/home.php");
    exit;
}