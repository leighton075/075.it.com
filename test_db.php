<?php
$mysqli = new mysqli("100.114.13.123", "skyline_user", "secure_password", "skyline");
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}
echo "Connection successful!";
$mysqli->close();
?>
