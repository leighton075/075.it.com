<?php
session_start();
// Check login and admin status for navbar/buttons
$is_admin = 0;
$is_logged_in = isset($_SESSION['user_id']);
if ($is_logged_in) {
    $mysqli = new mysqli("100.114.13.123", "skyline_user", "secure_password", "skyline");
    $user_id = $_SESSION['user_id'];
    $result = $mysqli->query("SELECT is_admin FROM users WHERE user_id = $user_id");
    $is_admin = $result ? ($result->fetch_assoc()['is_admin'] ?? 0) : 0;
    $mysqli->close();
}

// Fetch current user's bookings if logged in
$user_bookings = null;
if ($is_logged_in) {
    $mysqli = new mysqli("100.114.13.123", "skyline_user", "secure_password", "skyline");
    $user_id = $_SESSION['user_id'];
    $user_bookings = $mysqli->query("SELECT booking_id, first_name, last_name, email, phone, date, guests, activity FROM bookings WHERE email = (SELECT email FROM users WHERE user_id = $user_id)");
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css?v=2">
    <link rel="stylesheet" href="css/main.css?v=2">
    <link rel="stylesheet" href="css/now-ui-kit.css?v=2">
    <noscript>
      <link rel="stylesheet" href="css/main.css">
      <link rel="stylesheet" href="css/now-ui-kit.css">
    </noscript>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700,800,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,600,700,800,900&display=swap" rel="stylesheet">
    <title>Rotorua Skyline</title>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-transparent">
    <!-- Navbar: order and admin panel logic -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <img src="assets/menuIcon.svg" width="20" height="20" alt="Menu Icon">
    </button>
    <a href="index.php"><img src="assets/siteLogo.png" width="50" height="50" alt="Site Logo"></a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent" style="margin-left: 20px !important">
        <ul class="navbar-nav mr-auto">
            <!-- Main navigation order -->
            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php#features">Attractions</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php#statistics">Statistics</a></li>
            <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
            <li class="nav-item"><a class="nav-link" href="booking.php">Book</a></li>
            <li class="nav-item"><a class="nav-link" href="edit_my_booking.php">Edit Booking</a></li>
            <?php if ($is_admin): ?>
                <!-- Only show for admins -->
                <li class="nav-item"><a class="nav-link" href="admin.php" style="color: #AD91FF; font-weight: bold;">Admin Panel</a></li>
            <?php endif; ?>
        </ul>
        <!-- Consistent button sizes -->
        <?php if ($is_logged_in): ?>
            <a href="logout.php" class="btn login-btn btn-outline-accent my-2 my-sm-0 ml-2" style="font-size: 10px !important; font-family: poppins !important;">LOGOUT</a>
        <?php else: ?>
            <a href="login.php" class="btn login-btn btn-outline-accent my-2 my-sm-0" style="font-size: 10px !important; font-family: poppins !important;">LOGIN</a>
            <a href="register.php" class="btn login-btn btn-outline-accent my-2 my-sm-0 ml-2" style="font-size: 10px !important; font-family: poppins !important;">REGISTER</a>
        <?php endif; ?>
    </div>
</nav>
<button id="returnTopBtn" class="btn btn-secondary" style="position: fixed; bottom: 32px; right: 32px; z-index: 999; border-radius: 50%; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
    <img src="assets/upArrow.svg" width="24" height="24" alt="Return to top">
</button>
<div class="heading">
    <div class="text-center">
        <h1 class="display-5 title">Book Your Rotorua Skyline Experience</h1>
        <p class="subtitle">Reserve your spot for the Luge, Gondala, Skyswing, or Mountain Bike Park<br>Fill out the form below and we’ll see you soon!</p>
    </div>
</div>
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh; width: 100vw;">
    <div class="card shadow-lg" style="background: #1D1E28; border: 4px solid #AD91FF; width: 100vw; padding: 120px 0; border-radius: 0;">
        <div class="card-body d-flex flex-column align-items-center p-0" style="width: 100%;">
            <h2 class="card-title mb-4 text-center" style="color: #AD91FF;">Book Your Skyline Experience</h2>
            <form method="POST" action="booking_handler.php" style="width: 100%; max-width: 700px;">
                <div class="form-group">
                    <label for="first_name" style="color: #fff;">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter your first name" required>
                </div>
                <div class="form-group">
                    <label for="last_name" style="color: #fff;">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter your last name" required>
                </div>
                <div class="form-group">
                    <label for="email" style="color: #fff;">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="phone" style="color: #fff;">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
                </div>
                <div class="form-group">
                    <label for="date" style="color: #fff;">Booking Date</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <div class="form-group">
                    <label for="guests" style="color: #fff;">Number of Guests</label>
                    <input type="number" class="form-control" id="guests" name="guests" min="1" max="20" value="1" required>
                </div>
                <div class="form-group">
                    <label for="attraction" style="color: #fff;">Select Attraction</label>
                    <select class="form-control" id="attraction" name="activity" required>
                        <option value="">Choose...</option>
                        <option value="L1">Luge - 1 Luge Ride ($50.00)</option>
                        <option value="L3">Luge - 3 Luge Rides ($60.00)</option>
                        <option value="L5">Luge - 5 Luge Rides ($66.00)</option>
                        <option value="L7">Luge - 7 Luge Rides ($70.00)</option>
                        <option value="HDAP">Half Day Adventure Pass ($149.00)</option>
                        <option value="SC">Skyswing Combo ($101.00)</option>
                        <option value="ZC">Zipline Combo ($105.00)</option>
                        <option value="SMC">Skyswing Mega Combo ($104.00)</option>
                        <option value="ZMC">Zipline Mega Combo ($111.00)</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block mt-3">Submit Booking</button>
            </form>
        </div>
    </div>
</div>
<?php if ($is_logged_in && $user_bookings && $user_bookings->num_rows > 0): ?>
<div class="container my-5">
    <h3 class="title mb-3" style="color: #AD91FF;">Your Bookings</h3>
    <table class="table table-dark table-striped rounded shadow" style="background: #1D1E28; border: 4px solid #AD91FF;">
        <thead>
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Date</th><th>Guests</th><th>Activity</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php while ($b = $user_bookings->fetch_assoc()): ?>
            <tr>
                <td><?= $b['booking_id'] ?></td>
                <td><?= htmlspecialchars($b['first_name'] . ' ' . $b['last_name']) ?></td>
                <td><?= htmlspecialchars($b['email']) ?></td>
                <td><?= htmlspecialchars($b['phone']) ?></td>
                <td><?= htmlspecialchars($b['date']) ?></td>
                <td><?= $b['guests'] ?></td>
                <td><?= htmlspecialchars($b['activity']) ?></td>
                <td>
                    <!-- Make sure booking_id is passed as a GET parameter -->
                    <a href="edit_my_booking.php?id=<?= urlencode($b['booking_id']) ?>" class="btn btn-sm btn-primary" style="background-color:#007bff;border-color:#007bff;">Edit</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
<div class="page_end">
    <div class="footer">
        <div class="bot-footer">
            <img src="assets/siteLogoWord.png" width="150" alt="Site Logo Word"><br>
            Copyright © Leighton Simmons - 2025
        </div><br>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="js/now-ui-kit.min.js"></script>
<script>
$('#returnTopBtn').on('click', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
</script>
</body>
</html>
</body>
</html>
