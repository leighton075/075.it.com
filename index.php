<?php
// Remove all PHP login logic from index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rotorua Skyline</title>
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
            <li class="nav-item"><a class="nav-link" href="dining.php">Dining</a></li>
            <li class="nav-item"><a class="nav-link" href="locations.php">Locations</a></li>
            <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
            <li class="nav-item"><a class="nav-link" href="booking.php">Book</a></li>
        </ul>
        <a href="login.php" class="btn login-btn btn-outline-accent my-2 my-sm-0"
            style="font-size: 10px !important;font-family: poppins !important;">LOGIN</a>
        <a href="register.php" class="btn login-btn btn-outline-accent my-2 my-sm-0 ml-2"
            style="font-size: 10px !important;font-family: poppins !important;">REGISTER</a>
    </div>
</nav>
<button id="returnTopBtn" class="btn btn-secondary" style="position: fixed; bottom: 32px; right: 32px; z-index: 999; border-radius: 50%; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
    <img src="assets/upArrow.svg" width="24" height="24" alt="Return to top">
</button>
<div class="heading">
    <div class="d-flex flex-column align-items-center justify-content-center text-center">
        <h1 class="display-5 title">Welcome to the Rotorua Skyline</h1>
        <p class="subtitle">Come and have a go on the Luge Rotorua - good luck stopping at one ride!</p>
        <div class="d-flex justify-content-center mt-3">
            <a class="btn btn-primary btn-lg mx-2 book-now-btn" href="booking.php" role="button">Book Now</a>
            <a class="btn btn-secondary btn-lg mx-2 learn-more-btn" href="#features" role="button">Learn more</a>
        </div>
    </div>
</div>
<div class="features" id="features">
    <!-- ...copy features section from index.html... -->
</div>
<div class="statistics" id="statistics">
    <!-- ...copy statistics section from index.html... -->
</div>
<div class="page_end">
    <!-- ...copy page_end section from index.html... -->
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