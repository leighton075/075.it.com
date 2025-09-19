<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
$register_error = '';
$register_success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mysqli = new mysqli("100.114.13.123", "skyline_user", "secure_password", "skyline");
    if ($mysqli->connect_errno) {
        $register_error = "Database connection failed.";
    } else {
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!preg_match('/^[a-zA-Z]+$/', $first_name)) {
            $register_error = "First name must contain only letters.";
        } elseif (!preg_match('/^[a-zA-Z]+$/', $last_name)) {
            $register_error = "Last name must contain only letters.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $register_error = "Please enter a valid email address.";
        } elseif ($phone && !preg_match('/^[0-9+\-\s]+$/', $phone)) {
            $register_error = "Phone number must contain only numbers, spaces, + or -.";
        } elseif (!$first_name || !$last_name || !$email || !$password) {
            $register_error = "Please fill in all required fields.";
        }

        if (empty($register_error)) {
            // Check if email already exists
            $check_stmt = $mysqli->prepare("SELECT user_id FROM users WHERE email = ?");
            $check_stmt->bind_param("s", $email);
            $check_stmt->execute();
            $check_stmt->store_result();
            if ($check_stmt->num_rows > 0) {
                $register_error = "Email is already registered. Please log in.";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $mysqli->prepare("INSERT INTO users (first_name, last_name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $hash);
                    if ($stmt->execute()) {
                        $register_success = "Registration successful! You can now log in.";
                    } else {
                        $register_error = "Registration failed. Please try again.";
                    }
                    $stmt->close();
                } else {
                    $register_error = "Database error: " . $mysqli->error;
                }
            }
            $check_stmt->close();
        }
        $mysqli->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Preconnects for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://code.jquery.com">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://stackpath.bootstrapcdn.com">
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
            <li class="nav-item"><a class="nav-link" href="index.php#features">Attractions</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php#statistics">Statistics</a></li>
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
    <h2 class="title text-center mb-4" style="color: #AD91FF;">Register</h2>
    <?php if ($register_error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($register_error); ?></div>
    <?php endif; ?>
    <?php if ($register_success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($register_success); ?></div>
    <?php endif; ?>
    <form method="POST" action="register.php" style="max-width: 400px; margin: 0 auto;">
        <div class="form-group">
            <label for="first_name" style="color: #fff;">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required>
        </div>
        <div class="form-group">
            <label for="last_name" style="color: #fff;">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required>
        </div>
        <div class="form-group">
            <label for="email" style="color: #fff;">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="phone" style="color: #fff;">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone">
        </div>
        <div class="form-group">
            <label for="password" style="color: #fff;">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block mt-3">Register</button>
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
