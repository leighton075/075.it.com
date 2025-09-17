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
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = new mysqli("100.114.13.123", "skyline_user", "secure_password", "skyline");
    if ($mysqli->connect_errno) {
        $login_error = "Database connection failed.";
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $stmt = $mysqli->prepare("SELECT user_id, password, first_name FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($user_id, $hash, $first_name);
        if ($stmt->fetch() && password_verify($password, $hash)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['first_name'] = $first_name;
            header("Location: index.php");
            exit();
        } else {
            $login_error = "Invalid email or password.";
        }
        $stmt->close();
        $mysqli->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
<div class="container mt-5">
    <h2 class="title text-center mb-4" style="color: #AD91FF;">Login</h2>
    <?php if ($login_error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($login_error); ?></div>
    <?php endif; ?>
    <form method="POST" action="login.php" style="max-width: 400px; margin: 0 auto;">
        <div class="form-group">
            <label for="email" style="color: #fff;">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password" style="color: #fff;">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block mt-3">Login</button>
    </form>
</div>
<div class="footer mt-5">
    <div class="bot-footer">
        <img src="assets/siteLogoWord.png" width="150" alt="Site Logo Word"><br>
        Copyright © Leighton Simmons - 2025
    </div><br>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="js/now-ui-kit.min.js"></script>
</body>
</html>
</html>
