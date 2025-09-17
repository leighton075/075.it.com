<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_error.log');

function log_message($msg) {
    error_log(date('[Y-m-d H:i:s] ') . $msg . "\n", 3, __DIR__ . '/php_error.log');
}

log_message("booking_handler.php called");

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
$date = $_POST['date'] ?? '';
$guests = intval($_POST['guests'] ?? 1);
$activity_id = $_POST['activity'] ?? '';

log_message("Received POST: first_name=$first_name, last_name=$last_name, email=$email, phone=$phone, date=$date, guests=$guests, activity_id=$activity_id");

if (!$first_name || !$last_name || !$email || !$date || !$guests || !$activity_id) {
    log_message("Missing required fields in booking.");
    http_response_code(400);
    echo "Missing required fields.";
    exit();
}

// Insert user (if not exists)
$stmt = $mysqli->prepare("SELECT user_id FROM users WHERE email = ?");
if (!$stmt) {
    log_message("Prepare failed (user select): " . $mysqli->error);
    http_response_code(500);
    echo "Prepare failed.";
    exit();
}
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($user_id);
if ($stmt->fetch()) {
    log_message("User found for booking: user_id=$user_id");
    $stmt->close();
} else {
    $stmt->close();
    log_message("User not found, inserting new user for booking.");
    $stmt = $mysqli->prepare("INSERT INTO users (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, '')");
    if (!$stmt) {
        log_message("Prepare failed (user insert): " . $mysqli->error);
        http_response_code(500);
        echo "Prepare failed.";
        exit();
    }
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $phone);
    $stmt->execute();
    $user_id = $stmt->insert_id;
    log_message("Inserted new user for booking: user_id=$user_id");
    $stmt->close();
}

// Insert booking
$stmt = $mysqli->prepare("INSERT INTO bookings (user_id, activity_id, date, guests) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    log_message("Prepare failed (booking insert): " . $mysqli->error);
    http_response_code(500);
    echo "Prepare failed.";
    exit();
}
$stmt->bind_param("issi", $user_id, $activity_id, $date, $guests);
if ($stmt->execute()) {
    log_message("Booking successful for user_id=$user_id, activity_id=$activity_id, date=$date, guests=$guests");
    echo "Booking successful!";
} else {
    log_message("Booking error: " . $stmt->error);
    http_response_code(500);
    echo "Error: " . $stmt->error;
}
$stmt->close();
$mysqli->close();
log_message("booking_handler.php finished");
?>