<?php
session_start();
$mysqli = new mysqli("100.114.13.123", "skyline_user", "secure_password", "skyline");
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$user_id = $_SESSION['user_id'];
$result = $mysqli->query("SELECT is_admin FROM users WHERE user_id = $user_id");
$is_admin = $result->fetch_assoc()['is_admin'] ?? 0;
if (!$is_admin) { echo "Access denied."; exit(); }

$id = intval($_GET['id'] ?? 0);
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
    header("Location: admin.php");
    exit();
}
$booking = $mysqli->query("SELECT * FROM bookings WHERE booking_id = $id")->fetch_assoc();
?>
<!DOCTYPE html>
<html><body>
<h2>Edit Booking</h2>
<form method="POST">
    <input name="first_name" value="<?= htmlspecialchars($booking['first_name']) ?>" required>
    <input name="last_name" value="<?= htmlspecialchars($booking['last_name']) ?>" required>
    <input name="email" value="<?= htmlspecialchars($booking['email']) ?>" required>
    <input name="phone" value="<?= htmlspecialchars($booking['phone']) ?>">
    <input name="date" type="date" value="<?= htmlspecialchars($booking['date']) ?>" required>
    <input name="guests" type="number" value="<?= $booking['guests'] ?>" min="1" max="20" required>
    <input name="activity" value="<?= htmlspecialchars($booking['activity']) ?>" required>
    <button type="submit">Save</button>
</form>
<a href="admin.php">Back</a>
</body></html>
