<?php
// Start the session
session_start();

// Connect to database
$mysqli = new mysqli("100.114.13.123", "skyline_user", "secure_password", "skyline");

// Only allow access if logged in
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

// Check if user is admin
$user_id = $_SESSION['user_id'];
$result = $mysqli->query("SELECT is_admin FROM users WHERE user_id = $user_id");
$is_admin = $result->fetch_assoc()['is_admin'] ?? 0;
if (!$is_admin) { 
    echo "Access denied."; 
    exit(); 
}

// Delete booking by id
$id = intval($_GET['id'] ?? 0);
if ($id) {
    $mysqli->query("DELETE FROM bookings WHERE booking_id = $id");
}

// Redirect back to admin panel
header("Location: admin.php");
exit();
