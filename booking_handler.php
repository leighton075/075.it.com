<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = new mysqli("100.114.13.123", "skyline_user", "secure_password", "skyline");
    if ($mysqli->connect_errno) {
        die("Database connection failed.");
    }
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $date = $_POST['date'] ?? '';
    $guests = $_POST['guests'] ?? '';
    $activity = $_POST['activity'] ?? '';
    if ($first_name && $last_name && $email && $phone && $date && $guests && $activity) {
        $stmt = $mysqli->prepare("INSERT INTO bookings (first_name, last_name, email, phone, date, guests, activity) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssssis", $first_name, $last_name, $email, $phone, $date, $guests, $activity);
            if ($stmt->execute()) {
                echo "<script>alert('Booking successful!'); window.location.href='booking.php';</script>";
            } else {
                echo "<script>alert('Booking failed. Please try again.'); window.location.href='booking.php';</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Database error: " . addslashes($mysqli->error) . "'); window.location.href='booking.php';</script>";
        }
    } else {
        echo "<script>alert('Please fill in all required fields.'); window.location.href='booking.php';</script>";
    }
    $mysqli->close();
} else {
    echo "<script>alert('Invalid request.'); window.location.href='booking.php';</script>";
}
?>
