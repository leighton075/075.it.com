<?php
session_start();
$mysqli = new mysqli("100.114.13.123", "skyline_user", "secure_password", "skyline");
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$user_id = $_SESSION['user_id'];
$result = $mysqli->query("SELECT is_admin FROM users WHERE user_id = $user_id");
$is_admin = $result->fetch_assoc()['is_admin'] ?? 0;
if (!$is_admin) { echo "Access denied."; exit(); }

$id = intval($_GET['id'] ?? 0);
if ($id) {
    $mysqli->query("DELETE FROM users WHERE user_id = $id");
}
header("Location: admin.php");
exit();
