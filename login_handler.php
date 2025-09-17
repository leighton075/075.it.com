<?php
session_start();
$mysqli = new mysqli("localhost", "skyline_user", "secure_password", "skyline");
if ($mysqli->connect_errno) {
    die("Database connection failed: " . $mysqli->connect_error);
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    die("Missing required fields.");
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