<?php
$mysqli = new mysqli("localhost", "skyline_user", "secure_password", "skyline");
if ($mysqli->connect_errno) {
    die("Database connection failed: " . $mysqli->connect_error);
}

$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';

if (!$first_name || !$last_name || !$email || !$password) {
    die("Missing required fields.");
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $mysqli->prepare("INSERT INTO users (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $hash);

if ($stmt->execute()) {
    echo "Registration successful! <a href='login.html'>Login here</a>";
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$mysqli->close();
?>