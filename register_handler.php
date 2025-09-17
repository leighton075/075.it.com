<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_error.log');

function log_message($msg) {
    error_log(date('[Y-m-d H:i:s] ') . $msg . "\n", 3, __DIR__ . '/php_error.log');
}

log_message("register_handler.php called");

$mysqli = new mysqli("localhost", "skyline_user", "secure_password", "skyline");
if ($mysqli->connect_errno) {
    log_message("Database connection failed: " . $mysqli->connect_error);
    http_response_code(500);
    echo "Database connection failed.";
    exit();
}
log_message("Database connection successful");

$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';

log_message("Received POST: first_name=$first_name, last_name=$last_name, email=$email, phone=$phone");

if (!$first_name || !$last_name || !$email || !$password) {
    log_message("Missing required fields in registration.");
    http_response_code(400);
    echo "Missing required fields.";
    exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);
log_message("Password hashed");

$stmt = $mysqli->prepare("INSERT INTO users (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    log_message("Prepare failed: " . $mysqli->error);
    http_response_code(500);
    echo "Prepare failed.";
    exit();
}
$stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $hash);

if ($stmt->execute()) {
    log_message("Registration successful for email: $email");
    echo "Registration successful! <a href='login.html'>Login here</a>";
} else {
    log_message("Registration error: " . $stmt->error);
    http_response_code(500);
    echo "Error: " . $stmt->error;
}
$stmt->close();
$mysqli->close();
log_message("register_handler.php finished");
?>