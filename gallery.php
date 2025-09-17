<?php
session_start();
$is_admin = 0;
$is_logged_in = isset($_SESSION['user_id']);
if ($is_logged_in) {
    $mysqli = new mysqli("100.114.13.123", "skyline_user", "secure_password", "skyline");
    $user_id = $_SESSION['user_id'];
    $result = $mysqli->query("SELECT is_admin FROM users WHERE user_id = $user_id");
    $is_admin = $result ? ($result->fetch_assoc()['is_admin'] ?? 0) : 0;
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Inline critical CSS -->
    <style>
        body { background-color: #1D1E28; color: #fff; font-family: 'Poppins', sans-serif !important; }
        .navbar { box-shadow: none !important; }
        .nav-link { font-size: 14px !important; transition: color 0.2s; }
        .nav-link:hover { color: #AD91FF !important; font-weight: bold !important; border-bottom: 2px solid #AD91FF; }
        .login-btn { border: 2px solid #AD91FF !important; font-size: 10px !important; }
        .login-btn:hover { background: #AD91FF !important; }
        .btn { border-radius: 10px !important; font-size: 16px !important; font-weight: 600 !important; text-transform: uppercase !important; }
        .btn-primary { background-color: #AD91FF!important }
        .btn-secondary { background-color: #2C2F33 !important }
        .footer { text-align: center !important; padding: 30px 30px !important; margin-top: auto !important; color: #ecf2ff; }
        .bot-footer { color: #a2a8bd !important }
        .heading { padding: 160px 50px !important; color: #fff !important; }
        .title { line-height: 90px !important; margin-bottom: -2px !important; font-size: 36px !important }
        .subtitle { color: rgba(255, 255, 255, 0.85) !important; font-size: 18px !important; font-family: 'Lato', sans-serif; font-weight: 400 !important; letter-spacing: 0.02em !important; }
    </style>
    <!-- Load combined/minified CSS file -->
    <link rel="stylesheet" href="css/all.min.css?v=2">
    <noscript>
      <link rel="stylesheet" href="css/all.min.css?v=2">
    </noscript>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <!-- ...existing code... -->
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
            <li class="nav-item"><a class="nav-link" href="index.php#features">Attractions</a></li>
            <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
            <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
            <li class="nav-item"><a class="nav-link" href="booking.php">Book</a></li>
            <?php if ($is_admin): ?>
                <li class="nav-item"><a class="nav-link" href="admin.php" style="color: #AD91FF; font-weight: bold;">Admin Panel</a></li>
            <?php endif; ?>
        </ul>
        <?php if ($is_logged_in): ?>
            <a href="logout.php" class="btn login-btn btn-outline-accent my-2 my-sm-0 ml-2"
                style="font-size: 10px !important;font-family: poppins !important;">LOGOUT</a>
        <?php else: ?>
            <a href="login.php" class="btn login-btn btn-outline-accent my-2 my-sm-0"
                style="font-size: 10px !important;font-family: poppins !important;">LOGIN</a>
            <a href="register.php" class="btn login-btn btn-outline-accent my-2 my-sm-0 ml-2"
                style="font-size: 10px !important;font-family: poppins !important;">REGISTER</a>
        <?php endif; ?>
    </div>
</nav>
<button id="returnTopBtn" class="btn btn-secondary" style="position: fixed; bottom: 32px; right: 32px; z-index: 999; border-radius: 50%; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
    <img src="assets/upArrow.svg" width="24" height="24" alt="Return to top">
</button>
<div class="heading text-center mt-5 mb-4">
    <h1 class="display-5 title">Rotorua Skyline Gallery</h1>
    <p class="subtitle">A selection of photos from our attractions and events.</p>
</div>
<div class="container my-5">
    <div class="row">
        <div class="col-md-6 mb-4 d-flex justify-content-center">
            <img src="assets/Skyline%20Night-4_edit.jpg" class="img-fluid rounded shadow gallery-img" style="max-width: 90%; height: auto;" alt="Skyline Night">
        </div>
        <div class="col-md-6 mb-4 d-flex justify-content-center">
            <img src="assets/Skyline%20Rotorua%205%20(1).jpg" class="img-fluid rounded shadow gallery-img" style="max-width: 90%; height: auto;" alt="Skyline Rotorua 5">
        </div>
        <div class="col-md-6 mb-4 d-flex justify-content-center">
            <img src="assets/Skyline%20Rotorua%20Couple%20(2).jpg" class="img-fluid rounded shadow gallery-img" style="max-width: 90%; height: auto;" alt="Skyline Rotorua Couple">
        </div>
        <div class="col-md-6 mb-4 d-flex justify-content-center">
            <img src="assets/Skyline%20Rotorua%20Gondola%20Sun%20Set%20Photo%201.jpg" class="img-fluid rounded shadow gallery-img" style="max-width: 90%; height: auto;" alt="Gondola Sun Set">
        </div>
        <div class="col-md-6 mb-4 d-flex justify-content-center">
            <img src="assets/Skyline%20Rotorua%20Night%20Luge%20(3).jpg" class="img-fluid rounded shadow gallery-img" style="max-width: 90%; height: auto;" alt="Night Luge">
        </div>
        <div class="col-md-6 mb-4 d-flex justify-content-center">
            <img src="assets/Skyline%20Rotorua%20Zipline%20.jpg" class="img-fluid rounded shadow gallery-img" style="max-width: 90%; height: auto;" alt="Zipline">
        </div>
    </div>
</div>
<div class="page_end">
    <div class="start">
        <div class="card">
            <div class="card-body gs-card">
                <div class="title">Ready book now?</div>
                <div class="subtitle">Whoever you are, whatever mood you&apos;re in - we have something for you!</div>
                <br>
                <a class="btn btn-primary btn-lg" href="booking.php" role="button" id="getStarted">Book Now</a>
            </div><br>
        </div>
    </div>
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