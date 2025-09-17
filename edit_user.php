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
    $is_admin_val = isset($_POST['is_admin']) ? 1 : 0;
    $stmt = $mysqli->prepare("UPDATE users SET first_name=?, last_name=?, email=?, phone=?, is_admin=? WHERE user_id=?");
    $stmt->bind_param("ssssii", $first_name, $last_name, $email, $phone, $is_admin_val, $id);
    $stmt->execute();
    header("Location: admin.php");
    exit();
}
$user = $mysqli->query("SELECT * FROM users WHERE user_id = $id")->fetch_assoc();
?>
<!DOCTYPE html>
<html><body>
<h2>Edit User</h2>
<form method="POST">
    <input name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
    <input name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
    <input name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    <input name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
    <label><input type="checkbox" name="is_admin" <?= $user['is_admin'] ? 'checked' : '' ?>> Admin</label>
    <button type="submit">Save</button>
</form>
<a href="admin.php">Back</a>
</body></html>
