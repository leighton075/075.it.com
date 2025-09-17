<?php
session_start();
// Connect to database
$mysqli = new mysqli("100.114.13.123", "skyline_user", "secure_password", "skyline");
// Only allow access if logged in
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
// Check if user is admin
$user_id = $_SESSION['user_id'];
$result = $mysqli->query("SELECT is_admin FROM users WHERE user_id = $user_id");
$is_admin = $result->fetch_assoc()['is_admin'] ?? 0;
if (!$is_admin) { echo "Access denied."; exit(); }

// Get user id to edit
$id = intval($_GET['id'] ?? 0);
// Handle form submission to update user
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
// Fetch user data for editing
$user = $mysqli->query("SELECT * FROM users WHERE user_id = $id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
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
                <h2 class="edit-header mb-4">Edit User</h2>
                <form method="post" action="edit_user.php?id=<?= $user_id ?>">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="is_admin">Admin Status</label>
                        <select class="form-control" id="is_admin" name="is_admin">
                            <option value="0" <?= $user['is_admin'] ? '' : 'selected' ?>>No</option>
                            <option value="1" <?= $user['is_admin'] ? 'selected' : '' ?>>Yes</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-accent">Save Changes</button>
                    <a href="admin.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>