<?php
session_start();
// Only allow access if logged in
if (!isset($_SESSION['user_id'])) {
    echo "Session user_id not set.<br>";
    header("Location: login.php");
    exit();
}
// Connect to database
$mysqli = new mysqli("100.114.13.123", "skyline_user", "secure_password", "skyline");

// Get user info
$user_id = $_SESSION['user_id'];
$user_result = $mysqli->query("SELECT email FROM users WHERE user_id = $user_id");
$user_email = '';
if ($user_result && $user_result->num_rows > 0) {
    $user_email = $user_result->fetch_assoc()['email'];
    $user_email = strtolower(trim($user_email));
    echo "User email from DB: '$user_email'<br>";
} else {
    echo "No user found for user_id: $user_id<br>";
}

// Get booking id to edit
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
echo "Requested booking_id: $id<br>";
if ($id <= 0) {
    echo "Error: No valid booking ID provided in the URL.<br>";
    exit();
}
$booking = null;
if ($id && $user_email) {
    $sql = "SELECT * FROM bookings WHERE booking_id = $id AND LOWER(TRIM(email)) = '{$user_email}'";
    echo "Booking SQL: $sql<br>";
    $booking_result = $mysqli->query($sql);
    if ($booking_result && $booking_result->num_rows > 0) {
        $booking = $booking_result->fetch_assoc();
        echo "Booking found.<br>";
    } else {
        echo "No booking found for booking_id: $id and email: '$user_email'<br>";
    }
} else {
    echo "Booking ID or user email missing.<br>";
}

if (!$booking) {
    echo "Booking not found. Please check the booking ID or make sure you are the owner.<br>";
    exit();
}

// Dump the booking array for inspection
echo "<pre>";
var_dump($booking);
echo "</pre>";

if (!isset($booking['email']) || trim($booking['email']) === '') {
    echo "Booking email is missing or column name is wrong. Please check your database column names and data.<br>";
    exit();
}
echo "User email: '" . $user_email . "'<br>";
echo "Booking email: '" . strtolower(trim($booking['email'])) . "'<br>";
// Only allow editing if booking belongs to user (case-insensitive, trimmed)
if (strtolower(trim($booking['email'])) !== $user_email) {
    echo "Access denied.<br>";
    exit();
}
// Handle form submission to update booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $date = $_POST['date'];
    $guests = intval($_POST['guests']);
    $activity = $_POST['activity'];
    $stmt = $mysqli->prepare("UPDATE bookings SET first_name=?, last_name=?, email=?, phone=?, date=?, guests=?, activity=? WHERE booking_id=?");
    $stmt->bind_param("sssssisi", $first_name, $last_name, $email, $phone, $date, $guests, $activity, $id);
    $stmt->execute();
    header("Location: booking.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Your Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css?v=2">
    <link rel="stylesheet" href="css/main.css?v=2">
    <link rel="stylesheet" href="css/now-ui-kit.css?v=2">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
</head>
<body>
<div class="container mt-5">
    <h2 class="title text-center mb-4" style="color: #AD91FF;">Edit Your Booking</h2>
    <form method="POST" style="max-width: 400px; margin: 0 auto;">
        <div class="form-group">
            <label for="first_name" style="color: #fff;">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($booking['first_name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="last_name" style="color: #fff;">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($booking['last_name']) ?>" required>
        </div>
        <div class="form-group">
            <label for="email" style="color: #fff;">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($booking['email']) ?>" required>
        </div>
        <div class="form-group">
            <label for="phone" style="color: #fff;">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($booking['phone']) ?>">
        </div>
        <div class="form-group">
            <label for="date" style="color: #fff;">Booking Date</label>
            <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($booking['date']) ?>" required>
        </div>
        <div class="form-group">
            <label for="guests" style="color: #fff;">Number of Guests</label>
            <input type="number" class="form-control" id="guests" name="guests" min="1" max="20" value="<?= $booking['guests'] ?>" required>
        </div>
        <div class="form-group">
            <label for="activity" style="color: #fff;">Select Attraction</label>
            <select class="form-control" id="activity" name="activity" required>
                <option value="">Choose...</option>
                <option value="L1" <?= $booking['activity'] == 'L1' ? 'selected' : '' ?>>Luge - 1 Luge Ride ($50.00)</option>
                <option value="L3" <?= $booking['activity'] == 'L3' ? 'selected' : '' ?>>Luge - 3 Luge Rides ($60.00)</option>
                <option value="L5" <?= $booking['activity'] == 'L5' ? 'selected' : '' ?>>Luge - 5 Luge Rides ($66.00)</option>
                <option value="L7" <?= $booking['activity'] == 'L7' ? 'selected' : '' ?>>Luge - 7 Luge Rides ($70.00)</option>
                <option value="HDAP" <?= $booking['activity'] == 'HDAP' ? 'selected' : '' ?>>Half Day Adventure Pass ($149.00)</option>
                <option value="SC" <?= $booking['activity'] == 'SC' ? 'selected' : '' ?>>Skyswing Combo ($101.00)</option>
                <option value="ZC" <?= $booking['activity'] == 'ZC' ? 'selected' : '' ?>>Zipline Combo ($105.00)</option>
                <option value="SMC" <?= $booking['activity'] == 'SMC' ? 'selected' : '' ?>>Skyswing Mega Combo ($104.00)</option>
                <option value="ZMC" <?= $booking['activity'] == 'ZMC' ? 'selected' : '' ?>>Zipline Mega Combo ($111.00)</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-block mt-3">Save Changes</button>
    </form>
    <div class="text-center mt-3">
        <a href="booking.php" class="btn btn-secondary">Back to Bookings</a>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
