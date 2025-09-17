<?php
session_start();
$mysqli = new mysqli("100.114.13.123", "skyline_user", "secure_password", "skyline");
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// Check if user is admin
$user_id = $_SESSION['user_id'];
$result = $mysqli->query("SELECT is_admin FROM users WHERE user_id = $user_id");
$is_admin = $result->fetch_assoc()['is_admin'] ?? 0;
if (!$is_admin) {
    echo "<div class='alert alert-danger'>Access denied. Admins only.</div>";
    exit();
}

// Fetch users
$users = $mysqli->query("SELECT user_id, first_name, last_name, email, phone, is_admin FROM users");
// Fetch bookings
$bookings = $mysqli->query("SELECT booking_id, first_name, last_name, email, phone, date, guests, activity FROM bookings");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
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
</head>
<body>
<nav class="navbar navbar-expand-lg bg-transparent">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <img src="assets/menuIcon.svg" width="20" height="20" style="max-width: none !important;" alt="Menu Icon">
    </button>
    <a href="index.php"><img src="assets/siteLogo.png" width="50" height="50" alt="Site Logo"></a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent" style="margin-left: 20px !important">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="activities.php">Attractions</a></li>
            <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
            <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
            <li class="nav-item"><a class="nav-link" href="booking.php">Book</a></li>
            <?php if ($is_admin): ?>
                <li class="nav-item"><a class="nav-link" href="admin.php" style="color: #AD91FF; font-weight: bold;">Admin Panel</a></li>
            <?php endif; ?>
        </ul>
        <a href="login.php" class="btn login-btn btn-outline-accent my-2 my-sm-0"
            style="font-size: 10px !important;font-family: poppins !important;">LOGIN</a>
        <a href="register.php" class="btn login-btn btn-outline-accent my-2 my-sm-0 ml-2"
            style="font-size: 10px !important;font-family: poppins !important;">REGISTER</a>
    </div>
</nav>
<div class="heading text-center mb-4" style="padding: 80px 50px !important;">
    <h1 class="display-5 title" style="color: #AD91FF;">Admin Dashboard</h1>
    <p class="subtitle">Manage users and bookings below.</p>
</div>
<div class="container my-5">
    <h3 class="title mb-3" style="color: #AD91FF;">Users</h3>
    <table class="table table-dark table-striped rounded shadow" style="background: #1D1E28; border: 4px solid #AD91FF;">
        <thead>
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Admin</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php while ($u = $users->fetch_assoc()): ?>
            <tr>
                <td><?= $u['user_id'] ?></td>
                <td><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['phone']) ?></td>
                <td><?= $u['is_admin'] ? 'Yes' : 'No' ?></td>
                <td>
                    <a href="edit_user.php?id=<?= $u['user_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <?php if ($u['user_id'] != 3): ?>
                        <a href="delete_user.php?id=<?= $u['user_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete user?')">Delete</a>
                    <?php else: ?>
                        <span class="btn btn-sm btn-secondary disabled" title="Cannot delete root admin">Delete</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <h3 class="title mb-3" style="color: #AD91FF;">Bookings</h3>
    <table class="table table-dark table-striped rounded shadow" style="background: #1D1E28; border: 4px solid #AD91FF;">
        <thead>
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Date</th><th>Guests</th><th>Activity</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php while ($b = $bookings->fetch_assoc()): ?>
            <tr>
                <td><?= $b['booking_id'] ?></td>
                <td><?= htmlspecialchars($b['first_name'] . ' ' . $b['last_name']) ?></td>
                <td><?= htmlspecialchars($b['email']) ?></td>
                <td><?= htmlspecialchars($b['phone']) ?></td>
                <td><?= htmlspecialchars($b['date']) ?></td>
                <td><?= $b['guests'] ?></td>
                <td><?= htmlspecialchars($b['activity']) ?></td>
                <td>
                    <a href="edit_booking.php?id=<?= $b['booking_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete_booking.php?id=<?= $b['booking_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete booking?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<div class="footer mt-5">
    <div class="bot-footer">
        <img src="assets/siteLogoWord.png" width="150" alt="Site Logo Word"><br>
        Copyright © Leighton Simmons - 2025
    </div><br>
    <div class="nouridio">support on me <a href="https://ko-fi.com/leighton075" target="_blank">kofi</a></div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="js/now-ui-kit.min.js"></script>
</body>
</html>