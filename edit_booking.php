<?php
session_start();
// Connect to database
$mysqli = new mysqli("100.114.13.123", "skyline_user", "secure_password", "skyline");
// Only allow access if logged in
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
// Check if user is admin
$user_id = $_SESSION['user_id'];
$result = $mysqli->query("SELECT is_admin, email FROM users WHERE user_id = $user_id");
$user_row = $result->fetch_assoc();
$is_admin = $user_row['is_admin'] ?? 0;
$user_email = $user_row['email'] ?? '';
// Get booking id to edit
$id = intval($_GET['id'] ?? 0);
// Fetch booking data for editing
$booking = $mysqli->query("SELECT * FROM bookings WHERE booking_id = $id")->fetch_assoc();
// Only allow admin or owner of booking to edit
if (!$is_admin && $booking['email'] !== $user_email) { echo "Access denied."; exit(); }
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
    // Redirect user to booking.php if not admin, else to admin.php
    if ($is_admin) {
        header("Location: admin.php");
    } else {
        header("Location: booking.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css?v=2">
    <link rel="preload" as="style" href="css/main.css?v=2" onload="this.rel='stylesheet'">
    <link rel="preload" as="style" href="css/now-ui-kit.css?v=2" onload="this.rel='stylesheet'">
    <noscript>
      <link rel="stylesheet" href="css/main.css?v=2">
      <link rel="stylesheet" href="css/now-ui-kit.css?v=2">
    </noscript>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <style>
        .edit-card { background: #1D1E28; border: 4px solid #AD91FF; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.3); }
        .edit-header { color: #AD91FF; font-weight: bold; }
        .btn-accent { background-color: #AD91FF; border-color: #AD91FF; color: #fff; }
        .btn-accent:hover { background-color: #8a6cff; border-color: #8a6cff; }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="edit-card p-4">
                <h2 class="edit-header mb-4">Edit Booking</h2>
                <form method="post" action="edit_booking.php?id=<?= $booking_id ?>">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($booking['first_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($booking['last_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($booking['email']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($booking['phone']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($booking['date']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="guests">Guests</label>
                        <input type="number" class="form-control" id="guests" name="guests" value="<?= htmlspecialchars($booking['guests']) ?>" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="activity">Activity</label>
                        <input type="text" class="form-control" id="activity" name="activity" value="<?= htmlspecialchars($booking['activity']) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-accent">Save Changes</button>
                    <a href="admin.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="js/now-ui-kit.min.js"></script>
</body>
</html>
