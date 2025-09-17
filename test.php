<?php
$mysqli = new mysqli("localhost", "skyline_user", "secure_password", "skyline");
if ($mysqli->connect_errno) {
    echo "Failed to connect: " . $mysqli->connect_error;
} else {
    echo "Connection successful!";
}
?>