<?php
$mysqli = new mysqli("localhost", "skyline_user", "secure_password", "skyline");
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo "Database connection failed: " . $mysqli->connect_error;
    exit();
}

// Get and validate POST data
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$date = $_POST['date'] ?? '';
$guests = intval($_POST['guests'] ?? 1);
$activity_id = $_POST['activity'] ?? '';

if (!$first_name || !$last_name || !$email || !$date || !$guests || !$activity_id) {
    http_response_code(400);
    echo "Missing required fields.";
    exit();
}

// Insert user (if not exists)
$stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($user_id);
if ($stmt->fetch()) {
    $stmt->close();
} else {
    $stmt->close();
    $stmt = $mysqli->prepare("INSERT INTO users (first_name, last_name, email, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $phone);
    $stmt->execute();
    $user_id = $stmt->insert_id;
    $stmt->close();
}

// Insert booking
$stmt = $mysqli->prepare("INSERT INTO bookings (user_id, activity_id, date, guests) VALUES (?, ?, ?, ?)");
$stmt->bind_param("issi", $user_id, $activity_id, $date, $guests);
if ($stmt->execute()) {
    echo "Booking successful!";
} else {
    http_response_code(500);
    echo "Error: " . $stmt->error;
}
$stmt->close();
$mysqli->close();
?>