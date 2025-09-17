<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to the database
    $mysqli = new mysqli("100.114.13.123", "skyline_user", "secure_password", "skyline");
    if ($mysqli->connect_errno) {
        die("Database connection failed.");
    }
    // Get and sanitize form input
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $date = $_POST['date'] ?? '';
    $guests = $_POST['guests'] ?? '';
    $activity = $_POST['activity'] ?? '';
    // Validate required fields
    if ($first_name && $last_name && $email && $phone && $date && $guests && $activity) {
        // Insert booking into database
        $stmt = $mysqli->prepare("INSERT INTO bookings (first_name, last_name, email, phone, date, guests, activity) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssssis", $first_name, $last_name, $email, $phone, $date, $guests, $activity);
            if ($stmt->execute()) {
                // Success: show alert and redirect
                echo "<script>alert('Booking successful!'); window.location.href='booking.php';</script>";
            } else {
                // Failure: show alert and redirect
                echo "<script>alert('Booking failed. Please try again.'); window.location.href='booking.php';</script>";
            }
            $stmt->close();
        } else {
            // Database error: show alert and redirect
            echo "<script>alert('Database error: " . addslashes($mysqli->error) . "'); window.location.href='booking.php';</script>";
        }
    } else {
        // Missing fields: show alert and redirect
        echo "<script>alert('Please fill in all required fields.'); window.location.href='booking.php';</script>";
    }
    $mysqli->close();
} else {
    // Invalid request method: show alert and redirect
    echo "<script>alert('Invalid request.'); window.location.href='booking.php';</script>";
}
?>
