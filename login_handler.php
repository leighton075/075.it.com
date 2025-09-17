<?php
session_start();
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_error.log');

function log_message($msg) {
    error_log(date('[Y-m-d H:i:s] ') . $msg . "\n", 3, __DIR__ . '/php_error.log');
}

$mysqli = new mysqli("localhost", "skyline_user", "secure_password", "skyline");
if ($mysqli->connect_errno) {
    log_message("Database connection failed: " . $mysqli->connect_error);
    http_response_code(500);
    echo "Database connection failed.";
    exit();
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    log_message("Missing required fields in registration.");
    http_response_code(400);
    echo "Missing required fields.";
    exit();
}

$stmt = $mysqli->prepare("SELECT id, password, first_name FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($user_id, $hash, $first_name);

if ($stmt->fetch() && password_verify($password, $hash)) {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['first_name'] = $first_name;
    echo "Login successful! <a href='booking.html'>Go to booking</a>";
} else {
    echo "Invalid email or password.";
}
$stmt->close();
$mysqli->close();
?>